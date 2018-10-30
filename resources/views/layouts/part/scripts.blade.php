<!--[if lt IE 9]>
<script src="/theme/metronic4/global/plugins/respond.min.js"></script>
<script src="/theme/metronic4/global/plugins/excanvas.min.js"></script>
<script src="/theme/metronic4/global/plugins/ie8.fix.min.js"></script>
<![endif]-->

<!-- BEGIN CORE PLUGINS -->

<!-- jQuery 3 -->
<script src="{{ asset('theme/AdminLTE/bower_components/jquery/dist/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('theme/AdminLTE/bower_components/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('theme/AdminLTE/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!-- Morris.js charts -->
<script src="{{ asset('theme/AdminLTE/bower_components/raphael/raphael.min.js')}}"></script>
<script src="{{ asset('theme/AdminLTE/bower_components/morris.js/morris.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{ asset('theme/AdminLTE/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js')}}"></script>
<!-- jvectormap -->
<script src="{{ asset('theme/AdminLTE/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>
<script src="{{ asset('theme/AdminLTE/plugins/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('theme/AdminLTE/bower_components/jquery-knob/dist/jquery.knob.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{ asset('theme/AdminLTE/bower_components/moment/min/moment.min.js')}}"></script>
<script src="{{ asset('theme/AdminLTE/bower_components/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<!-- datepicker -->
<script src="{{ asset('theme/AdminLTE/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{ asset('theme/AdminLTE/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}"></script>
<!-- Slimscroll -->
<script src="{{ asset('theme/AdminLTE/bower_components/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
<!-- FastClick -->
<script src="{{ asset('theme/AdminLTE/bower_components/fastclick/lib/fastclick.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('theme/AdminLTE/dist/js/adminlte.min.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset('theme/AdminLTE/dist/js/pages/dashboard.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<!-- <script src="/theme/AdminLTE/dist/js/demo.js"></script> -->
@section('core-plugins')
@show()
<!-- END CORE PLUGINS -->

<!-- BEGIN PAGE LEVEL PLUGINS -->
@section('page-level-plugins')
@show()
<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN CUSTOM PAGE LEVEL PLUGINS -->
@section('custom-page-level-plugins')
@show()
<!-- END CUSTOM PAGE LEVEL PLUGINS -->

<!-- BEGIN THEME GLOBAL SCRIPTS -->
@section('theme-global-scripts')
@show()
<!-- END THEME GLOBAL SCRIPTS -->

<!-- BEGIN PAGE LEVEL SCRIPTS -->
@section('page-level-scripts')
@show()
<!-- END PAGE LEVEL SCRIPTS -->

<!-- BEGIN THEME LAYOUT SCRIPTS -->

@section('theme-layout-scripts')
@show()
<!-- END THEME LAYOUT SCRIPTS -->

<!-- BEGIN PAGE SPECIFIC SCRIPTS -->
@section('page-specific-scripts')
@show()
<!-- END PAGE SPECIFIC SCRIPTS -->