<div class="sidebar-header">
    <div>
        {{-- <img src="{{ asset('assets/images/logo-icon.png') }}" class="logo-icon" alt="logo icon"> --}}
        <img src="{{ asset('assets/images/logo.png') }}" class="logo-icon" alt="logo icon">
    </div>
    <div>
        <h4 class="logo-text">PRARANG</h4>
    </div>
    <div class="toggle-icon ms-auto">
        {{-- <i class='bx bx-arrow-to-left'></i> --}}
    </div>
</div>
<!--navigation-->
<ul class="metismenu" id="menu">
    @if (Auth::guard('admin')->check())
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-home-circle'></i>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>
            <ul>
                <li> <a href="{{ url('/admin/dashboard') }}"><i class="bx bx-right-arrow-alt"></i>Home</a>
                </li>
                <li> <a href="{{ url('/admin/maker/maker-listing') }}"><i class="bx bx-right-arrow-alt"></i>Maker</a>
                </li>
                <li> <a href="{{ url('/admin/checker/checker-listing') }}"><i class="bx bx-right-arrow-alt"></i>Checker</a>
                </li>
                <li> <a href="{{ url('/admin/uploader/uploader-listing') }}"><i class="bx bx-right-arrow-alt"></i>Uploader</a>
                </li>
                <li> <a href="{{ url('/admin/postanalyticsmaker/post-analytics-maker-city-listing') }}"><i class="bx bx-right-arrow-alt"></i>Post Analytics Maker</a>
                </li>
                <li> <a href="{{ url('/admin/postanalyticschecker/post-analytics-checker-city-listing') }}"><i class="bx bx-right-arrow-alt"></i>Post Analytics Checker</a>
                </li>
                <li> <a href="{{ url('/admin/postanalytics/post-analytics-listing') }}"><i class="bx bx-right-arrow-alt"></i>Post Analytics</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="lni lni-user"></i>
                </div>
                <div class="menu-title">Admin</div>
            </a>
            <ul>
                <li> <a href="{{ url('/admin/user-profile') }}"><i class="bx bx-right-arrow-alt"></i>User Profile</a>
                </li>
                <li> <a href="{{ url('/admin/user-listing') }}"><i class="bx bx-right-arrow-alt"></i>User</a>
                </li>
                <li> <a href="{{ url('/admin/role/role-listing') }}"><i class="bx bx-right-arrow-alt"></i>Role</a>
                </li>
                <li> <a href="{{ url('/admin/languagescript/languagescript-listing') }}"><i class="bx bx-right-arrow-alt"></i>Language Script</a>
                </li>
                <li> <a href="{{ url('/admin/country/country-listing') }}"><i class="bx bx-right-arrow-alt"></i>Country</a>
                </li>
                <li> <a href="{{ url('/admin/livecity/live-city-listing') }}"><i class="bx bx-right-arrow-alt"></i>Live City</a>
                </li>
                <li> <a href="{{ url('/admin/scities/scities-listing') }}"><i class="bx bx-right-arrow-alt"></i>City</a>
                </li>
                <li> <a href="{{ url('/admin/region/region-listing') }}"><i class="bx bx-right-arrow-alt"></i>Region</a>
                    {{-- Portal: Vivek --}}
                    <li> <a href="{{ route('portal.index') }}"><i class="bx bx-right-arrow-alt"></i>Portals</a>
                    </li>
                    {{-- End Portal:Vivek --}}
                </li>
                <li> <a href="{{ url('/admin/tagcategory/tag-category-listing') }}"><i class="bx bx-right-arrow-alt"></i>Tag Category</a>
                </li>
                <li> <a href="{{ url('/admin/tag/tag-listing') }}"><i class="bx bx-right-arrow-alt"></i>Tag</a>
                </li>
                <li> <a href="{{ url('/admin/usercountry/user-country-listing') }}"><i class="bx bx-right-arrow-alt"></i>User Country</a>
                </li>
                <li> <a href="{{ url('/admin/usercity/user-city-listing') }}"><i class="bx bx-right-arrow-alt"></i>User City</a>
                </li>
                <li> <a href="{{ url('/admin/post/post-listing') }}"><i class="bx bx-right-arrow-alt"></i>Post</a>
                </li>
                <li> <a href="{{ url('/admin/deleted-post/deleted-post-listing') }}"><i class="bx bx-right-arrow-alt"></i>Deleted Post</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-cart'></i>
                </div>
                <div class="menu-title">eCommerce</div>
            </a>
            <ul>
                <li> <a href="ecommerce-products.html"><i class="bx bx-right-arrow-alt"></i>Products</a>
                </li>
                <li> <a href="ecommerce-products-details.html"><i class="bx bx-right-arrow-alt"></i>Product Details</a>
                </li>
                <li> <a href="ecommerce-add-new-products.html"><i class="bx bx-right-arrow-alt"></i>Add New Products</a>
                </li>
                <li> <a href="ecommerce-orders.html"><i class="bx bx-right-arrow-alt"></i>Orders</a>
                </li>
            </ul>
        </li>
    @elseif (Auth::check())
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-home-circle'></i>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>
            <ul>
                <li> <a href="{{ url('/accounts/dashboard') }}"><i class="bx bx-right-arrow-alt"></i>Home</a>
                </li>
                <li> <a href="dashboard-eCommerce.html"><i class="bx bx-right-arrow-alt"></i>Maker</a>
                </li>
                <li> <a href="dashboard-analytics.html"><i class="bx bx-right-arrow-alt"></i>Checker</a>
                </li>
                <li> <a href="dashboard-digital-marketing.html"><i class="bx bx-right-arrow-alt"></i>Uploader</a>
                </li>
                <li> <a href="dashboard-human-resources.html"><i class="bx bx-right-arrow-alt"></i>Post Analytics Maker</a>
                </li>
                <li> <a href="dashboard-human-resources.html"><i class="bx bx-right-arrow-alt"></i>Post Analytics Checker</a>
                </li>
                <li> <a href="dashboard-human-resources.html"><i class="bx bx-right-arrow-alt"></i>Post Analytics</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="lni lni-user"></i>
                </div>
                <div class="menu-title">Admin</div>
            </a>
            <ul>
                <li> <a href="app-emailbox.html"><i class="bx bx-right-arrow-alt"></i>User</a>
                </li>
                <li> <a href="app-chat-box.html"><i class="bx bx-right-arrow-alt"></i>Role</a>
                </li>
                <li> <a href="app-file-manager.html"><i class="bx bx-right-arrow-alt"></i>Language Script</a>
                </li>
                <li> <a href="app-contact-list.html"><i class="bx bx-right-arrow-alt"></i>Country</a>
                </li>
                <li> <a href="app-to-do.html"><i class="bx bx-right-arrow-alt"></i>Live City</a>
                </li>
                <li> <a href="app-invoice.html"><i class="bx bx-right-arrow-alt"></i>City</a>
                </li>
                <li> <a href="app-fullcalender.html"><i class="bx bx-right-arrow-alt"></i>Region</a>
                </li>
                <li> <a href="app-fullcalender.html"><i class="bx bx-right-arrow-alt"></i>Tag Category</a>
                </li>
                <li> <a href="app-fullcalender.html"><i class="bx bx-right-arrow-alt"></i>Tag</a>
                </li>
                <li> <a href="app-fullcalender.html"><i class="bx bx-right-arrow-alt"></i>User Country</a>
                </li>
                <li> <a href="app-fullcalender.html"><i class="bx bx-right-arrow-alt"></i>User City</a>
                </li>
                <li> <a href="app-fullcalender.html"><i class="bx bx-right-arrow-alt"></i>Upload Excel File</a>
                </li>
                <li> <a href="app-fullcalender.html"><i class="bx bx-right-arrow-alt"></i>Post</a>
                </li>
                <li> <a href="app-fullcalender.html"><i class="bx bx-right-arrow-alt"></i>Deleted Post</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-cart'></i>
                </div>
                <div class="menu-title">eCommerce</div>
            </a>
            <ul>
                <li> <a href="ecommerce-products.html"><i class="bx bx-right-arrow-alt"></i>Products</a>
                </li>
                <li> <a href="ecommerce-products-details.html"><i class="bx bx-right-arrow-alt"></i>Product Details</a>
                </li>
                <li> <a href="ecommerce-add-new-products.html"><i class="bx bx-right-arrow-alt"></i>Add New Products</a>
                </li>
                <li> <a href="ecommerce-orders.html"><i class="bx bx-right-arrow-alt"></i>Orders</a>
                </li>
            </ul>
        </li>
    @endif
</ul>
<!--end navigation-->
