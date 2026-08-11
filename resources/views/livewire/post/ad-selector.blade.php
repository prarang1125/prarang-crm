<div>
    <button type="button" class="btn btn-primary" wire:click="openModal">
        Select Ads
    </button>

    @if ($showModal)
    <div class="modal fade show" style="display:block; background:rgba(0,0,0,.5);" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chitti #{{ $chittiId }} ke liye Ads Select Karein</h5>
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>

                <div class="modal-body">
                    @if (session()->has('ad_selector_success'))
                    <div class="alert alert-success">
                        {{ session('ad_selector_success') }}
                    </div>
                    @endif

                    @error('selectedAds')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    @foreach ([1, 2, 3, 4] as $position)
                    <div class="mb-3">
                        <label class="form-label">Ad Position {{ $position }}</label>
                        <select class="form-select" wire:model="selectedAds.{{ $position }}">
                            <option value="">-- Select Ad --</option>
                            @foreach ($ads as $ad)
                            <option value="{{ $ad['id'] }}">{{ $ad['ad_title'] }}</option>
                            @endforeach
                        </select>
                        @error("selectedAds.$position")
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    @endforeach

                    <small class="text-muted d-block mt-2">
                        Aap 1 se 4 tak kisi bhi select box ko choose kar sakte hain, aur ek hi ad ko multiple positions
                        par bhi select kar sakte hain.
                    </small>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal">Cancel</button>
                    <button type="button" class="btn btn-success" wire:click="save" wire:loading.attr="disabled"
                        wire:target="save">
                        <span wire:loading.remove wire:target="save">Save</span>
                        <span wire:loading wire:target="save">Saving...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
