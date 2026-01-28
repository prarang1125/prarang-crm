<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<!-- Bootstrap JS -->
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<!--plugins-->
<script src="{{ asset('assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
<script src="{{ asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
<!-- Select2 (AFTER jQuery) -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
{{-- <script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script> --}}
{{-- <script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script> --}}
<script src="{{ asset('assets/plugins/chartjs/js/Chart.min.js') }}"></script>
<script src="{{ asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
<script src="{{ asset('assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery.easy-pie-chart/jquery.easypiechart.min.js') }}"></script>
<script src="{{ asset('assets/plugins/sparkline-charts/jquery.sparkline.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-knob/excanvas.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-knob/jquery.knob.js') }}"></script>
{{-- <script src="{{ asset('assets/plugins/ckeditor/ckeditor.js') }}"></script> --}}
{{-- <script src="{{ asset('assets/plugins/ckeditor/build-config.js') }}"></script> --}}
{{-- <script src="{{ asset('assets/plugins/ckeditor/config.js') }}"></script> --}}

<script src="{{ asset('assets/js/custom/ckeditor.js') }}"></script>

<script>
    $(function() {
        $(".knob").knob();
    });
</script>
<script src="{{ asset('assets/js/index.js') }}"></script>
<script>
    window.POST_SEARCH_URL = "{{ route('admin.search.posts') }}";
</script>
<!--app JS-->
<script src="{{ asset('assets/js/app.js') }}"></script>
<script src="{{ asset('assets/js/custom/login.js') }}"></script>
</body>

</html>
