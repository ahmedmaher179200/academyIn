<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 2.4.0
    </div>
    <strong>Copyright &copy; 2022
        <a href="http://algorithmltd.com/" target="_blank">ahmed</a>.</strong> All rights
    reserved.
</footer>



</div><!-- end of wrapper -->

{{-- <!-- Bootstrap 3.3.7 --> --}}
<script src="{{ asset('public/admin/theme2/js/bootstrap.min.js') }}"></script>

{{-- icheck --}}
<script src="{{ asset('public/admin/theme2/plugins/icheck/icheck.min.js') }}"></script>

{{-- <!-- FastClick --> --}}
<script src="{{ asset('public/admin/theme2/js/fastclick.js') }}"></script>

{{-- <!-- AdminLTE App --> --}}
<script src="{{ asset('public/admin/theme2/js/adminlte.min.js') }}"></script>

{{-- <!-- Jqurey Number --> --}}
<script src="{{ asset('public/admin/theme2/js/jquery.number.min.js') }}"></script>

{{-- <!-- Jqurey Print_this --> --}}
<script src="{{ asset('public/admin/theme2/js/printThis.js') }}"></script>


{{-- <!-- CKEditor App --> --}}
<script src="{{ asset('public/admin/theme2/plugins/ckeditor/ckeditor.js') }}"></script>

{{-- morris --}}
<script src="{{ asset('public/admin/theme2/plugins/morris/raphael-min.js') }}"></script>
<script src="{{ asset('public/admin/theme2/plugins/morris/morris.min.js') }}"></script>

{{-- custom js --}}
<script src="{{ asset('public/admin/theme2/js/custom/order.js') }}"></script>
@yield('scripts')

<script>
    $(document).ready(function() {
        $('.sidebar-menu').tree();

        //icheck
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        });

        // delete Noty
        $('.delete').click(function(e) {

            var that = $(this)

            e.preventDefault();

            var n = new Noty({
                text: "@lang('site.confirm_delete')",
                type: "warning",
                killer: true,
                buttons: [
                    Noty.button("@lang('site.yes')", 'btn btn-success mr-2',
                function() {
                        that.closest('form').submit();
                    }),

                    Noty.button("@lang('site.no')", 'btn btn-primary mr-2', function() {
                        n.close();
                    })
                ]
            });

            n.show();

        }); //end of delete


        //image Preview
        $(".image").change(function() {

            if (this.files && this.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('.image-preview').attr('src', e.target.result);
                }

                reader.readAsDataURL(this.files[0]);
            }
        });

        CKEDITOR.config.language = "{{ app()->getLocale() }}";
    });  

</script>
@stack('script')

<script src="//cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready( function () {
            $('#myTable').DataTable();
        } );
    </script>

</body>

</html>
