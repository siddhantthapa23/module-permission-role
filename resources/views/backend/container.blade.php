<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<!-- Main Head -->
@include('backend.head')

@yield('head_css')

<body class="hold-transition skin-blue sidebar-mini">

    <div class="wrapper">

        <!-- Main Header -->
        @include('backend.header')

        @include('backend.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->

            <!-- Main content -->
            <section class="content container-fluid"  style="margin-top: 35px;">

            <!--------------------------
                | Your Page Content Here |
                -------------------------->
                <div class="content">

                    @yield('content')
                
                </div>

            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        @include('backend.footer')

    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED JS SCRIPTS -->
    @include('backend.foot')

    <!-- Datatable Initialise -->
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>

    @yield('foot_js')

    <script type="text/javascript">
        $(function () {
            // Initialize datetimepicker Element
            $('#datetimepicker').datetimepicker({
                format: 'YYYY-MM-DD HH:mm',
            });

            // Initialize Select2 Element
            $('.select2').select2({
                placeholder: "Select",
                allowClear: true
            });
        });
    </script>

</body>
</html>