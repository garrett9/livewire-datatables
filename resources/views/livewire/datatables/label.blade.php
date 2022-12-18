<div
	class="whitespace-no-wrap @if ($column['headerAlign'] === 'right') text-right @elseif($column['headerAlign'] === 'center') text-center @else text-left @endif {{ $this->cellClasses($row, $column) }} table-cell px-6 py-2"
>
	{!! $column['content'] ?? '' !!}
</div>
