<div class="relative">
	<div
		class="absolute inset-0 text-center"
		style="top: 50% !important;"
		wire:loading
	>
		<i class="fas fa-spinner fa-spin fa-3x text-gray-900"></i>
	</div>
	<div
		class="mx-auto overflow-hidden rounded bg-white shadow-md"
		wire:loading.attr.class="pointer-events-none opacity-50"
	>
		<div class="px-8 pt-8 pb-3">
			@includeIf($beforeTableSlot)
			@if ($heading)
				<div class="flex flex-col space-y-2 pb-8 text-lg">
					<div>{{ $heading }}</div>
					@if ($description)
						<div class="text-sm">{{ $description }}</div>
					@endif
				</div>
			@endif
			<div class="flex flex-row pb-5 xl:hidden xl:pb-0">
				<div class="flex-grow">
					<x-assetsheld::forms.icon-input
						icon="fas fa-search"
						wire:model.debounce.500ms="search"
						placeholder="{{ __('Search by') }} {{ $this->searchableColumns()->map->label->join(', ') }}"
					/>
				</div>
				<div class="flex flex-shrink items-center space-x-3 pl-6 lg:hidden">
					@include('datatables::button-group', [
						'buttonsSlot' => $buttonsSlot,
						'exportable' => $exportable,
						'createRoute' => $this->createRoute,
						'columns' => $this->columns,
						'hasFilterableColumns' => $this->hasFilterableColumns,
						'shouldOpenFilters' => $this->shouldOpenFilters,
					])
				</div>
			</div>
			<div class="flex w-full flex-row items-center justify-between xl:divide-x">
				@if ($this->searchableColumns()->count())
					<div class="mr-6 hidden flex-grow xl:block">
						<x-assetsheld::forms.icon-input
							icon="fas fa-search"
							wire:model.debounce.500ms="search"
							placeholder="{{ __('Search by') }} {{ $this->searchableColumns()->map->label->join(', ') }}"
						/>
					</div>
				@endif

				<div class="flex h-10 flex-shrink items-center space-x-1 py-3 leading-10 lg:py-0 xl:px-6">
					<p class="whitespace-nowrap">
						@if (!$this->results->isEmpty())
							{{ "Viewing {$this->results->firstItem()} - {$this->results->lastItem()} of {$this->results->total()}" }}
						@else
							No results
						@endif
					</p>
					<x-assetsheld::ui.icon-button
						class="border-none shadow-none disabled:!bg-white disabled:text-gray-400"
						icon="fas fa-chevron-left"
						wire:click="gotoPage({{ $this->results->currentPage() - 1 }})"
						:disabled="$this->results->onFirstPage()"
						loading=""
					/>
					<x-assetsheld::ui.icon-button
						loading=""
						class="border-none shadow-none disabled:!bg-white disabled:text-gray-400"
						icon="fas fa-chevron-right"
						wire:click="gotoPage({{ $this->results->currentPage() + 1 }})"
						:disabled="!$this->results->hasMorePages()"
					/>
				</div>

				<div class="flex-inline flex flex-shrink items-center space-x-3 pl-6 lg:py-0 lg:pr-6">
					<div class="whitespace-nowrap">Per Page</div>
					<x-assetsheld::forms.select
						wire:model="perPage"
						class="!w-20 border-none shadow-none outline-none"
					>
						@foreach ([10, 25, 50] as $option)
							<option value="{{ $option }}">{{ $option }}</option>
						@endforeach
					</x-assetsheld::forms.select>
				</div>

				<div class="hidden flex-shrink items-center space-x-3 pl-6 lg:flex lg:py-0">
					@include('datatables::button-group', [
						'buttonsSlot' => $buttonsSlot,
						'exportable' => $exportable,
						'createRoute' => $this->createRoute,
						'columns' => $this->columns,
						'hasFilterableColumns' => $this->hasFilterableColumns,
						'shouldOpenFilters' => $this->shouldOpenFilters,
					])
				</div>
			</div>
		</div>

		<x-assetsheld::tables.table>
			<thead>
				<x-assetsheld::tables.row>
					@foreach ($this->columns as $index => $column)
						@if (!$column['hidden'])
							<x-assetsheld::tables.header :alignment="$column['headerAlign'] ?? 'left'">
								@if ($column['type'] === 'checkbox')
									<x-assetsheld::forms.checkbox
										wire:click="toggleSelectAll()"
										:checked="count($selected) === $this->results->total()"
									/>
								@else
									@if ($column['sortable'])
										<button wire:click="sort({{ $index }})">
											<span>{{ $column['label'] }}</span>
											@if ($sort === $index)
												@if ($direction)
													<i class="fas fa-caret-up ml-2"></i>
												@else
													<i class="fas fa-caret-down ml-2"></i>
												@endif
											@endif
										</button>
									@else
										{{ $column['label'] }}
									@endif
								@endif
							</x-assetsheld::tables.header>
						@endif
					@endforeach
				</x-assetsheld::tables.row>
				@if ($this->shouldOpenFilters)
					<x-assetsheld::tables.row>
						@foreach ($this->columns as $index => $column)
							@if (!$column['hidden'])
								<x-assetsheld::tables.header class="bg-gray-50">
									@isset($column['filterable'])
										@if (is_iterable($column['filterable']))
											<div wire:key="{{ $index }}">
												@include('datatables::filters.select', [
													'index' => $index,
													'name' => $column['label'],
													'options' => $this->getSelectOptionsToChooseFrom($index),
												])
											</div>
										@endif
									@endisset
								</x-assetsheld::tables.header>
							@endif
						@endforeach
					</x-assetsheld::tables.row>
				@endif
			</thead>
			<x-assetsheld::tables.body>
				@foreach ($this->results as $row)
					<x-assetsheld::tables.row>
						@foreach ($this->columns as $column)
							@if (!$column['hidden'])
								<x-assetsheld::tables.column :alignment="$column['headerAlign']">
									@if (!$column['hidden'])
										@if ($column['type'] === 'checkbox')
											<x-assetsheld::forms.checkbox
												wire:model="selected"
												value="{{ $row->checkbox_attribute }}"
												:checked="property_exists($this, 'pinnedRecords') && in_array($value, $this->pinnedRecords)"
											/>
										@elseif($column['type'] === 'label')
											{{ $column['content'] }}
										@else
											{{ $row->{$column['name']} === null ? $column['default'] : $row->{$column['name']} }}
										@endif
									@endif
								</x-assetsheld::tables.column>
							@endif
						@endforeach
					</x-assetsheld::tables.row>
				@endforeach
			</x-assetsheld::tables.body>
			@if ($this->results->isEmpty())
				<tfoot>
					<x-assetsheld::tables.row>
						<x-assetsheld::tables.column
							class="text-center"
							colspan="100%"
						>
							No results were found.
						</x-assetsheld::tables.column>
					</x-assetsheld::tables.row>
				</tfoot>
			@endif
		</x-assetsheld::tables.table>
	</div>
</div>
