<?php

namespace App\Http\Controllers;

use App\Http\Services\CatalogService;
use App\Models\Group;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function __construct(
        private CatalogService $catalogService,
    ) {}

    public function index(Request $request): View
    {
        return view(
            'catalog.index',
            $this->catalogService->getRootCatalog(
                $request->query('sort'),
                $request->integer('per_page', 6),
            ),
        );
    }

    public function showGroup(Group $group, Request $request): View
    {
        return view(
            'catalog.group',
            [
                'group' => $group,
                ...$this->catalogService->getGroupCatalog(
                    $group,
                    $request->query('sort'),
                    $request->integer('per_page', 6),
                ),
            ],
        );
    }

    public function showProduct(Product $product): View
    {
        return view('catalog.product', $this->catalogService->getProductCard($product));
    }
}
