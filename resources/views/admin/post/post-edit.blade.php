@extends('layouts.admin.admin')
@section('title', 'Post Edit')

@section('content')
<!--start page wrapper -->
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Admin</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ url('admin/post/post-listing')}}"><i class="bx bx-user"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Post Edit</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
        <div class="card" style="padding-top: 15px;">
            <div class="col-xl-9 mx-auto w-100">
                <!-- Success Message -->
                @if(session('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                    </div>
                @endif
                <h6 class="mb-0 text-uppercase text-primary">Maker Edit</h6>
                <hr/>
                <form  action="{{ route('admin.post-update' , $chitti->chittiId) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    {{-- image preview and image thumbnail and content section --}}
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="content" class="form-label">Content</label>
                            <textarea class="@error('content') is-invalid @enderror" name="content" id="editor">{{ old('text',$chitti->description) }}</textarea>
                            @error('content')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- Image Preview and Thumbnails -->
                        <div class="col-md-4">
                            <label>Image Preview</label>
                            <div class="image-preview-mt" id="image-preview" style="max-width: 300px; max-height: 300px; overflow: hidden; border: 1px solid #ccc; padding: 5px;">
                                <img id="preview-img" src="{{ $image ? Storage::url($image->accessUrl) : '/img/blankImage2.png' }}" alt="Image Preview" style="width: 288px; height: 250px; background-size: cover;" />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label>Thumbnail</label>
                            <div id="thumbnail" class="d-flex flex-wrap" style="gap: 10px; border: 1px solid #ccc; padding: 5px; min-height: 80px; background-color: #28252517;">
                                <div class="thumbnail-slot" style="background-image: url('{{ $image ? Storage::url($image->accessUrl) : '/img/blankImage2.png' }}'); background-size: cover; width:100%; height:100px;position:relative;">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- image upload --}}
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label for="makerImage" class="form-label">Upload Image</label>
                            <input type="file" class="form-control @error('makerImage') is-invalid @enderror" id="makerImage" name="makerImage"
                            onchange="previewImage()">
                            @error('makerImage')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                            {{-- @if($image)
                                <p>Current Image: {{ $image->imageName }}</p>
                            @else
                                <p>No image uploaded</p>
                            @endif --}}
                        </div>
                    </div>

                    {{-- geography and area code start--}}
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="inputGeography" class="form-label">Geography</label>
                            <select id="inputGeography" class="form-select @error('geography') is-invalid @enderror" name="geography">
                                <option selected disabled>Choose...</option>
                                @foreach($geographyOptions as $geographyOption)
                                    <option value="{{ $geographyOption->id }}"
                                        {{ $geographyMapping && $geographyMapping->geographyId == $geographyOption->id ? 'selected' : '' }}>
                                        {{ $geographyOption->labelInEnglish }}
                                    </option>
                                @endforeach
                            </select>
                            @error('geography')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label id="inputLanguageLabel" for="inputLanguageScript" class="form-label">Select Area</label>
                            <select id="inputLanguageScript" class="form-select @error('c2rselect') is-invalid @enderror" name="c2rselect">
                                <option selected disabled>Choose...</option>

                                {{-- Display Regions if the areaId is a region --}}
                                @if($geographyMapping && $geographyMapping->region)
                                    @foreach($regions as $region)
                                        <option value="{{ $region->regionId }}"
                                            {{ $geographyMapping->areaId == $region->regionId ? 'selected' : '' }}>
                                            {{ $region->regionnameInEnglish }}
                                        </option>
                                    @endforeach
                                @endif

                                {{-- Display Cities if the areaId is a city --}}
                                @if($geographyMapping && $geographyMapping->city)
                                    @foreach($cities as $city)
                                        <option value="{{ $city->cityId }}"
                                            {{ $geographyMapping->areaId == $city->cityId ? 'selected' : '' }}>
                                            {{ $city->cityNameInEnglish }}
                                        </option>
                                    @endforeach
                                @endif

                                {{-- Display Countries if the areaId is a country --}}
                                @if($geographyMapping && $geographyMapping->country)
                                    @foreach($countries as $country)
                                        <option value="{{ $country->countryId }}"
                                            {{ $geographyMapping->areaId == $country->countryId ? 'selected' : '' }}>
                                            {{ $country->countryNameInEnglish }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('c2rselect')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    {{-- geography and area code end--}}

                    {{-- title and subtitle code start--}}
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control  @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $chitti->Title) }}" >
                            @error('title')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="subtitle" class="form-label">Sub Title</label>
                            <input type="text" class="form-control  @error('subtitle') is-invalid @enderror" id="subtitle" name="subtitle" value="{{ old('subtitle', $chitti->SubTitle) }}" >
                            @error('subtitle')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    {{-- title and subtitle code end--}}

                    {{-- above city or about city code start--}}
                    <div class="row mt-1">
                        <div class="row align-items-center">
                            <div class="col-md-4 mt-2">
                                <label class="form-label">Select</label>
                            </div>
                            <div class="col-md-8 forcity">
                                <div class="form-check me-3">
                                    <input class="form-check-input @error('forTheCity') is-invalid @enderror"
                                        type="radio" name="forTheCity" id="forTheCityYes" value="1"
                                        {{ old('forTheCity', $facityValue) == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="forTheCityYes">For the city</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input @error('forTheCity') is-invalid @enderror"
                                        type="radio" name="forTheCity" id="aboutTheCity" value="0"
                                        {{ old('forTheCity', $facityValue) == '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="aboutTheCity">About the City</label>
                                </div>
                                <!-- Display the error message for the radio group -->
                                @error('forTheCity')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    {{-- above city or about city code end--}}

                    {{-- nature and culture code start--}}
                    <div class="row mt-3">
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input @error('isCultureNature') is-invalid @enderror"
                                    type="radio" name="isCultureNature" id="cultureNatureYes" value="1"
                                    {{ old('isCultureNature', $chittiTagMapping->tagId) == 1 ? 'checked' : '' }}> <!-- Default checked -->
                                <label class="form-check-label" for="cultureNatureYes">Culture</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input @error('isCultureNature') is-invalid @enderror"
                                    type="radio" name="isCultureNature" id="cultureNatureNo" value="0"
                                    {{ old('isCultureNature', $chittiTagMapping->tagId) == 0 ? 'checked' : '' }}>
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
                                <a class="nav-link active" id="culture-tab1" data-bs-toggle="tab" href="#cultureTab1" role="tab" style="background-color: #ff0006;color: white;">Timelines</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="culture-tab2" data-bs-toggle="tab" href="#cultureTab2" role="tab" style="background-color: #ffff18;color: #282828;">Man And his Senses</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="culture-tab3" data-bs-toggle="tab" href="#cultureTab3" role="tab" style="background-color: #1919d9;color: white;">Man and his Inventions</a>
                            </li>

                            <!-- Nature tabs -->
                            <li class="nav-item">
                                <a class="nav-link" id="nature-tab1" data-bs-toggle="tab" href="#natureTab1" role="tab" style="display:none; background-color: #faff98;color: #282828;">Geography</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="nature-tab2" data-bs-toggle="tab" href="#natureTab2" role="tab" style="display:none; background-color: #c8ff00;color: #282828;">Fauna</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="nature-tab3" data-bs-toggle="tab" href="#natureTab3" role="tab" style="display:none; background-color: #339933;color: #fff;">Flora</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <!-- Content for Culture Tab 1 (Timelines) -->
                            <div class="tab-pane fade show active" id="cultureTab1" role="tabpanel">
                                <div class="row">
                                   @foreach ($timelines as $timeline)
                                        <div class="col-md-4">
                                            <div class="card">
                                                <div class="card-body cardbodselect mt-3" style="background-color: #ff0006;color: white;">
                                                    <i class="lni lni-close"></i>
                                                    {{ $timeline->tagInEnglish }}
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
                                                <div class="card-body cardbodselect mt-3" style="background-color: #ffff18;color: #282828;">
                                                    <i class="lni lni-close"></i>
                                                    {{ $sense->tagInEnglish }}
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
                                                <div class="card-body cardbodselect mt-3" style="background-color: #1919d9;color: white;">
                                                    <i class="lni lni-close"></i>
                                                    {{ $invention->tagInEnglish }}
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
                                                <div class="card-body cardbodselect mt-3" style="background-color: #faff98;color: #282828;">
                                                    <i class="lni lni-close"></i>
                                                    {{ $geography->tagInEnglish }}
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
                                                <div class="card-body cardbodselect mt-3" style="background-color: #c8ff00;color: #282828;">
                                                    <i class="lni lni-close"></i>
                                                    {{ $fauna->tagInEnglish }}
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
                                                <div class="card-body cardbodselect mt-3" style=" background-color: #339933;color: #fff;">
                                                    <i class="lni lni-close"></i>
                                                    {{ $flora->tagInEnglish }}
                                                </div>
                                            </div>
                                         </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer mt-3">
                        {{-- <button type="submit" class="btn btn-primary">Update Maker</button>
                        <a href="{{ route('admin.checker-listing', $chitti->chittiId) }}" class="btn btn-primary">Send to Checker</a> --}}

                        <button type="submit" class="btn btn-primary" name="action" value="update_maker">Update Post</button>
                        <button type="submit" class="btn btn-primary" name="action" value="send_to_checker">Send to Checker</button>
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
function previewImage() {
    const input = document.getElementById('makerImage');
    const preview = document.getElementById('preview-img');
    const thumbnail = document.querySelector('.thumbnail-slot');

    const file = input.files[0];
    const reader = new FileReader();

    reader.onloadend = function () {
        preview.src = reader.result;
        thumbnail.style.backgroundImage = 'url(' + reader.result + ')';
    }

    if (file) {
        reader.readAsDataURL(file);
    } else {
        preview.src = '/img/blankImage2.png';
        thumbnail.style.backgroundImage = 'url(/img/blankImage2.png)';
    }
}
document.addEventListener('DOMContentLoaded', function () {
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

    // Choose city, country, or region according to its geography
    /*const geographySelect = document.getElementById('inputGeography');
    const levelSelect = document.getElementById('inputLanguageScript');
    const labelSelect = document.getElementById('inputLanguageLabel');

    // Regions, Cities, and Countries are passed as JSON from Blade
    const regions = @json($regions);
    const cities = @json($cities);
    const countries = @json($countries);

    geographySelect.addEventListener('change', function () {
        const selectedValue = this.value;
        let options = [];

        // Clear the existing options
        levelSelect.innerHTML = '<option selected disabled>Choose...</option>';

        // Check which geography option is selected and populate the dropdown accordingly
        if (selectedValue == 5) { // Region selected
            options = regions.map(region => `<option value="${region.regionId}">${region.regionnameInEnglish}</option>`);
            labelSelect.textContent = 'Select Region'; // Update label text
        } else if (selectedValue == 6) { // City selected
            options = cities.map(city => `<option value="${city.cityId}">${city.cityNameInEnglish}</option>`);
            labelSelect.textContent = 'Select City'; // Update label text
        } else if (selectedValue == 7) { // Country selected
            options = countries.map(country => `<option value="${country.countryId}">${country.countryNameInEnglish}</option>`);
            labelSelect.textContent = 'Select Country'; // Update label text
        }

        // Append new options to the select dropdown
        if (options.length > 0) {
            levelSelect.innerHTML += options.join('');
        }
    });*/
});
</script>
@endsection


