<div
	x-data
	class="flex flex-col py-2"
>
	<x-assetsheld::forms.select
		wire:input="doSelectFilter('{{ $index }}', $event.target.value);"
		name="{{ $name }}"
		class="!h-7 !text-xs"
		x-ref="select"
		x-init="$refs.select.value = ''"
		x-on:input="$refs.select.value=''"
	>
		<option
			selected
			value=""
			disabled
		>Select a {{ $column['label'] }}</option>
		@foreach ($options as $value => $label)
			@if (is_object($label))
				<option value="{{ $label->id }}">{{ $label->name }}</option>
			@elseif(is_array($label))
				<option value="{{ $label['id'] }}">{{ $label['name'] }}</option>
			@elseif(is_numeric($value))
				<option value="{{ $label }}">{{ $label }}</option>
			@else
				<option value="{{ $value }}">{{ $label }}</option>
			@endif
		@endforeach
	</x-assetsheld::forms.select>

	@if ($this->activeSelectFilters[$index] ?? [])
		<div class="flex flex-wrap">
			@foreach ($this->activeSelectFilters[$index] ?? [] as $key => $value)
				<button
					wire:click="removeSelectFilter('{{ $index }}', '{{ $key }}')"
					class="flex-inline mt-2 mr-1 flex items-center space-x-1 rounded-full border bg-gray-100 px-2 py-1 text-xs hover:bg-primary-100"
				>
					<span>{{ $this->getDisplayValue($index, $value) }}</span>
					<i class="fas fa-times-circle"></i>
				</button>
			@endforeach
		</div>
	@endif
</div>
