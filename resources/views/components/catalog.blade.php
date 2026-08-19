@props(['groups', 'productCounts', 'groupPathIds' => []])

<ul class="mb-0">
    @foreach ($groups as $group)
        <li>
            <a href="{{ route('groups.show', array_merge(['group' => $group], request()->only(['sort', 'per_page']))) }}">
                {{ $group->name }} ({{ $productCounts[$group->id] }})
            </a>

            @if (in_array((int) $group->id, $groupPathIds, true) && $group->children->isNotEmpty())
                <x-catalog
                    :groups="$group->children"
                    :product-counts="$productCounts"
                    :group-path-ids="$groupPathIds"
                />
            @endif
        </li>
    @endforeach
</ul>
