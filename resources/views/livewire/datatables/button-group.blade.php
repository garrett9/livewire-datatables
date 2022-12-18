@includeIf($buttonsSlot)
@if ($hasFilterableColumns)
	<x-assetsheld::ui.icon-button
		icon="fas fa-filter"
		:color="$shouldOpenFilters ? 'primary' : 'secondary'"
		wire:click="toggleFilters()"
	/>
@endif
@if (collect($columns)->some(function ($column) {
    return $column['hideable'];
}))
	<x-assetsheld::dropdown.container icon="fas fa-cog">
		<x-slot name="actions">
			@foreach ($columns as $index => $column)
				@if ($column['hideable'])
					<x-assetsheld::dropdown.action>
						<x-assetsheld::forms.checkbox
							wire:click="toggle({{ $index }})"
							:checked="!$column['hidden']"
						><span class="whitespace-nowrap">{{ $column['label'] }}</span>
						</x-assetsheld::forms.checkbox>
					</x-assetsheld::dropdown.action>
				@endif
			@endforeach
		</x-slot>
	</x-assetsheld::dropdown.container>
@endif

@if ($exportable)
	<x-assetsheld::ui.icon-button
		icon="fas fa-download"
		wire:click="export"
		x-data="{
    init() {
        window.livewire.on('startDownload', link => window.open(link, '_blank'));
    }
}"
	/>
@endif

@if ($createRoute)
	<x-assetsheld::ui.icon-button
		color="primary"
		icon="fas fa-plus"
		href="{{ route($createRoute) }}"
	/>
@endif
