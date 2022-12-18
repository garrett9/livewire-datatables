<div>
	@includeIf($beforeTableSlot)
	<div class="relative">
		<div class="mb-1 flex items-center justify-between">
			<div class="flex flex-wrap items-center space-x-1">
				<x-icons.cog
					wire:loading
					class="h-9 w-9 animate-spin text-gray-400"
				/>

				@if ($this->activeFilters)
					<button
						wire:click="clearAllFilters"
						class="flex items-center space-x-2 rounded-md border border-red-400 bg-white px-3 text-xs font-medium uppercase leading-4 tracking-wider text-red-500 hover:bg-red-200 focus:outline-none"
					><span>{{ __('Reset') }}</span>
						<x-icons.x-circle class="m-2" />
					</button>
				@endif

				@if (count($this->massActionsOptions))
					<div class="flex items-center justify-center space-x-1">
						<label for="datatables_mass_actions">{{ __('With selected') }}:</label>
						<select
							wire:model="massActionOption"
							class="space-x-2 rounded-md border border-green-400 bg-white px-3 text-xs font-medium uppercase leading-4 tracking-wider focus:outline-none"
							id="datatables_mass_actions"
						>
							<option value="">{{ __('Choose...') }}</option>
							@foreach ($this->massActionsOptions as $group => $items)
								@if (!$group)
									@foreach ($items as $item)
										<option value="{{ $item['value'] }}">{{ $item['label'] }}</option>
									@endforeach
								@else
									<optgroup label="{{ $group }}">
										@foreach ($items as $item)
											<option value="{{ $item['value'] }}">{{ $item['label'] }}</option>
										@endforeach
									</optgroup>
								@endif
							@endforeach
						</select>
						<button
							wire:click="massActionOptionHandler"
							class="flex items-center rounded-md border border-green-400 bg-white px-4 py-2 text-xs font-medium uppercase leading-4 tracking-wider text-green-500 hover:bg-green-200 focus:outline-none"
							type="submit"
							title="Submit"
						>Go</button>
					</div>
				@endif


				@if ($hideable === 'select')
					@include('datatables::hide-column-multiselect')
				@endif

			</div>
		</div>

		<div
			wire:loading.class="opacity-50"
			class="@unless($complex || $this->hidePagination) rounded-b-none @endunless max-w-screen @if ($this->activeFilters) border-blue-500 @else border-transparent @endif @if ($complex) rounded-b-none border-b-0 @endif overflow-x-scroll rounded-lg border-2 bg-white shadow-lg"
		>
			<div>
				<div class="table min-w-full align-middle">
					@unless($this->hideHeader)
						<div class="table-row divide-x divide-gray-200">
							@foreach ($this->columns as $index => $column)
								@if ($hideable === 'inline')
									@include('datatables::header-inline-hide', ['column' => $column, 'sort' => $sort])
								@elseif($column['type'] === 'checkbox')
									@unless($column['hidden'])
										<div
											class="flex table-cell h-12 w-32 justify-center overflow-hidden border-b border-gray-200 bg-gray-50 px-6 py-4 text-left align-top text-xs font-medium uppercase leading-4 tracking-wider text-gray-500 focus:outline-none"
										>
											<div
												class="@if (count($selected)) bg-orange-400 @else bg-gray-200 @endif rounded px-3 py-1 text-center text-white"
											>
												{{ count($selected) }}
											</div>
										</div>
									@endunless
								@else
									@include('datatables::header-no-hide', ['column' => $column, 'sort' => $sort])
								@endif
							@endforeach
						</div>
					@endunless
					<div class="table-row divide-x divide-blue-200 bg-blue-100">
						@foreach ($this->columns as $index => $column)
							@if ($column['hidden'])
								@if ($hideable === 'inline')
									<div class="table-cell w-5 overflow-hidden bg-blue-100 align-top"></div>
								@endif
							@elseif($column['type'] === 'checkbox')
								@include('datatables::filters.checkbox')
							@elseif($column['type'] === 'label')
								<div class="table-cell overflow-hidden align-top">
									{{ $column['label'] ?? '' }}
								</div>
							@else
								<div class="table-cell overflow-hidden align-top">
									@isset($column['filterable'])
										@if (is_iterable($column['filterable']))
											<div wire:key="{{ $index }}">
												@include('datatables::filters.select', [
												    'index' => $index,
												    'name' => $column['label'],
												    'options' => $column['filterable'],
												])
											</div>
										@else
											<div wire:key="{{ $index }}">
												@include('datatables::filters.' . ($column['filterView'] ?? $column['type']), [
												    'index' => $index,
												    'name' => $column['label'],
												])
											</div>
										@endif
									@endisset
								</div>
							@endif
						@endforeach
					</div>
					@foreach ($this->results as $row)
						<div class="{{ $this->rowClasses($row, $loop) }} table-row p-1">
							@foreach ($this->columns as $column)
								@if ($column['hidden'])
									@if ($hideable === 'inline')
										<div
											class="@unless($column['wrappable']) whitespace-nowrap truncate @endunless table-cell w-5 overflow-hidden align-top"
										></div>
									@endif
								@elseif($column['type'] === 'checkbox')
									@include('datatables::checkbox', ['value' => $row->checkbox_attribute])
								@elseif($column['type'] === 'label')
									@include('datatables::label')
								@else
									<div
										class="@unless($column['wrappable']) whitespace-nowrap truncate @endunless @if ($column['contentAlign'] === 'right') text-right @elseif($column['contentAlign'] === 'center') text-center @else text-left @endif {{ $this->cellClasses($row, $column) }} table-cell px-6 py-2"
									>
										{!! $row->{$column['name']} !!}
									</div>
								@endif
							@endforeach
						</div>
					@endforeach

					@if ($this->hasSummaryRow())
						<div class="table-row p-1">
							@foreach ($this->columns as $column)
								@unless($column['hidden'])
									@if ($column['summary'])
										<div
											class="@unless($column['wrappable']) whitespace-nowrap truncate @endunless @if ($column['contentAlign'] === 'right') text-right @elseif($column['contentAlign'] === 'center') text-center @else text-left @endif {{ $this->cellClasses($row, $column) }} table-cell px-6 py-2"
										>
											{{ $this->summarize($column['name']) }}
										</div>
									@else
										<div class="table-cell"></div>
									@endif
								@endunless
							@endforeach
						</div>
					@endif
				</div>
			</div>
			@if ($this->results->isEmpty())
				<p class="p-3 text-center text-lg">
					{{ __("There's Nothing to show at the moment") }}
				</p>
			@endif
		</div>

		@unless($this->hidePagination)
			<div
				class="max-w-screen @unless($complex) rounded-b-lg @endunless @if ($this->activeFilters) border-blue-500 @else border-transparent @endif border-4 border-t-0 border-b-0 bg-white"
			>
				<div class="items-center justify-between p-2 sm:flex">
					{{-- check if there is any data --}}
					@if (count($this->results))
						<div class="my-2 flex items-center sm:my-0">
							<select
								name="perPage"
								class="form-select focus:shadow-outline-blue mt-1 block w-full border-gray-300 py-2 pl-3 pr-10 text-base leading-6 focus:border-blue-300 focus:outline-none sm:text-sm sm:leading-5"
								wire:model="perPage"
							>
								@foreach (config('livewire-datatables.per_page_options', [10, 25, 50, 100]) as $per_page_option)
									<option value="{{ $per_page_option }}">{{ $per_page_option }}</option>
								@endforeach
								<option value="99999999">{{ __('All') }}</option>
							</select>
						</div>

						<div class="my-4 sm:my-0">
							<div class="lg:hidden">
								<span class="space-x-2">{{ $this->results->links('datatables::tailwind-simple-pagination') }}</span>
							</div>

							<div class="hidden justify-center lg:flex">
								<span>{{ $this->results->links('datatables::tailwind-pagination') }}</span>
							</div>
						</div>

						<div class="flex justify-end text-gray-600">
							{{ __('Results') }} {{ $this->results->firstItem() }} - {{ $this->results->lastItem() }}
							{{ __('of') }}
							{{ $this->results->total() }}
						</div>
					@endif
				</div>
			</div>
			@endif
		</div>

		@if ($complex)
			<div
				class="@if ($this->activeFilters) border-blue-500 @else border-transparent @endif @if ($complex) border-t-0 @endif rounded-b-lg rounded-t-none border-2 bg-gray-50 px-4 py-4 shadow-lg"
			>
				<livewire:complex-query
					:columns="$this->complexColumns"
					:persistKey="$this->persistKey"
					:savedQueries="method_exists($this, 'getSavedQueries') ? $this->getSavedQueries() : null"
				/>
			</div>
		@endif

		@includeIf($afterTableSlot)

		<span
			class="hidden bg-gray-100 bg-yellow-100 bg-gray-50 text-left text-center text-right text-sm leading-5 text-gray-900"
		></span>
	</div>
