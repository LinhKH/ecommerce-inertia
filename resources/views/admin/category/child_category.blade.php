@php
    $value = null;
    for ($i = 0; $i < $child_category->level; $i++) {
        $value .= '--';
    }
@endphp
<option value="{{ $child_category->id }}">{{ $value . ' ' . $child_category->category_name }}</option>
@if ($child_category->categories)
    @foreach ($child_category->categories as $childCategory)
        @include('admin.category.child_category', ['child_category' => $childCategory])
    @endforeach
@endif
