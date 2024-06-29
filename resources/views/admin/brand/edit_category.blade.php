@php
    $value = null;
    for ($i=0; $i < $child_category->level; $i++){
        $value .= '--';
    }
    $selected = (in_array($child_category->id,$brand_cat)) ? 'selected' : '';
@endphp
<option value="{{ $child_category->id }}" {{$selected}}>{{ $value." ".$child_category->category_name }}</option>
@if ($child_category->categories)
    @foreach ($child_category->categories as $childCategory)
        @include('admin.brand.edit_category', ['child_category' => $childCategory])
    @endforeach
@endif
