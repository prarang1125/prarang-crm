@props(['modalId', 'title', 'data', 'labelKey', 'valueKey', 'model', 'selectAllModel', 'groupBy' => null, 'colorKey' => null])

<div wire:ignore.self class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    @if($groupBy)
                    @foreach ($data as $group => $items)
                    <div class="col-12 fw-bold text-muted mt-2">{{ $group }}</div>
                    @foreach ($items as $item)
                    <div class="col-md-12">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input"
                                wire:model="{{ $model }}"
                                id="{{ $modalId }}_{{ $item->$valueKey }}"
                                value="{{ $item->$valueKey }}">
                            <label class="form-check-label" for="{{ $modalId }}_{{ $item->$valueKey }}"
                                @if($colorKey) style="background-color: {{ $item->$colorKey }}" @endif>
                                {{ $item->$labelKey }}
                            </label>
                        </div>
                    </div>
                    @endforeach

                    @endforeach
                    @else
                    @foreach ($data as $item)
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input"
                            wire:model="{{ $model }}"
                            id="{{ $modalId }}_{{ $item->$valueKey }}"
                            value="{{ $item->$valueKey }}">
                        <label class="form-check-label" for="{{ $modalId }}_{{ $item->$valueKey }}">
                        @if($colorKey) <div class="srre border shadow" style="background-color:  {{ $item->$colorKey }}"></div>@endif
                            {{ $item->$labelKey }}
                        </label>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <div>
                    <!-- <div class="form-check">
                        <input type="checkbox" class="form-check-input" wire:model="{{ $selectAllModel }}"
                            id="selectAll_{{ $modalId }}">
                        <label class="form-check-label" for="selectAll_{{ $modalId }}">Select All</label>
                    </div> -->

                    <button type="button" class="btn btn-sm btn-secondary"  wire:click="$set('{{ $model }}', [])">
                        Clear All
                    </button>

                </div>
                <button class="btn btn-secondary" data-bs-dismiss="modal" wire:click="ok">Ok</button>
            </div>
        </div>
    </div>
</div>
