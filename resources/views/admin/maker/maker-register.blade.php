@extends('layouts.admin.admin')
@section('title', 'New Maker Register')

@section('content')
    <!--start page wrapper -->
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Admin</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('admin/maker/maker-listing') }}"><i
                                    class="bx bx-user"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Maker Register</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="card" style="padding-top: 15px;">
                <div class="col-xl-9 mx-auto w-100">
                    <!-- Success Message -->
                    @if (session('success'))
                        <div class="alert alert-success mt-3">
                            {{ session('success') }}
                        </div>
                    @endif
                    <h6 class="mb-0 text-uppercase text-primary">Create New Maker Register</h6>
                    <hr />
                    <form action="{{ url('/admin/maker/maker-store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="content" class="form-label">Content</label>
                                <textarea class="@error('content') is-invalid @enderror" name="content" id="editor">{{ old('text') }}</textarea>
                                @error('content')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Image Preview and Thumbnails -->
                            <div class="col-md-4">
                                <label>Image Preview</label>
                                <div class="image-preview-mt" id="image-preview"
                                    style="max-width: 300px; max-height: 300px; overflow: hidden; border: 1px solid #ccc; padding: 5px;">
                                    <img id="preview-img" src="/img/blankImage2.png" alt="Image Preview"
                                        style="width: 288px; height: 250px; background-size: cover;" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label>Thumbnails</label>
                                <div id="thumbnails" class="d-flex flex-wrap"
                                    style="gap: 10px; border: 1px solid #ccc; padding: 5px; min-height: 80px; background-color: #28252517;">
                                    <div class="thumbnail-slot" id="slot-1"
                                        style="background-image: url('/img/blankImage2.png'); background-size: cover; width:100%; height:100px;position:relative;">
                                        <input type="checkbox" class="thumbnail-checkbox" id="checkbox-1"
                                            style="position: absolute; top: 2px; left: 2px; display: none;">
                                    </div>
                                    <div class="thumbnail-slot" id="slot-2"
                                        style="background-image: url('/img/blankImage2.png'); background-size: cover; width:100%; height:100px;position:relative;">
                                        <input type="checkbox" class="thumbnail-checkbox" id="checkbox-2"
                                            style="position: absolute; top: 2px; left: 2px; display: none;">
                                    </div>
                                    <div class="thumbnail-slot" id="slot-3"
                                        style="background-image: url('/img/blankImage2.png'); background-size: cover; width:100%; height:100px;position:relative;">
                                        <input type="checkbox" class="thumbnail-checkbox" id="checkbox-3"
                                            style="position: absolute; top: 2px; left: 2px; display: none;">
                                    </div>
                                    <div class="thumbnail-slot" id="slot-4"
                                        style="background-image: url('/img/blankImage2.png'); background-size: cover; width:100%; height:100px;position:relative;">
                                        <input type="checkbox" class="thumbnail-checkbox" id="checkbox-4"
                                            style="position: absolute; top: 2px; left: 2px; display: none;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label for="makerImage" class="form-label">Upload Image</label>
                                <input type="file" class="form-control @error('makerImage') is-invalid @enderror"
                                    id="makerImage" name="makerImage" onchange="previewImage()">
                                @error('makerImage')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        @livewire('post.geography-selector')

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control  @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title') }}">
                                @error('title')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="subtitle" class="form-label">Sub Title</label>
                                <input type="text" class="form-control  @error('subtitle') is-invalid @enderror"
                                    id="subtitle" name="subtitle" value="{{ old('subtitle') }}">
                                @error('subtitle')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="row align-items-center">
                                <div class="col-md-4 mt-2">
                                    <label class="form-label">Select</label>
                                </div>
                                <div class="col-md-8 forcity">
                                    <div class="form-check me-3">
                                        <input class="form-check-input @error('forTheCity') is-invalid @enderror"
                                            type="radio" name="forTheCity" id="forTheCityYes" value="1"
                                            {{ old('forTheCity') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="forTheCityYes">For the city</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input @error('forTheCity') is-invalid @enderror"
                                            type="radio" name="forTheCity" id="aboutTheCity" value="0"
                                            {{ old('forTheCity') == '0' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="aboutTheCity">About the City</label>
                                    </div>
                                    <!-- Display the error message for the radio group -->
                                    @error('forTheCity')
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input @error('isCultureNature') is-invalid @enderror"
                                        type="radio" name="isCultureNature" id="cultureNatureYes" value="1"
                                        {{ old('isCultureNature') == '1' ? 'checked' : 'checked' }}>
                                    <!-- Default checked -->
                                    <label class="form-check-label" for="cultureNatureYes">Culture</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input @error('isCultureNature') is-invalid @enderror"
                                        type="radio" name="isCultureNature" id="cultureNatureNo" value="0"
                                        {{ old('isCultureNature') == '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="cultureNatureNo">Nature</label>
                                </div>
                                @error('isCultureNature')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>



                        <!-- Tab structure to display based on radio button selection -->
                        <div id="cultureNatureTabs" style="display: none;">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <!-- Culture tabs -->
                                <li class="nav-item">
                                    <a class="nav-link active" id="culture-tab1" data-bs-toggle="tab"
                                        href="#cultureTab1" role="tab"
                                        style="background-color: #ff0006;color: white;">Timelines</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="culture-tab2" data-bs-toggle="tab" href="#cultureTab2"
                                        role="tab" style="background-color: #ffff18;color: #282828;">Man And his
                                        Senses</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="culture-tab3" data-bs-toggle="tab" href="#cultureTab3"
                                        role="tab" style="background-color: #1919d9;color: white;">Man and his
                                        Inventions</a>
                                </li>

                                <!-- Nature tabs -->
                                <li class="nav-item">
                                    <a class="nav-link" id="nature-tab1" data-bs-toggle="tab" href="#natureTab1"
                                        role="tab"
                                        style="display:none; background-color: #faff98;color: #282828;">Geography</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="nature-tab2" data-bs-toggle="tab" href="#natureTab2"
                                        role="tab"
                                        style="display:none; background-color: #c8ff00;color: #282828;">Fauna</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="nature-tab3" data-bs-toggle="tab" href="#natureTab3"
                                        role="tab"
                                        style="display:none; background-color: #339933;color: #fff;">Flora</a>
                                </li>
                            </ul>
                            @error('tagId')
                                <p class="invalid-feedback" style="color: red; font-size: 0.875em;">{{ $message }}</p>
                            @enderror
                            <div class="tab-content">
                                <!-- Content for Culture Tab 1 (Timelines) -->
                                <div class="tab-pane fade show active" id="cultureTab1" role="tabpanel">
                                    <div class="row">
                                        @foreach ($timelines as $timeline)
                                            <div class="col-md-4">
                                                <div class="card">
                                                    <div class="card-body cardbodselect mt-3"
                                                        style="background-color: #ff0006; color: white;">
                                                        <div class="d-flex align-items-center">
                                                            <input type="radio" name="tagId"
                                                                value="{{ $timeline->tagId }}"
                                                                id="timeline{{ $timeline->id }}" class="me-2">
                                                            <label for="timeline{{ $timeline->id }}"
                                                                class="mb-0">{{ $timeline->tagInEnglish }}</label>
                                                            <i class="lni lni-close ms-auto"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <!-- Content for Man And His Senses -->
                                <div class="tab-pane fade" id="cultureTab2" role="tabpanel">
                                    <div class="row">
                                        @foreach ($manSenses as $sense)
                                            <div class="col-md-4">
                                                <div class="card">
                                                    <div class="card-body cardbodselect mt-3"
                                                        style="background-color: #ffff18; color: #282828;">
                                                        <div class="d-flex align-items-center">
                                                            <input type="radio" name="tagId"
                                                                value="{{ $sense->tagId }}"
                                                                id="sense{{ $sense->id }}" class="me-2">
                                                            <label for="sense{{ $sense->id }}"
                                                                class="mb-0">{{ $sense->tagInEnglish }}</label>
                                                            <i class="lni lni-close ms-auto"></i>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Content for Man And His Inventions -->
                                <div class="tab-pane fade" id="cultureTab3" role="tabpanel">
                                    <div class="row">
                                        @foreach ($manInventions as $invention)
                                            <div class="col-md-4">
                                                <div class="card">
                                                    <div class="card-body cardbodselect mt-3"
                                                        style="background-color: #1919d9; color: white;">
                                                        <div class="d-flex align-items-center">
                                                            <input type="radio" name="tagId"
                                                                value="{{ $invention->tagId }}"
                                                                id="invention{{ $invention->id }}" class="me-2">
                                                            <label for="invention{{ $invention->id }}"
                                                                class="mb-0">{{ $invention->tagInEnglish }}</label>
                                                            <i class="lni lni-close ms-auto"></i>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Content for Nature (Geography, Flora, etc.) -->
                                <div class="tab-pane fade" id="natureTab1" role="tabpanel">
                                    <div class="row">
                                        @foreach ($geographys as $geography)
                                            <div class="col-md-4">
                                                <div class="card">
                                                    <div class="card-body cardbodselect mt-3"
                                                        style="background-color: #faff98; color: #282828;">
                                                        <div class="d-flex align-items-center">
                                                            <!-- Radio Input -->
                                                            <input type="radio" name="tagId"
                                                                value="{{ $geography->tagId }}"
                                                                id="geography{{ $geography->id }}" class="me-2">
                                                            <label for="geography{{ $geography->id }}"
                                                                class="mb-0">{{ $geography->tagInEnglish }}</label>
                                                            <i class="lni lni-close ms-auto"></i>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="natureTab2" role="tabpanel">
                                    <div class="row">
                                        @foreach ($faunas as $fauna)
                                            <div class="col-md-4">
                                                <div class="card">
                                                    <div class="card-body cardbodselect mt-3"
                                                        style="background-color: #c8ff00; color: #282828;">
                                                        <div class="d-flex align-items-center">
                                                            <!-- Radio Input -->
                                                            <input type="radio" name="tagId"
                                                                value="{{ $fauna->tagId }}"
                                                                id="fauna{{ $fauna->id }}" class="me-2">
                                                            <label for="fauna{{ $fauna->id }}"
                                                                class="mb-0">{{ $fauna->tagInEnglish }}</label>
                                                            <i class="lni lni-close ms-auto"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="natureTab3" role="tabpanel">
                                    <div class="row">
                                        @foreach ($floras as $flora)
                                            <div class="col-md-4">
                                                <div class="card">
                                                    <div class="card-body cardbodselect mt-3"
                                                        style="background-color: #339933; color: #fff;">
                                                        <div class="d-flex align-items-center">

                                                            <input type="radio" name="tagId"
                                                                value="{{ $flora->tagId }}"
                                                                id="flora{{ $flora->id }}" class="me-2">\
                                                            <label for="flora{{ $flora->id }}"
                                                                class="mb-0">{{ $flora->tagInEnglish }}</label>
                                                            <i class="lni lni-close ms-auto"></i>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="modal-footer mt-3">
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--end page wrapper -->
    <script>
        const uploadUrl = "{{ route('admin.ckeditor-upload') }}";
        const csrfToken = "{{ csrf_token() }}";
    </script>
    <script>
        //JavaScript for Image Preview and Thumbnails start
        const defaultImageSrc = '/img/blankImage2.png'; // Default main image URL
        const defaultThumbnailSrc = '/img/blankImage2.png'; // Default thumbnail background
        function previewImage() {
            const input = document.getElementById('makerImage');
            const previewImg = document.getElementById('preview-img');
            const files = input.files;

            // If no files are uploaded, reset the main image to the default image
            if (!files || files.length === 0) {
                previewImg.src = defaultImageSrc;
                return;
            }

            // Preview the uploaded image and set it to the main image
            if (files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result; // Set main image preview

                    // Find the first available thumbnail slot and set the image
                    setThumbnailImage(e.target.result);
                };
                reader.readAsDataURL(files[0]);
            }
        }

        let isFirstImage = true; // Flag to track if it's the first uploaded image
        function setThumbnailImage(imageSrc) {
            const thumbnails = document.getElementById('thumbnails').children;

            // Loop through the slots and find the first one that still has the default background image
            for (let i = 0; i < thumbnails.length; i++) {
                const slot = thumbnails[i];
                const checkbox = slot.querySelector('.thumbnail-checkbox');

                // Check if the current slot still has the default background
                if (slot.style.backgroundImage.includes(defaultThumbnailSrc)) {
                    // Replace background image with the uploaded image
                    slot.style.backgroundImage = `url(${imageSrc})`;

                    // Make the checkbox visible when the image is uploaded
                    checkbox.style.display = 'block';

                    // Only check the checkbox for the first uploaded image, leave others unchecked
                    if (isFirstImage) {
                        checkbox.checked = true;
                        isFirstImage = false; // Set flag to false after the first image is uploaded
                    } else {
                        checkbox.checked = false;
                    }

                    // Add delete button to remove the image and restore the default background
                    addDeleteButton(slot, checkbox);

                    // Add click event to set the image as the main preview
                    slot.onclick = function() {
                        document.getElementById('preview-img').src = imageSrc;
                    };

                    break;
                }
            }
        }

        function addDeleteButton(slot, checkbox) {
            // Create delete button if not already present
            if (!slot.querySelector('.delete-btn')) {
                const deleteBtn = document.createElement('span');
                deleteBtn.innerHTML = '&times;';
                deleteBtn.className = 'delete-btn';
                deleteBtn.style.position = 'absolute';
                deleteBtn.style.top = '2px';
                deleteBtn.style.right = '2px';
                deleteBtn.style.cursor = 'pointer';
                deleteBtn.style.background = 'red';
                deleteBtn.style.color = 'white';
                deleteBtn.style.fontSize = '12px';
                deleteBtn.style.padding = '2px 5px';
                deleteBtn.style.borderRadius = '50%';

                // On clicking the delete button, restore the default background and uncheck the checkbox
                deleteBtn.onclick = function() {
                    // Restore default background
                    slot.style.backgroundImage = `url(${defaultThumbnailSrc})`;

                    // Uncheck and hide the checkbox
                    checkbox.checked = false;
                    checkbox.style.display = 'none';

                    // Remove delete button
                    slot.removeChild(deleteBtn);

                    // Remove the click event for setting the main image
                    slot.onclick = null;

                    // Check if all thumbnails have the default background image
                    checkIfAllThumbnailsAreDefault();
                };

                // Append the delete button to the thumbnail slot
                slot.appendChild(deleteBtn);
            }
        }

        // Function to check if all thumbnails have the default background
        function checkIfAllThumbnailsAreDefault() {
            const thumbnails = document.getElementById('thumbnails').children;
            let allDefault = true;

            // Loop through all the thumbnail slots
            for (let i = 0; i < thumbnails.length; i++) {
                const slot = thumbnails[i];
                if (!slot.style.backgroundImage.includes(defaultThumbnailSrc)) {
                    allDefault = false; // If any slot doesn't have the default image, break the loop
                    break;
                }
            }

            // If all thumbnails have the default background, reset the main preview image
            if (allDefault) {
                document.getElementById('preview-img').src = defaultImageSrc;
            }
        }

        //JavaScript for Image Preview and Thumbnails End

        document.addEventListener('DOMContentLoaded', function() {
            // Handle radio button change event
            const cultureRadio = document.getElementById('cultureNatureYes');
            const natureRadio = document.getElementById('cultureNatureNo');
            const tabsContainer = document.getElementById('cultureNatureTabs');

            function toggleTabs() {
                tabsContainer.style.display = 'block'; // Show the tab section
                if (cultureRadio.checked) {
                    // Show culture tabs and hide nature tabs
                    document.querySelector('#culture-tab1').style.display = 'block';
                    document.querySelector('#culture-tab2').style.display = 'block';
                    document.querySelector('#culture-tab3').style.display = 'block';

                    document.querySelector('#nature-tab1').style.display = 'none';
                    document.querySelector('#nature-tab2').style.display = 'none';
                    document.querySelector('#nature-tab3').style.display = 'none';

                    // Make the first culture tab active
                    document.querySelector('#culture-tab1').click();
                } else if (natureRadio.checked) {
                    // Show nature tabs and hide culture tabs
                    document.querySelector('#nature-tab1').style.display = 'block';
                    document.querySelector('#nature-tab2').style.display = 'block';
                    document.querySelector('#nature-tab3').style.display = 'block';

                    document.querySelector('#culture-tab1').style.display = 'none';
                    document.querySelector('#culture-tab2').style.display = 'none';
                    document.querySelector('#culture-tab3').style.display = 'none';

                    // Make the first nature tab active
                    document.querySelector('#nature-tab1').click();
                }
            }

            // Event listeners for radio buttons
            cultureRadio.addEventListener('change', toggleTabs);
            natureRadio.addEventListener('change', toggleTabs);

            // By default, select and show Culture tabs
            toggleTabs(); // Trigger the toggleTabs function on page load to show default selection


            // Get all card-body cardbodselect elements start
            const cardBodies = document.querySelectorAll(".cardbodselect");
            // Add click event listener to each card-body cardbodselect
            cardBodies.forEach(cardBody => {
                cardBody.addEventListener("click", function() {
                    // Remove checkmark from all icons and reset to X, also remove 'selected' class
                    cardBodies.forEach(cb => {
                        const icon = cb.querySelector("i");
                        icon.classList.remove("lni-checkmark");
                        icon.classList.add("lni-close");
                        cb.classList.remove("selected");
                    });

                    // Toggle the clicked card-body cardbodselect's icon to checkmark and add 'selected' class
                    const icon = this.querySelector("i");
                    icon.classList.remove("lni-close");
                    icon.classList.add("lni-checkmark");
                    this.classList.add("selected");
                });
            });
            // Get all card-body cardbodselect elements end

            //choose city country or region according to its geography start
            /*const geographySelect = document.getElementById('inputGeography');
            const levelSelect = document.getElementById('inputLanguageScript');
            const labelSelect = document.getElementById('inputLanguageLabel');

            const regions = @json($regions);
            const cities = @json($cities);
            const countries = @json($countries);

            geographySelect.addEventListener('change', function() {
                const selectedValue = this.value;
                let options = [];

                // Clear the existing options
                levelSelect.innerHTML = '<option selected disabled>Choose...</option>';
                if (selectedValue == 5) { // Region selected
                    options = regions.map(region =>
                        `<option value="${region.regionId}">${region.regionnameInEnglish}</option>`);
                    labelSelect.textContent = 'Select Region'; // Update label text
                } else if (selectedValue == 6) { // City selected
                    options = cities.map(city =>
                        `<option value="${city.cityId}">${city.citynameInEnglish}</option>`);
                    labelSelect.textContent = 'Select City'; // Update label text
                } else if (selectedValue == 7) { // Country selected
                    options = countries.map(country =>
                        `<option value="${country.countryId}">${country.countryNameInEnglish}</option>`);
                    labelSelect.textContent = 'Select Country'; // Update label text
                }
                // Append new options to the select dropdown
                levelSelect.innerHTML += options.join('');
            });*/
            //choose city country or region according to its geography end
        });
    </script>
@endsection
