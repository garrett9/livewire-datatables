<x-assetsheld::dropdown.container
	icon="fas fa-ellipsis-v"
	class="border-none text-left shadow-none"
>
	<x-slot name="actions">
		@if ($editRoute ?? false)
			<x-assetsheld::dropdown.action
				:href="route($editRoute, $value)"
				icon="fas fa-edit"
			>
				Edit
			</x-assetsheld::dropdown.action>
		@endif
		@if ($deleteModal)
			<x-assetsheld::dropdown.action
				icon="fas fa-trash"
				wire:click="$emit('openModal', '{{ $deleteModal }}', {{ json_encode([$value]) }})"
			>
				Delete
			</x-assetsheld::dropdown.action>
		@endif
	</x-slot>
</x-assetsheld::dropdown.container>
