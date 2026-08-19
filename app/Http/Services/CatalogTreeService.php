<?php

namespace App\Http\Services;

use Illuminate\Support\Collection;

class CatalogTreeService
{
    public function getProductCounts(
        Collection $groups,
        Collection $childrenByParent,
        Collection $directProductCounts,
    ): Collection {
        $productCounts = collect();
        $calculatedCounts = [];

        foreach ($groups as $group) {
            $count = $this->countProductsInGroup(
                $group->id,
                $childrenByParent,
                $directProductCounts,
                $calculatedCounts,
            );

            $productCounts->put($group->id, $count);
        }

        return $productCounts;
    }

    public function buildTree(Collection $groups): Collection
    {
        $groupsById = $groups->keyBy('id');
        $rootGroups = collect();

        foreach ($groupsById as $group) {
            $group->setRelation('children', collect());
        }

        foreach ($groupsById as $group) {
            if ((int) $group->id_parent === 0) {
                $rootGroups->push($group);

                continue;
            }

            $parent = $groupsById->get($group->id_parent);

            if ($parent !== null) {
                $parent->children->push($group);
            }
        }

        return $rootGroups;
    }

    public function getIdsGroupAndSubgroup(
        int $groupId,
        Collection $childrenByParent,
    ): array {
        $groupIds = [$groupId];

        foreach ($childrenByParent->get($groupId, collect()) as $child) {
            $childGroupIds = $this->getIdsGroupAndSubgroup(
                $child->id,
                $childrenByParent,
            );

            $groupIds = array_merge($groupIds, $childGroupIds);
        }

        return $groupIds;
    }

    public function getBreadcrumbs(int $groupId, Collection $groups): array
    {
        $groupsById = $groups->keyBy('id');
        $breadcrumbs = [];
        $currentGroup = $groupsById->get($groupId);

        while ($currentGroup !== null) {
            array_unshift($breadcrumbs, $currentGroup);

            if ((int) $currentGroup->id_parent === 0) {
                break;
            }

            $currentGroup = $groupsById->get($currentGroup->id_parent);
        }

        return $breadcrumbs;
    }

    public function getGroupPathIds(int $groupId, Collection $groups): array
    {
        $groupPathIds = [];
        $breadcrumbs = $this->getBreadcrumbs($groupId, $groups);

        foreach ($breadcrumbs as $group) {
            $groupPathIds[] = $group->id;
        }

        return $groupPathIds;
    }

    private function countProductsInGroup(
        int $groupId,
        Collection $childrenByParent,
        Collection $directProductCounts,
        array &$calculatedCounts,
    ): int {
        if (isset($calculatedCounts[$groupId])) {
            return $calculatedCounts[$groupId];
        }

        $count = (int) $directProductCounts->get($groupId, 0);
        $children = $childrenByParent->get($groupId, collect());

        foreach ($children as $child) {
            $count += $this->countProductsInGroup(
                $child->id,
                $childrenByParent,
                $directProductCounts,
                $calculatedCounts,
            );
        }

        $calculatedCounts[$groupId] = $count;

        return $count;
    }
}
