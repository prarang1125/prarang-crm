<div class="row mt-3">
    <div class="col-md-6">
        <label for="geography" class="form-label">Geography</label>
        <select id="geography" name="geography" class="form-select" wire:change="changeGeography"
            wire:model="selectedGeography">
            <option>Select Geography</option>
            <option value="1">Country</option>
            <option value="2">City</option>
            <option value="3">Region</option>
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
