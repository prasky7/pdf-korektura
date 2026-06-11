@props(['field', 'sortField' => '', 'sortDirection' => 'asc'])
@if($sortField === $field)
    @if($sortDirection === 'asc')
        <span class="ml-1">&#9650;</span>
    @else
        <span class="ml-1">&#9660;</span>
    @endif
@else
    <span class="ml-1 text-gray-300">&#9650;&#9660;</span>
@endif
