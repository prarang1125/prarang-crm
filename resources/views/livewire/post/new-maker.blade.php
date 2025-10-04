<div>
<style>
    /* Accordion button */
    #flush-headingOne .accordion-button {
        background-color: #cdcdd9;
        transform: translatex(0px) translatey(0px);
        font-weight: 700;
    }

    /* Accordion button (active) */
    #flush-headingOne .accordion-button:active {
        background-color: #dddde2;
    }

    /* Form label */
    .accordion-body .form-label {
        font-weight: 700;
    }
    /* Accordion button */
#flush-headingOne .accordion-button{
 transform:translatex(0px) translatey(0px);
 color:#000000;
}

/* Accordion button */
#flush-headingOne .accordion-button{
 margin-bottom:31px;
}
/* Row */
#flush-collapseOne .accordion-body .row{
 padding-bottom:12px;
 background-color:rgba(0,0,0,0.14);
 padding-left:3px;
 transform:translatex(0px) translatey(0px);
}

/* Accordion button */
#flush-headingOne .accordion-button{
 margin-bottom:0px !important;
 transform:translatex(0px) translatey(0px);
 background-color:#dbdbdb !important;
}

/* Accordion body */
#flush-collapseOne .accordion-body{
 padding-left:8px;
 padding-right:9px;
}
</style>
<div class="accordion accordion-flush" id="accordionFlushExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingOne">
                                <button class="accordion-button collapsed @error('summary') bg-danger @enderror @error('intent') bg-danger @enderror" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                    Intent / Summary <small> @error('intent')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror @error('summary')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror</small>
                                </button>
                            </h2>
                            <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne"
                                data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    <div class="row g-3">
                                        <!-- Intent Textarea -->
                                        <div class="col-md-6">
                                            <label for="intent" class="form-label">Intent</label>
                                            <textarea
                                                class="form-control @error('intent') is-invalid @enderror"
                                                name="intent"
                                                id="intent"
                                                rows="4">{{ old('intent')?? $intent }}</textarea>
                                            @error('intent')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Summary Textarea -->
                                        <div class="col-md-6">
                                            <label for="summary"  class="form-label">Summary</label>
                                            <textarea
                                                class="form-control @error('summary') is-invalid @enderror"
                                                name="summary"
                                                id="summary"
                                                rows="4">{{ old('summary') ?? $summary }}</textarea>
                                            @error('summary')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="intent_type" wire:model.defer="intent_type" class="form-label">Intent Type</label>
                                            <select class="form-select @error('intent_type') is-invalid @enderror" name="intent_type" id="intent_type">
                                                <option value="">Select Intent Type</option>
                                                <option value="1" {{ old('intent_type')?? $intent_type == '1' ? 'selected' : '' }}>For City</option>
                                                <option value="2" {{ old('intent_type')??  $intent_type == '2' ? 'selected' : '' }}>About City</option>
                                                <option value="3" {{ old('intent_type')??  $intent_type == '3' ? 'selected' : '' }}>For World</option>
                                                <option value="4" {{ old('intent_type')??  $intent_type == '4' ? 'selected' : '' }}>Matching Generated</option>
                                            </select>
                                            @error('intent_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
</div>
