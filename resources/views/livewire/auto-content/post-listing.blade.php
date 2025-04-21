<div class="mb-4">
    <style>
        .modal-footer label {
            font-weight: 700;
            font-size: 18px;
        }

        .modal-footer .form-check-input {
            margin-top: 7px;
        }

        /* Column 4/12 */
        .bg-white .col-md-4 {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        /* Button */
        .bg-white .btn-secondary {
            margin-bottom: 4px;
        }

        .modal-dialog .modal-body {
            padding-left: 27px;
        }

        /* Srre */
        #emotionModal .modal-body .srre {
            width: 20px;
            height: 20px;

            margin-right: 9px;
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        /* Form check label */
        #emotionModal .modal-body .form-check-label {
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }

        /* Form check */
        #emotionModal .modal-body .form-check {
            flex: 0 0 auto;
            align-self: auto;
        }

        /* Button */
        .bg-white .btn-outline-primary {
            margin-bottom: 8px;
            width: 211px !important;
        }

        /* Modal body */
        #tagModal .modal-dialog .modal-body {
            padding-left: 13px;
            padding-top: 0px;
        }

        /* Column 12/12 */
        #tagModal .modal-body .text-muted {
            color: #1962a3 !important;
        }

        /* Form check label */
        #professionModal .modal-body .form-check-label {
            border-style: none;
            text-decoration: none;
            border-top-width: 0px;
            border-top-style: solid;
        }

        /* Row */
        .mb-4 form {
            align-items: normal;
            justify-content: center;
            /* transform:translatex(0px) translatey(0px); */
        }

        /* Column 4/12 */
        .bg-white .col-md-4 {
            padding-top: 8px;
        }

        /* Small Tag */
        .bg-white .row small {
            margin-bottom: 4px;
        }
          /* Selectable card style */
          .selectable-card {
                        cursor: pointer;
                    }

                    .selectable-input:checked + .selectable-body {
                        background-color: #dee2e6; /* Bootstrap's light gray */
                        border: 2px solid #6c757d;
                    }

                    /* Smooth transition */
                    .selectable-body {
                        transition: background-color 0.3s, border 0.3s;
                    }
    </style>

    <section class="p-4 shadow rounded bg-white m-3 mt-4">
        <p class="text-center h5">Post's Filter</p>
        <small>Required</small>
        <form class="row g-3" wire:submit.prevent="submit">

            <!-- Geography + Dates -->
            <div class="col-md-8">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label for="city" class="form-label fw-semibold">Geography <span
                                class="text-danger">*</span></label>
                        <select id="city" wire:model="city" class="form-select">
                            <option value="">Select Geography</option>
                            @foreach ($cities as $cityData)
                                <option value="{{ $cityData->cityId }}">{{ $cityData->citynameInEnglish }}</option>
                            @endforeach
                        </select>
                        @error('city')
                            <span class="text-danger small muted">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="startDate" class="form-label fw-semibold">Start Date <span
                                class="text-danger">*</span></label>
                        <input type="date" id="startDate" wire:model="startDate" class="form-control">
                        @error('startDate')
                            <span class="text-danger small muted">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="endDate" class="form-label fw-semibold">End Date <span
                                class="text-danger">*</span></label>
                        <input type="date" id="endDate" wire:model="endDate" class="form-control">
                        @error('endDate')
                            <span class="text-danger small muted">{{ $message }}</span>
                        @enderror
                    </div>
                    {{-- <div class="col-md-6">
                        <label for="intentType" class="form-label fw-semibold">Intent Type</label>
                        <select id="intentType" wire:model="intentType" class="form-select" disabled>
                            <option value="">All Intent Types</option>
                            <option value="Informative">🤔 Informative</option>
                            <option value="Persuasive">💬 Persuasive</option>
                            <option value="Entertaining">😂 Entertaining</option>
                        </select>
                    </div> --}}
                    <div class="col-md-6">
                        <label for="postType" class="form-label fw-semibold">Post Type</label>
                        <select id="postType" wire:model="forAbout" class="form-select">
                            <option value="">For/About Geography</option>
                            <option value="0">About The City</option>
                            <option value="1">For The City</option>
                        </select>
                    </div>

                    <!-- View Count Filter -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">View Count Filter</label>
                        <div class="input-group">
                            <input type="text" class="form-control" disabled value="Views is"
                                style="max-width: 100px;">
                            <select wire:model="comparator" class="form-select" style="max-width: 180px;">
                                <option value="0">Lower Then</option>
                                <option value="1">Greter Then</option>
                            </select>
                            <input type="number" wire:model="value" class="form-control"
                                placeholder="Enter View Count">
                        </div>
                    </div>

                </div>
            </div>

            <!-- Tag Modal Trigger -->
            <div class="col-md-4">
                <small>Smart Filter</small>
                <button type="button" class="btn btn-outline-primary w-75" data-bs-toggle="modal"
                    data-bs-target="#tagModal">
                    Select Tags @if (count($selectedTags) == 0)
                    @else
                        <span class="badge bg-danger">{{ count($selectedTags) }}</span>
                    @endif
                </button>
                <button type="button" class="btn btn-outline-primary w-75" data-bs-toggle="modal"
                    data-bs-target="#professionModal">
                    Select Professions @if (count($selectedProfessions) == 0)
                    @else
                        <span class="badge bg-danger">{{ count($selectedProfessions) }}</span>
                    @endif
                </button>
                <button type="button" class="btn btn-outline-primary w-75" data-bs-toggle="modal"
                    data-bs-target="#educationModal">
                    Select Education @if (count($selectedEducations) == 0)
                    @else
                        <span class="badge bg-danger">{{ count($selectedEducations) }}</span>
                    @endif
                </button>
                <button type="button" class="btn btn-outline-primary w-75" data-bs-toggle="modal"
                    data-bs-target="#emotionModal">
                    Select Emotions @if (count($selectedEmotions) == 0)
                    @else
                        <span class="badge bg-danger">{{ count($selectedEmotions) }}</span>
                    @endif
                </button>
            </div>


            <!-- Intent Type -->


            <!-- Filter Button -->
            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary px-4" wire:loading.attr="disabled" onclick="countDown();">

                    <span wire:loading wire:target="submit">
                        <div class="spinner-border text-light spinner-border-sm" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </span> 🔍 Filter
                </button>
                <p class="text-end">
                    <span id="countdown"></span>
                    @if ($loadTimeInSeconds > 0)
                        {{ $loadTimeInSeconds }} seconds
                    @endif
                </p>
            </div>
        </form>
    </section>

    <section class="p-4 shadow rounded bg-white m-3 mt-4">
        <p class="text-center h5">{{ $posts->total() }} Posts Found.</p>
        <div class="row g-3">
            @if (count($posts) > 0)
                @foreach ($posts as $post)

                <div class="col-md-4">
                    <label class="selectable-card">
                        <input type="checkbox" wire:model="selectedPosts" value="{{ $post->id }}" class="d-none selectable-input">
                        <div class="card mb-3 shadow-sm selectable-body">
                            <img src="{{ $post->image }}" class="card-img-top img-fluid" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">{{ $post->Title }}</h5>
                            </div>
                        </div>
                    </label>
                </div>




                @endforeach
                <div class="col-md-12">
                    <div class="d-flex justify-content-center">
                        {{ $posts->links() }}
                    </div>
                </div>

            @else
                <div class="col-md-12">
                    <div class="alert alert-info text-center" role="alert">
                        No posts found for the selected filters.
                    </div>
                </div>
            @endif
        </div>

        {{-- Tag Modal --}}
        <x-multiselect-modal modalId="tagModal" title="Select Tags" :data="$tags" groupBy="catg"
            labelKey="tagName" valueKey="tagId" model="selectedTags" selectAllModel="selectAllTags" />

        {{-- Profession Modal --}}
        <x-multiselect-modal modalId="professionModal" title="Select Professions" :data="$professionArr"
            labelKey="profession" valueKey="professioncode" model="selectedProfessions"
            selectAllModel="selectAllProfessions" />

        {{-- Education Modal --}}
        <x-multiselect-modal modalId="educationModal" title="Select Education" :data="$educationArr"
            labelKey="subjectname" valueKey="subjectcode" model="selectedEducations"
            selectAllModel="selectAllEducations" />
        {{-- Emotion Modal --}}
        <x-multiselect-modal modalId="emotionModal" title="Select Emotions" :data="$emotionArr" labelKey="name"
            valueKey="id" model="selectedEmotions" selectAllModel="selectAllEmotions" :colorKey="'colorcode'" />


</div>
