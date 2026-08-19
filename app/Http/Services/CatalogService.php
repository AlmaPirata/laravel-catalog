<?php

namespace App\Http\Services;

use App\Models\Group;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CatalogService
{
    public function __construct(
        private CatalogTreeService $catalogTreeService,
    ) {}

    public function getRootCatalog(?string $sort, int $perPage): array
    {
        $allGroups = $this->getAllGroups();
        $childrenByParent = $allGroups->groupBy('id_parent');
        $groups = $childrenByParent->get(0, collect());

        return [
            'groups' => $groups,
            'productCounts' => $this->catalogTreeService->getProductCounts(
                $groups,
                $childrenByParent,
                $this->getDirectProductCounts(),
            ),
            'products' => $this->getProducts($sort, perPage: $this->getPerPage($perPage)),
        ];
    }

    public function getGroupCatalog(Group $group, ?string $sort, int $perPage): array
    {
        $allGroups = $this->getAllGroups();
        $childrenByParent = $allGroups->groupBy('id_parent');

        return [
            'groups' => $this->catalogTreeService->buildTree($allGroups),
            'productCounts' => $this->catalogTreeService->getProductCounts(
                $allGroups,
                $childrenByParent,
                $this->getDirectProductCounts(),
            ),
            'products' => $this->getProducts(
                $sort,
                $this->catalogTreeService->getIdsGroupAndSubgroup(
                    $group->id,
                    $childrenByParent,
                ),
                $this->getPerPage($perPage),
            ),
            'groupPathIds' => $this->catalogTreeService->getGroupPathIds(
                $group->id,
                $allGroups,
            ),
        ];
    }

    public function getProductCard(Product $product): array
    {
        $groups = $this->getAllGroups();

        return [
            'product' => $product->load('price'),
            'breadcrumbs' => $this->catalogTreeService->getBreadcrumbs(
                $product->id_group,
                $groups,
            ),
        ];
    }

    private function getAllGroups(): Collection
    {
        return Group::query()
            ->select(['id', 'id_parent', 'name'])
            ->orderBy('id')
            ->get();
    }

    private function getDirectProductCounts(): Collection
    {
        return Product::query()
            ->selectRaw('id_group, count(*) as products_count')
            ->groupBy('id_group')
            ->pluck('products_count', 'id_group');
    }

    private function getProducts(
        ?string $sort,
        array $groupIds = [],
        int $perPage = 6,
    ): LengthAwarePaginator {
        $sortVariations = [
            'price_asc' => ['prices.price', 'asc'],
            'price_desc' => ['prices.price', 'desc'],
            'name_asc' => ['products.name', 'asc'],
            'name_desc' => ['products.name', 'desc'],
        ];

        [$column, $direction] = $sortVariations[$sort] ?? ['products.id', 'asc'];

        $productsQuery = Product::query()
            ->with('price:id,id_product,price')
            ->leftJoin('prices', 'prices.id_product', '=', 'products.id')
            ->select('products.*');

        if ($groupIds !== []) {
            $productsQuery->whereIn('products.id_group', $groupIds);
        }

        return $productsQuery
            ->orderBy($column, $direction)
            ->paginate($perPage)
            ->withQueryString();
    }

    private function getPerPage(int $perPage): int
    {
        return in_array($perPage, [6, 12, 18], true) ? $perPage : 6;
    }
}
