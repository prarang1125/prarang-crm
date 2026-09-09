<div class="sidebar-header">
    <div>
        <img src="{{ asset('assets/images/logo.png') }}" class="logo-icon" alt="logo icon">
    </div>
    <div>
        <h4 class="logo-text">PRARANG</h4>
    </div>
    <div class="ms-auto toggle-icon">
        {{-- Optional toggle icon --}}
    </div>
</div>
<!--navigation-->
<ul class="metismenu" id="menu">
    @if (Auth::guard('admin')->check())
    {{-- Admin Sidebar --}}
    <li class="mm-active">
        <a href="javascript:void(0)" class="has-arrow active">
            <div class="parent-icon active"><i class='bx bx-home-circle'></i></div>
            <div class="menu-title">Dashboard</div>
        </a>
        <ul>
            <li> <a href="{{ url('/admin/dashboard') }}"><i class="bx-right-arrow-alt bx"></i>Home</a>
            </li>
            <li> <a href="{{ url('/admin/maker/maker-listing') }}"><i class="bx-right-arrow-alt bx"></i>Maker</a>
            </li>
            <li> <a href="{{ url('/admin/checker/checker-listing') }}"><i
                        class="bx-right-arrow-alt bx"></i>Checker</a>
            </li>
            <li> <a href="{{ url('/admin/uploader/uploader-listing') }}"><i
                        class="bx-right-arrow-alt bx"></i>Uploader</a>
            </li>

            <!-- Sudhanshu -->
            <li>
                <a href="{{ url('ads/ad-listing') }}">
                    <i class="bx-right-arrow-alt bx"></i>Ads Uploads</a>
            </li>
              <!-- Sudhanshu -->
             <li>
                <a href="{{ url('admin/portal-ads') }}">
                    <i class="bx-right-arrow-alt bx"></i>Portal Ads</a>
            </li>
          




            <li> <a href="{{ url('/admin/postanalyticsmaker/post-analytics-maker-city-listing') }}"><i
                        class="bx-right-arrow-alt bx"></i>Post Analytics Maker</a>
            </li>
            <li> <a href="{{ url('/admin/postanalyticschecker/post-analytics-checker-city-listing') }}"><i
                        class="bx-right-arrow-alt bx"></i>Post Analytics Checker</a>
            </li>
            <li> <a href="{{ url('/admin/postanalytics/post-analytics-listing') }}"><i
                        class="bx-right-arrow-alt bx"></i>Post Analytics</a>
            </li>
            <li> <a href="{{route('content.post-listing')}}"><i class="bx-right-arrow-alt bx"></i>Posts Filter</a>
            </li>
            <li> <a href="/subscribers"><i class="bx bx-user"></i>Subscribers</a>
            </li>
            <li> <a href="{{ route('visitor') }}"><i class="bx bx-user"></i>Visitors</a>
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
            <li> <a href="{{ url('/admin/user-profile') }}"><i class="bx-right-arrow-alt bx"></i>User Profile</a>
            </li>
            <li> <a href="{{ url('/admin/user-listing') }}"><i class="bx-right-arrow-alt bx"></i>User</a>
            <li> <a href="{{ route('our-team.index') }}"><i class="bx-right-arrow-alt bx"></i>Our Teams</a>
            </li>
    </li>
    <li> <a href="{{ url('/admin/role/role-listing') }}"><i class="bx-right-arrow-alt bx"></i>Role</a>
    </li>
    <li> <a href="{{ url('/admin/languagescript/languagescript-listing') }}"><i
                class="bx-right-arrow-alt bx"></i>Language Script</a>
    </li>
    <li> <a href="{{ url('/admin/country/country-listing') }}"><i class="bx-right-arrow-alt bx"></i>Country</a>
    </li>
    <li> <a href="{{ url('/admin/livecity/live-city-listing') }}"><i class="bx-right-arrow-alt bx"></i>Live
            City</a>
    </li>
    <li> <a href="{{ url('/admin/scities/scities-listing') }}"><i class="bx-right-arrow-alt bx"></i>City</a>
    </li>
    <li> <a href="{{ url('/admin/region/region-listing') }}"><i class="bx-right-arrow-alt bx"></i>Region</a>
        {{-- Portal: Vivek --}}
    <li> <a href="{{ route('portal.index') }}"><i class="bx-right-arrow-alt bx"></i>Portals</a>
    </li>
    {{-- End Portal:Vivek --}}
    </li>
    <li> <a href="{{ url('/admin/tagcategory/tag-category-listing') }}"><i class="bx-right-arrow-alt bx"></i>Tag
            Category</a>
    </li>
    <li> <a href="{{ url('/admin/tag/tag-listing') }}"><i class="bx-right-arrow-alt bx"></i>Tag</a>
    </li>
    <li> <a href="{{ url('/admin/usercountry/user-country-listing') }}"><i class="bx-right-arrow-alt bx"></i>User
            Country</a>
    </li>
    <li> <a href="{{ url('/admin/usercity/user-city-listing') }}"><i class="bx-right-arrow-alt bx"></i>User
            City</a>
    </li>
    <li> <a href="{{ url('/admin/post/post-listing') }}"><i class="bx-right-arrow-alt bx"></i>Post</a>
    </li>
    <li> <a href="{{ url('/admin/deleted-post/deleted-post-listing') }}"><i
                class="bx-right-arrow-alt bx"></i>Deleted Post</a>
    </li>
</ul>
</li>
@elseif (Auth::check())
{{-- Other Roles Sidebar --}}
<li class="mm-active">
    <a href="javascript:;" class="has-arrow">
        <div class="parent-icon"><i class='bx bx-home-circle'></i></div>
        <div class="menu-title">Dashboard</div>
    </a>
    <ul>
        @if (Auth::user()->roleId == 2 || Auth::user()->roleId == 13 || Auth::user()->roleId == 14)
        <li>
            <a href="{{ url('/accounts/maker/dashboard') }}">
                <i class="bx-right-arrow-alt bx"></i> Maker
            </a>
        </li>
        @endif
        @if (Auth::user()->roleId == 3 || Auth::user()->roleId == 14)
        <li> <a href="{{ url('/accounts/checker/dashboard') }}"><i class="bx-right-arrow-alt bx"></i>Checker</a>
            @endif
            @if (Auth::user()->roleId == 4 || Auth::user()->roleId == 13 || Auth::user()->roleId == 14)
        <li> <a href="{{ url('/accounts/uploader/dashboard') }}"><i class="bx-right-arrow-alt bx"></i>Uploader</a></li>
        @endif
        @if (Auth::user()->roleId == 6 )
        <li> <a href="{{ url('/accounts/postanalyticsmaker/acc-post-analytics-maker-city-listing') }}"><i
                    class="bx-right-arrow-alt bx"></i>Analytics Maker</a>
            @endif
            @if (Auth::user()->roleId == 7 )
        <li> <a href="{{ url('/accounts/postanalyticschecker/acc-post-analytics-checker-city-listing') }}"><i
                    class="bx-right-arrow-alt bx"></i>Analytics Checker</a>
            @endif
    </ul>
</li>
@endif
</ul>
<!--end navigation-->