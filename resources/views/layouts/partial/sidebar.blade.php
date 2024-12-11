<div class="sidebar-header">
    <div>
        <img src="{{ asset('assets/images/logo.png') }}" class="logo-icon" alt="logo icon">
    </div>
    <div>
        <h4 class="logo-text">PRARANG</h4>
    </div>
    <div class="toggle-icon ms-auto">
        {{-- Optional toggle icon --}}
    </div>
</div>
<!--navigation-->
<ul class="metismenu" id="menu">
    @if (Auth::guard('admin')->check())
        {{-- Admin Sidebar --}}
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-home-circle'></i></div>
                <div class="menu-title">Dashboard</div>
            </a>
            <ul>


                <li><a href="{{ url('/admin/dashboard') }}"><i class="bx bx-right-arrow-alt"></i>Home</a></li>
                @if (Auth::user()->roleId == 1) {{-- Admin Role --}}
                    <li><a href="{{ url('/admin/user-listing') }}"><i class="bx bx-right-arrow-alt"></i>User</a></li>
                    <li><a href="{{ url('/admin/role/role-listing') }}"><i class="bx bx-right-arrow-alt"></i>Role</a></li>
                    <li><a href="{{ url('/admin/languagescript/languagescript-listing') }}"><i class="bx bx-right-arrow-alt"></i>Language Script</a></li>
                    <li><a href="{{ url('/admin/country/country-listing') }}"><i class="bx bx-right-arrow-alt"></i>Country</a></li>
                    <li><a href="{{ url('/admin/livecity/live-city-listing') }}"><i class="bx bx-right-arrow-alt"></i>Live City</a></li>
                    <li><a href="{{ url('/admin/scities/scities-listing') }}"><i class="bx bx-right-arrow-alt"></i>City</a></li>
                    <li><a href="{{ url('/admin/region/region-listing') }}"><i class="bx bx-right-arrow-alt"></i>Region</a></li>
                    <li><a href="{{ route('portal.index') }}"><i class="bx bx-right-arrow-alt"></i>Portals</a></li>
                    <li><a href="{{ url('/admin/tagcategory/tag-category-listing') }}"><i class="bx bx-right-arrow-alt"></i>Tag Category</a></li>
                    <li><a href="{{ url('/admin/tag/tag-listing') }}"><i class="bx bx-right-arrow-alt"></i>Tag</a></li>
                    <li><a href="{{ url('/admin/usercountry/user-country-listing') }}"><i class="bx bx-right-arrow-alt"></i>User Country</a></li>
                    <li><a href="{{ url('/admin/usercity/user-city-listing') }}"><i class="bx bx-right-arrow-alt"></i>User City</a></li>
                    <li><a href="{{ url('/admin/post/post-listing') }}"><i class="bx bx-right-arrow-alt"></i>Post</a></li>
                    <li><a href="{{ url('/admin/deleted-post/deleted-post-listing') }}"><i class="bx bx-right-arrow-alt"></i>Deleted Post</a></li>
                @elseif (Auth::user()->roleId == 2) {{-- Maker Role --}}
                    <li><a href="{{ url('/admin/maker/maker-listing') }}"><i class="bx bx-right-arrow-alt"></i>Maker</a></li>
                @elseif (Auth::user()->roleId == 3) {{-- Checker Role --}}
                    <li><a href="{{ url('/admin/checker/checker-listing') }}"><i class="bx bx-right-arrow-alt"></i>Checker</a></li>
                @elseif (Auth::user()->roleId == 4) {{-- Uploader Role --}}
                    <li><a href="{{ url('/admin/uploader/uploader-listing') }}"><i class="bx bx-right-arrow-alt"></i>Uploader</a></li>
                @elseif (Auth::user()->roleId == 6) {{-- Post Analytics Maker Role --}}
                    <li><a href="{{ url('/admin/postanalyticsmaker/post-analytics-maker-city-listing') }}"><i class="bx bx-right-arrow-alt"></i>Post Analytics Maker</a></li>
                @elseif (Auth::user()->roleId == 7) {{-- Post Analytics Checker Role --}}
                    <li><a href="{{ url('/admin/postanalyticschecker/post-analytics-checker-city-listing') }}"><i class="bx bx-right-arrow-alt"></i>Post Analytics Checker</a></li>
                @elseif (Auth::user()->roleId == 5) {{-- Post Analytics --}}
                    <li><a href="{{ url('/admin/postanalytics/post-analytics-listing') }}"><i class="bx bx-right-arrow-alt"></i>Post Analytics</a></li>
                @endif
            </ul>
        </li>
    @elseif (Auth::check())
        {{-- Other Roles Sidebar --}}
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-home-circle'></i></div>
                <div class="menu-title">Dashboard</div>
            </a>
            <ul>
                <li><a href="{{ url('/accounts/dashboard') }}"><i class="bx bx-right-arrow-alt"></i>Home</a></li>
                @if (Auth::user()->roleId == 2) {{-- Maker Role --}}
                    <li><a href="{{ url('/accounts/maker/maker-listing') }}"><i class="bx bx-right-arrow-alt"></i>Maker</a></li>
                @elseif (Auth::user()->roleId == 3) {{-- Checker Role --}}
                    <li><a href="{{ url('/accounts/checker/checker-listing') }}"><i class="bx bx-right-arrow-alt"></i>Checker</a></li>
                @elseif (Auth::user()->roleId == 4) {{-- Uploader Role --}}
                    <li><a href="{{ url('/accounts/uploader/uploader-listing') }}"><i class="bx bx-right-arrow-alt"></i>Uploader</a></li>
                @elseif (Auth::user()->roleId == 6) {{-- Post Analytics Maker Role --}}
                    <li><a href="{{ url('/accounts/postanalyticsmaker/post-analytics-maker-city-listing') }}"><i class="bx bx-right-arrow-alt"></i>Post Analytics Maker</a></li>
                @elseif (Auth::user()->roleId == 7) {{-- Post Analytics Checker Role --}}
                    <li><a href="{{ url('/accounts/postanalyticschecker/post-analytics-checker-city-listing') }}"><i class="bx bx-right-arrow-alt"></i>Post Analytics Checker</a></li>
                @elseif (Auth::user()->roleId == 5) {{-- Post Analytics --}}
                    <li><a href="{{ url('/accounts/postanalytics/post-analytics-listing') }}"><i class="bx bx-right-arrow-alt"></i>Post Analytics</a></li>
                @endif
            </ul>
        </li>
        
        @if (Auth::user()->roleId == 1) {{-- Only show these sections for Admin (roleId 1) --}}
            {{-- Admin Section --}}
            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class="lni lni-user"></i></div>
                    <div class="menu-title">Admin</div>
                </a>
                <ul>
                    <li><a href="{{ url('/accounts/user-profile') }}"><i class="bx bx-right-arrow-alt"></i>User Profile</a></li>
                    <li><a href="{{ url('/accounts/user-listing') }}"><i class="bx bx-right-arrow-alt"></i>User</a></li>
                    <li><a href="{{ url('/accounts/role/role-listing') }}"><i class="bx bx-right-arrow-alt"></i>Role</a></li>
                    <li><a href="{{ url('/accounts/languagescript/languagescript-listing') }}"><i class="bx bx-right-arrow-alt"></i>Language Script</a></li>
                    <li><a href="{{ url('/accounts/country/country-listing') }}"><i class="bx bx-right-arrow-alt"></i>Country</a></li>
                    <li><a href="{{ url('/accounts/livecity/live-city-listing') }}"><i class="bx bx-right-arrow-alt"></i>Live City</a></li>
                    <li><a href="{{ url('/accounts/scities/scities-listing') }}"><i class="bx bx-right-arrow-alt"></i>City</a></li>
                    <li><a href="{{ url('/accounts/region/region-listing') }}"><i class="bx bx-right-arrow-alt"></i>Region</a></li>
                </ul>
            </li>
        
            {{-- eCommerce Section --}}
            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class='bx bx-cart'></i></div>
                    <div class="menu-title">eCommerce</div>
                </a>
                <ul>
                    <li><a href="{{ url('/accounts/product/product-listing') }}"><i class="bx bx-right-arrow-alt"></i>Product</a></li>
                    <li><a href="{{ url('/accounts/productcategory/product-category-listing') }}"><i class="bx bx-right-arrow-alt"></i>Product Category</a></li>
                </ul>
            </li>

            {{-- Settings Section --}}
            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class="bx bx-cog"></i></div>
                    <div class="menu-title">Settings</div>
                </a>
                <ul>
                    <li><a href="settings-general.html"><i class="bx bx-right-arrow-alt"></i>General</a></li>
                    <li><a href="settings-security.html"><i class="bx bx-right-arrow-alt"></i>Security</a></li>
                </ul>
            </li>
        @endif
    @endif
</ul>
<!--end navigation-->
