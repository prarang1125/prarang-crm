@extends('layouts.admin.admin')
@section('title', 'Portals')

@section('content')
<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
<style>
    .book-field,
    .link-name,
    .link-url,
    .category-name {
        font-size: 0.9rem;
    }

    .card-header h5,
    .card-header h6 {
        font-weight: 600;
    }

    #booksContainer .card,
    #linksContainer .card {
        transition: all 0.3s ease;
    }

    #booksContainer .card:hover,
    #linksContainer .card:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .link-entry {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 10px;
    }

    .category-name {
        min-width: 200px;
    }
</style>
<!--start page wrapper -->
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Admin</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('portal.index') }}">Portal List</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">New Portal</li>
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
                <h6 class="mb-0 text-uppercase text-primary">Create Portals</h6>
                <hr />
                <div class="">

                    <div class="card-body">

                        <form action="{{ route('portal.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-sm-4">
                                    <label for="city_name">City Name</label>
                                    <input class="form-control" type="text" name="city_name" id="city_name"
                                        value="{{ old('city_name') }}" required>
                                    @error('city_name')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-sm-4">
                                    <label for="city_id">City ID</label>
                                    <input class="form-control" type="number" name="city_id" id="city_id"
                                        value="{{ old('city_id') }}" required>
                                    @error('city_id')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-sm-4">
                                    <label for="city_code">City Code</label>
                                    <select class="form-control" name="city_code" id="city_code" required>
                                        <option value="">Select City Code</option>
                                        @foreach ($cityCodes as $cityCode)
                                        <option value="{{ $cityCode->geographycode }}" {{ old('city_code')==$cityCode->
                                            geographycode ? 'selected' : '' }}>
                                            {{ $cityCode->geographycode }} - {{ $cityCode->geography }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('city_code')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>


                            <div class="row mt-3">
                                <div class="col-sm-6">
                                    <label for="city_name_local">City Name Local</label>
                                    <input class="form-control" type="text" name="city_name_local" id="city_name_local"
                                        value="{{ old('city_name_local') }}">
                                    @error('city_name_local')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label for="city_slogan">City Slogan</label>
                                    <input class="form-control" type="text" name="city_slogan" id="city_slogan"
                                        value="{{ old('city_slogan') }}">
                                    @error('city_slogan')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>



                            <div class="row mt-3">
                                <div class="col-sm-6">
                                    <label for="local_lang">Local Language</label>
                                    <select class="form-control" name="local_lang" id="local_lang">
                                        <option value="">Select Language</option>
                                        <option value="en" {{ old('local_lang')=='en' ? 'selected' : '' }}>
                                            English (en)</option>
                                        <option value="hi" {{ old('local_lang')=='hi' ? 'selected' : '' }}>Hindi
                                            (hi)</option>
                                        <option value="bn" {{ old('local_lang')=='bn' ? 'selected' : '' }}>
                                            Bengali (bn)</option>
                                        <option value="te" {{ old('local_lang')=='te' ? 'selected' : '' }}>Telugu
                                            (te)</option>
                                        <option value="mr" {{ old('local_lang')=='mr' ? 'selected' : '' }}>
                                            Marathi (mr)</option>
                                        <option value="ta" {{ old('local_lang')=='ta' ? 'selected' : '' }}>Tamil
                                            (ta)</option>
                                        <option value="ur" {{ old('local_lang')=='ur' ? 'selected' : '' }}>Urdu
                                            (ur)</option>
                                        <option value="gu" {{ old('local_lang')=='gu' ? 'selected' : '' }}>
                                            Gujarati (gu)</option>
                                        <option value="kn" {{ old('local_lang')=='kn' ? 'selected' : '' }}>
                                            Kannada (kn)</option>
                                        <option value="or" {{ old('local_lang')=='or' ? 'selected' : '' }}>Odia
                                            (or)</option>
                                        <option value="pa" {{ old('local_lang')=='pa' ? 'selected' : '' }}>
                                            Punjabi (pa)</option>
                                        <option value="ml" {{ old('local_lang')=='ml' ? 'selected' : '' }}>
                                            Malayalam (ml)</option>
                                        <option value="as" {{ old('local_lang')=='as' ? 'selected' : '' }}>
                                            Assamese (as)</option>
                                    </select>
                                    @error('local_lang')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label for="map_link">Slug</label>
                                    <input class="form-control" type="text" name="slug" id="slug"
                                        value="{{ old('slug') }}">
                                    @error('slug')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-sm-6 mt-2">
                                    <label for="list_order">Order</label>
                                    <input class="form-control" type="number" name="list_order" id="list_order"
                                        value="{{ old('list_order') }}">
                                    @error('list_order')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-sm-6 mt-2">
                                    <label for="state">State</label>
                                    <input class="form-control" type="text" name="state" id="state"
                                        value="{{ old('state') }}">
                                    @error('state')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>


                            </div>
                            <div class="mt-3">
                                <label for="map_link">Map Link</label>
                                <input class="form-control" type="text" name="map_link" id="map_link"
                                    value="{{ old('map_link') }}">
                                @error('map_link')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>



                            <div class="row">
                                <div class="col-sm-6">
                                    <!-- Weather Widget Code -->
                                    <div class="mt-3">
                                        <label for="weather_widget_code">Weather Widget Code</label>
                                        <textarea class="form-control" name="weather_widget_code"
                                            id="weather_widget_code">{{ old('weather_widget_code') }}</textarea>
                                        @error('weather_widget_code')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Sports Widget Code -->
                                    <div class="mt-3">
                                        <label for="sports_widget_code">Sports Widget Code</label>
                                        <textarea class="form-control" name="sports_widget_code"
                                            id="sports_widget_code">{{ old('sports_widget_code') }}</textarea>
                                        @error('sports_widget_code')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- News Widget Code -->
                                    <div class="mt-3">
                                        <label for="news_widget_code">News Widget Code</label>
                                        <textarea class="form-control" name="news_widget_code"
                                            id="news_widget_code">{{ old('news_widget_code') }}</textarea>
                                        @error('news_widget_code')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>


                                </div>
                                <div class="col-sm-6">

                                    <!-- Local Matrics -->
                                    <div class="mt-3">
                                        <label for="local_matrics">Local Metrics</label>
                                        <textarea class="form-control ckeditorinit" name="local_matrics"
                                            id="local_matrics">{{ old('local_matrics') }}</textarea>
                                        @error('local_matrics')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="mt-3 col-sm-6">
                                    <label for="header_scripts">Header Scripts</label>
                                    <textarea class="form-control " rows="8" name="header_scripts"
                                        id="header_scripts">{{ old('header_scripts') }}</textarea>
                                    @error('header_scripts')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mt-3 col-sm-6">
                                    <label for="footer_scripts">Footer Scripts</label>
                                    <textarea class="form-control " rows="8" name="footer_scripts"
                                        id="footer_scripts">{{ old('footer_scripts') }}</textarea>
                                    @error('footer_scripts')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- Books Section -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card">
                                        <div
                                            class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">Books</h5>
                                            <button type="button" class="btn btn-light btn-sm" onclick="addBookEntry()">
                                                <i class="bx bx-plus"></i> Add Book
                                            </button>
                                        </div>
                                        <div class="card-body">
                                            <div id="booksContainer"></div>
                                            <textarea class="form-control d-none" name="books" id="books"></textarea>
                                            @error('books')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Links Section -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card">
                                        <div
                                            class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">Links</h5>
                                            <button type="button" class="btn btn-light btn-sm"
                                                onclick="addLinkCategory()">
                                                <i class="bx bx-plus"></i> Add Category
                                            </button>
                                        </div>
                                        <div class="card-body">
                                            <div id="linksContainer"></div>
                                            <textarea class="form-control d-none" name="links" id="links"></textarea>
                                            @error('links')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="viewership">Viewership Text (HTML)</label>
                                <textarea class="form-control " rows="8" name="viewership"
                                    id="viewership">{{ old('viewership') }}</textarea>
                                @error('viewership')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <br>
                            <!-- Header Image -->
                            <div class="row">
                                <!-- Header Image -->
                                <div class="col-md-4">
                                    <label for="header_image">Header Image</label>
                                    <input class="form-control" type="file" name="header_image" id="header_image"
                                        accept="image/*" onchange="previewImage(this, 'header_preview')">
                                    @error('header_image')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="mt-2">
                                        <img id="header_preview" src="#" alt="Header Image Preview"
                                            style="display: none; max-height: 100px;">
                                    </div>
                                </div>

                                <!-- Footer Image -->
                                <div class="col-md-4">
                                    <label for="footer_image">Footer Image</label>
                                    <input class="form-control" type="file" name="footer_image" id="footer_image"
                                        accept="image/*" onchange="previewImage(this, 'footer_preview')">
                                    @error('footer_image')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="mt-2">
                                        <img id="footer_preview" src="#" alt="Footer Image Preview"
                                            style="display: none; max-height: 100px;">
                                    </div>
                                </div>

                                <!-- Local Info Image -->
                                <div class="col-md-4">
                                    <label for="local_info_image">Local Info Image</label>
                                    <input class="form-control" type="file" name="local_info_image"
                                        id="local_info_image" accept="image/*"
                                        onchange="previewImage(this, 'local_info_preview')">
                                    @error('local_info_image')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="mt-2">
                                        <img id="local_info_preview" src="#" alt="Local Info Image Preview"
                                            style="display: none; max-height: 100px;">
                                    </div>
                                </div>
                            </div>

                            <button class="text-end btn btn-success  mt-4" type="submit">Create City</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(input, previewId) {
            const file = input.files[0];
            const preview = document.getElementById(previewId);

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = "block"; // Show the preview image
                };
                reader.readAsDataURL(file);
            } else {
                preview.style.display = "none"; // Hide the preview if no file selected
            }
        }
</script>
<script>
    // Select all textarea elements
        document.querySelectorAll('.ckeditorinit').forEach(function(textarea) {
            ClassicEditor
                .create(textarea)
                .catch(error => {
                    console.error(error);
                });
        });

    // ============================================
    // BOOKS MANAGEMENT
    // ============================================
    let bookCounter = 0;
    let booksData = [];

    function addBookEntry(data = null) {
        bookCounter++;
        const bookId = `book_${bookCounter}`;
        
        const bookEntry = document.createElement('div');
        bookEntry.className = 'card mb-3 border-primary';
        bookEntry.id = bookId;
        bookEntry.innerHTML = `
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Book #${bookCounter}</h6>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeBookEntry('${bookId}')">
                    <i class="bx bx-trash"></i> Remove
                </button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>S.No</label>
                        <input type="number" class="form-control book-field" data-field="s_no" value="${data?.s_no || bookCounter}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control book-field" data-field="name" value="${data?.name || ''}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Author</label>
                        <input type="text" class="form-control book-field" data-field="author" value="${data?.author || ''}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Publisher</label>
                        <input type="text" class="form-control book-field" data-field="publisher" value="${data?.publisher || ''}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Year</label>
                        <input type="text" class="form-control book-field" data-field="year" value="${data?.year || ''}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Tag</label>
                        <input type="text" class="form-control book-field" data-field="tag" value="${data?.tag || ''}">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Link (URL)</label>
                        <input type="url" class="form-control book-field" data-field="link" value="${data?.link || ''}" placeholder="https://example.com">
                    </div>
                </div>
            </div>
        `;
        
        document.getElementById('booksContainer').appendChild(bookEntry);
    }

    function removeBookEntry(bookId) {
        const element = document.getElementById(bookId);
        if (element) {
            element.remove();
        }
    }

    function collectBooksData() {
        const books = [];
        const bookEntries = document.querySelectorAll('#booksContainer .card');
        
        bookEntries.forEach((entry, index) => {
            const fields = entry.querySelectorAll('.book-field');
            const bookData = {};
            
            fields.forEach(field => {
                const fieldName = field.getAttribute('data-field');
                bookData[fieldName] = field.value.trim();
            });
            
            // Only add if name is provided
            if (bookData.name) {
                books.push(bookData);
            }
        });
        
        return books;
    }

    // ============================================
    // LINKS MANAGEMENT
    // ============================================
    let categoryCounter = 0;
    let linksData = {};

    function addLinkCategory(categoryName = null, links = []) {
        categoryCounter++;
        const categoryId = `category_${categoryCounter}`;
        
        const categoryEntry = document.createElement('div');
        categoryEntry.className = 'card mb-3 border-success';
        categoryEntry.id = categoryId;
        categoryEntry.innerHTML = `
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <div class="flex-grow-1">
                    <label class="mb-0 me-2">Category Name (Hindi):</label>
                    <input type="text" class="form-control d-inline-block w-auto category-name" value="${categoryName || ''}" placeholder="सरकारी वेबसाइट्स" required>
                </div>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeLinkCategory('${categoryId}')">
                    <i class="bx bx-trash"></i> Remove Category
                </button>
            </div>
            <div class="card-body">
                <div class="links-list" id="${categoryId}_links"></div>
                <button type="button" class="btn btn-sm btn-outline-success mt-2" onclick="addLinkToCategory('${categoryId}')">
                    <i class="bx bx-plus"></i> Add Link
                </button>
            </div>
        `;
        
        document.getElementById('linksContainer').appendChild(categoryEntry);
        
        // Add existing links if provided
        if (links && links.length > 0) {
            links.forEach(link => {
                addLinkToCategory(categoryId, link);
            });
        } else {
            // Add one empty link by default
            addLinkToCategory(categoryId);
        }
    }

    function removeLinkCategory(categoryId) {
        const element = document.getElementById(categoryId);
        if (element) {
            element.remove();
        }
    }

    let linkCounter = 0;

    function addLinkToCategory(categoryId, data = null) {
        linkCounter++;
        const linkId = `link_${linkCounter}`;
        
        const linkEntry = document.createElement('div');
        linkEntry.className = 'row mb-2 align-items-center link-entry';
        linkEntry.id = linkId;
        linkEntry.innerHTML = `
            <div class="col-md-5">
                <input type="text" class="form-control link-name" placeholder="Link Name" value="${data?.name || ''}" required>
            </div>
            <div class="col-md-6">
                <input type="url" class="form-control link-url" placeholder="https://example.com" value="${data?.url || ''}" required>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-sm btn-danger" onclick="removeLink('${linkId}')">
                    <i class="bx bx-trash"></i>
                </button>
            </div>
        `;
        
        document.getElementById(`${categoryId}_links`).appendChild(linkEntry);
    }

    function removeLink(linkId) {
        const element = document.getElementById(linkId);
        if (element) {
            element.remove();
        }
    }

    function collectLinksData() {
        const linksObject = {};
        const categoryEntries = document.querySelectorAll('#linksContainer .card');
        
        categoryEntries.forEach(categoryEntry => {
            const categoryNameInput = categoryEntry.querySelector('.category-name');
            const categoryName = categoryNameInput.value.trim();
            
            if (!categoryName) return;
            
            const links = [];
            const linkEntries = categoryEntry.querySelectorAll('.link-entry');
            
            linkEntries.forEach(linkEntry => {
                const name = linkEntry.querySelector('.link-name').value.trim();
                const url = linkEntry.querySelector('.link-url').value.trim();
                
                if (name && url) {
                    links.push({ name, url });
                }
            });
            
            if (links.length > 0) {
                linksObject[categoryName] = links;
            }
        });
        
        return linksObject;
    }

    // ============================================
    // FORM SUBMISSION HANDLER
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        
        form.addEventListener('submit', function(e) {
            // Collect books data
            const books = collectBooksData();
            const booksJSON = JSON.stringify({ books: books }, null, 2);
            document.getElementById('books').value = booksJSON;
            
            // Collect links data
            const links = collectLinksData();
            const linksJSON = JSON.stringify(links, null, 2);
            document.getElementById('links').value = linksJSON;
            
            console.log('Books JSON:', booksJSON);
            console.log('Links JSON:', linksJSON);
        });

        // Load old data if exists (for validation errors)
        const oldBooks = @json(old('books'));
        const oldLinks = @json(old('links'));
        
        if (oldBooks) {
            try {
                const booksData = typeof oldBooks === 'string' ? JSON.parse(oldBooks) : oldBooks;
                if (booksData.books && Array.isArray(booksData.books)) {
                    booksData.books.forEach(book => addBookEntry(book));
                }
            } catch (e) {
                console.error('Error parsing old books data:', e);
            }
        }
        
        if (oldLinks) {
            try {
                const linksData = typeof oldLinks === 'string' ? JSON.parse(oldLinks) : oldLinks;
                Object.keys(linksData).forEach(categoryName => {
                    addLinkCategory(categoryName, linksData[categoryName]);
                });
            } catch (e) {
                console.error('Error parsing old links data:', e);
            }
        }
    });
</script>
@endsection
