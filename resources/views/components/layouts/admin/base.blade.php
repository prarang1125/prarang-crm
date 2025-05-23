@include('layouts.partial.head')
<!-- header -->
@include('layouts.partial.navbar')
<div class="wrapper">
    <!--sidebar wrapper -->
    <div class="sidebar-wrapper" data-simplebar="true">
        @include('layouts.partial.sidebar')
    </div>
    <!--end sidebar wrapper -->
    <div class="page-wrapper">
       {{$slot}}
        @include('layouts.partial.footer')
        @livewireScripts
    </div>
</div>
@include('layouts.partial.script')
