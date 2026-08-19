<?php

namespace Tests\Unit;

use App\Http\Services\CatalogTreeService;
use App\Models\Group;
use PHPUnit\Framework\TestCase;

class CatalogTreeServiceTest extends TestCase
{
    public function test_find_all_nested_groups(): void
    {
        $groups = collect([
            $this->makeGroup(1, 0, 'Электроника'),
            $this->makeGroup(2, 1, 'Комплектующие'),
            $this->makeGroup(3, 2, 'Накопители'),
            $this->makeGroup(4, 3, 'SSD'),
            $this->makeGroup(5, 1, 'Моноблоки'),
        ]);

        $groupIds = (new CatalogTreeService)->getIdsGroupAndSubgroup(
            2,
            $groups->groupBy('id_parent'),
        );

        $this->assertSame([2, 3, 4], $groupIds);
    }

    public function test_breadcrumbs_from_root_to_current_group(): void
    {
        $groups = collect([
            $this->makeGroup(1, 0, 'Электроника'),
            $this->makeGroup(4, 1, 'Телефоны и смарт-часы'),
            $this->makeGroup(12, 4, 'Смарт-часы и фитнес-браслеты'),
            $this->makeGroup(24, 12, 'Смарт-часы'),
        ]);

        $breadcrumbs = (new CatalogTreeService)->getBreadcrumbs(24, $groups);

        $this->assertSame(
            [1, 4, 12, 24],
            collect($breadcrumbs)->pluck('id')->all(),
        );
    }

    public function test_calculates_product_counts_for_nested_groups(): void
    {
        $groups = collect([
            $this->makeGroup(1, 0, 'Электроника'),
            $this->makeGroup(2, 1, 'Комплектующие'),
            $this->makeGroup(3, 2, 'Накопители'),
            $this->makeGroup(4, 1, 'Моноблоки'),
        ]);

        $productCounts = (new CatalogTreeService)->getProductCounts(
            $groups,
            $groups->groupBy('id_parent'),
            collect([
                1 => 1,
                2 => 2,
                3 => 3,
                4 => 4,
            ]),
        );

        $this->assertSame([
            1 => 10,
            2 => 5,
            3 => 3,
            4 => 4,
        ], $productCounts->all());
    }

    private function makeGroup(int $id, int $parentId, string $name): Group
    {
        $group = new Group;
        $group->id = $id;
        $group->id_parent = $parentId;
        $group->name = $name;

        return $group;
    }
}
