<span class="group">
	<span class="flex items-center">{{ Str::limit($slot, $length) }}</span>
	<span
		class="absolute z-10 mt-2 hidden w-96 whitespace-pre-wrap rounded-lg border border-gray-300 bg-gray-100 p-2 text-left text-xs text-gray-700 shadow-xl group-hover:block"
	>{{ $slot }}</span>
</span>
