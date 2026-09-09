<div>
    <div class="mb-3">
        <label class="form-label">{{ $label }}</label>
        <textarea wire:model.live="pastedText" class="form-control" rows="4"
            placeholder="Paste data from Excel here...&#10;Format: [Category Key] [Tab] [Name] [Tab] [URL]"></textarea>
        {{-- <small class="text-muted d-block mt-1">Data will be automatically parsed. If the first column (key)
            repeats, it
            will group them together.</small> --}}
    </div>

    @if(!empty($parsedData))
    <div class="mt-3">
        <label class="form-label mb-2 fw-semibold text-dark">Parsed Data Preview</label>
        @foreach($parsedData as $key => $items)
        <div class="card mb-3 shadow-sm border" style="border-radius: 8px;">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-primary fw-bold text-capitalize">{{ str_replace('_', ' ', $key) }}</h6>
                <button type="button" wire:click="removeCategory('{{ $key }}')" class="btn btn-sm btn-outline-danger"
                    title="Clear Category">
                    <i class="bx bx-trash"></i> Clear
                </button>
            </div>
            <div class="card-body p-0">
                @if(is_array($items))
                <ul class="list-group list-group-flush rounded-bottom">
                    @foreach($items as $index => $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="text-break">
                            @if(is_array($item))
                            <span class="fw-medium text-dark">{{ $item['name'] ?? '' }}</span>
                            @if(!empty($item['url']))
                            <br>
                            <a href="{{ $item['url'] }}" target="_blank" class="text-decoration-none small text-muted">
                                <i class="bx bx-link"></i> {{ $item['url'] }}
                            </a>
                            @endif
                            @else
                            <span class="fw-medium text-dark">{{ $item }}</span>
                            @endif
                        </div>
                        <button type="button" wire:click="removeItem('{{ $key }}', {{ $index }})"
                            class="btn btn-sm btn-light text-danger rounded-circle shadow-sm"
                            style="width: 28px; height: 28px; padding: 0; line-height: 1;">
                            &times;
                        </button>
                    </li>
                    @endforeach
                </ul>
                @else
                <div class="p-3 d-flex justify-content-between align-items-center">
                    <span class="fw-medium text-dark">{{ $items }}</span>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- The JSON Output will be captured by the parent form via this hidden input -->
    <input type="hidden" name="{{ $inputName }}" value="{{ $jsonOutput }}">
</div>
