<div class="row mt-3">
    <div class="col-md-6">
        <label for="geography" class="form-label">Geography</label>
        <select id="geography" name="geography" class="form-select" wire:change="changeGeography"
            wire:model="selectedGeography">
            <option selected>Choose...</option>
            @foreach ($geographyOptions as $geo)
                <option value="{{ $geo->id }}">
                    {{ $geo->labelInEnglish }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label for="c2rselectsx" class="form-label">Select </label>
        <select id="c2rselectsx" name="c2rselect" class="form-select">
            <option>Select {{ $changeTitle }}</option>
            @foreach ($filteredOptions as $option)
                <option value="{{ $option->id }}" {{ $c2rselectId == $option->id ? 'selected' : '' }}>
                    {{ $option->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>
