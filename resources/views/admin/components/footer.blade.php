<!-- /.content-wrapper -->
<footer class="main-footer">
    <strong>Copyright <?php echo date('Y'); ?> by <a href="https://news-portal.shop/">Linh Dev</a>.</strong>
    All rights reserved
</footer>
<input type="hidden" class="demo" value="{{ url('/') }}"></input>
<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>

<!-- Bootstrap 4 -->
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('assets/js/adminlte.min.js') }}"></script>
<script src="{{ asset('assets/js/summernote-bs4.min.js') }}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="{{ asset('assets/js/image-uploader.js') }}"></script>
<script src="{{ asset('assets/js/sweetalert2.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/js/additional-methods.min.js') }}"></script>
<script src="{{ asset('assets/js/main_ajax.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();

        $('#summernote').summernote({
            height: 200,
        });

        $(function() {

            $('input[name="datefilter"]').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });

            $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate
                    .format('MM/DD/YYYY'));
            });

            $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

        });

        $(function() {
            $('input[name="datetimes"]').daterangepicker({
                timePicker: true,
                locale: {
                    format: 'M/DD/Y hh:mm A',
                }
            });
        });

    });
</script>
@yield('pageJsScripts')
</body>

</html>
