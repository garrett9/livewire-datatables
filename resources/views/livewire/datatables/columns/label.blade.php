<div class="flex flex-row items-center space-x-3">
	@if (!empty($pre))
		<div>{{ $pre }}</div>
	@endif
	@if (!empty($slot))
		<x-assetsheld::ui.label
			class="!text-xs"
			:status="$status"
		>
			{{ $slot }}
	@endif
	</x-assetsheld::ui.label>
	@if (!empty($post))
		<div>{{ $post }}</div>
	@endif
</div>
