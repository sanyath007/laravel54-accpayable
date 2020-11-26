<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Accounts Payable System</title>

	<link rel="stylesheet" href="{{ asset('/node_modules/bootstrap/dist/css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('/node_modules/select2/dist/css/select2.min.css') }}">
	<link rel="stylesheet" href="{{ asset('/node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
	<link rel="stylesheet" href="{{ asset('/node_modules/daterangepicker/daterangepicker.css') }}">
	<link rel="stylesheet" href="{{ asset('/node_modules/fullcalendar/dist/fullcalendar.min.css') }}">
	<link rel="stylesheet" href="{{ asset('/node_modules/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') }}">
	<link rel="stylesheet" href="{{ asset('/node_modules/ng-tags-input/build/ng-tags-input.min.css') }}">
	<link rel="stylesheet" href="{{ asset('/node_modules/AngularJS-Toaster/toaster.min.css') }}">
	<link rel="stylesheet" href="{{ asset('/node_modules/angular-xeditable/dist/css/xeditable.css') }}">
	<!-- Ionicons -->
  	<link rel="stylesheet" href="{{ asset('/css/ionicons.min.css') }}">
  	<!-- jvectormap -->
  	<link rel="stylesheet" href="{{ asset('/css/jquery-jvectormap.css') }}">
  	<!-- Theme style -->
  	<link rel="stylesheet" href="{{ asset('/css/AdminLTE.min.css') }}">
 	<!-- AdminLTE Skins. Choose a skin from the css/skins
    folder instead of downloading all of them to reduce the load. -->
  	<link rel="stylesheet" href="{{ asset('/css/skins/_all-skins.min.css') }}">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/balloon-css/0.5.0/balloon.min.css">
	<!-- Fonts -->
	<link rel='stylesheet' href='//fonts.googleapis.com/css?family=Roboto:400,300' type='text/css'>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<!-- Custom style -->
	<link rel="stylesheet" href="{{ asset('/css/main.css') }}">
	
	<!-- Scripts -->
	<script src="{{ asset('/node_modules/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('/node_modules/bootstrap/dist/js/bootstrap.min.js') }}"></script>
	<script src="{{ asset('/node_modules/angular/angular.min.js') }}"></script>
	<script src="{{ asset('/node_modules/moment/moment.js') }}"></script>
    <script src="{{ asset('/node_modules/fullcalendar/dist/fullcalendar.min.js') }}"></script>
    <script src="{{ asset('/node_modules/fullcalendar/dist/locale/th.js') }}"></script>
	<script src="{{ asset('/node_modules/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>
	<script src="{{ asset('/node_modules/ng-tags-input/build/ng-tags-input.min.js') }}"></script>
	<script src="{{ asset('/node_modules/angular-animate/angular-animate.min.js') }}"></script>
	<script src="{{ asset('/node_modules/AngularJS-Toaster/toaster.min.js') }}"></script>
	<script src="{{ asset('/node_modules/angular-xeditable/dist/js/xeditable.js') }}"></script>
	<script src="{{ asset('/node_modules/angular-modal-service/dst/angular-modal-service.min.js') }}"></script>
	<script src="{{ asset('/node_modules/underscore/underscore-min.js') }}"></script>
	<script src="{{ asset('/node_modules/jquery-ui-dist/jquery-ui.min.js') }}"></script>
	<script src="{{ asset('/node_modules/bootstrap3-wysihtml5-bower/dist/bootstrap3-wysihtml5.all.min.js') }}"></script>
	<script src="{{ asset('/node_modules/select2/dist/js/select2.full.min.js') }}"></script>
	<script src="{{ asset('/node_modules/daterangepicker/daterangepicker.js') }}"></script>
	<script src="{{ asset('/node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
	<script src="{{ asset('/js/bootstrap-datepicker-custom.js') }}"></script>
	<script src="{{ asset('/node_modules/bootstrap-datepicker/dist/locales/bootstrap-datepicker.th.min.js') }}"></script>
	<script src="{{ asset('/js/jquery.knob.min.js') }}"></script>
	<script src="{{ asset('/js/fastclick.js') }}"></script>
	<script src="{{ asset('/js/thaibath.js') }}"></script>
	<script src="https://code.highcharts.com/highcharts.js"></script>
	<script src="http://code.highcharts.com/highcharts-more.js"></script>
	<!-- AdminLTE App -->
	<script src="{{ asset('/js/adminlte.min.js') }}"></script>
	<!-- Environment Variable -->
	<script src="{{ asset('/js/env.js') }}"></script>
	<!-- AngularJS Components -->
	<script src="{{ asset('/js/main.js') }}"></script>
	
	<script src="{{ asset('/js/controllers/mainCtrl.js') }}"></script>
	<script src="{{ asset('/js/controllers/homeCtrl.js') }}"></script>
	<script src="{{ asset('/js/controllers/debtCtrl.js') }}"></script>
	<script src="{{ asset('/js/controllers/accountCtrl.js') }}"></script>
	<script src="{{ asset('/js/controllers/reportCtrl.js') }}"></script>
	<script src="{{ asset('/js/controllers/approveCtrl.js') }}"></script>
	<script src="{{ asset('/js/controllers/paymentCtrl.js') }}"></script>
	<script src="{{ asset('/js/controllers/creditorCtrl.js') }}"></script>
	<script src="{{ asset('/js/controllers/debttypeCtrl.js') }}"></script>
	<script src="{{ asset('/js/controllers/bankAccCtrl.js') }}"></script>

	<script src="{{ asset('/js/services/report.js') }}"></script>
	<script src="{{ asset('/js/services/stringFormat.js') }}"></script>
	<script src="{{ asset('/js/services/pagination.js') }}"></script>

	<!--<script src="{{ asset('/js/directives/highcharts.js') }}"></script>-->

</head>
<!-- For set collapse menu use .sidebar-collapse class in body tag -->
<body class="hold-transition skin-blue sidebar-collapse sidebar-mini" ng-app="app" ng-controller="mainCtrl">
	<div class="wrapper">

		@include('layouts.header')

		@include('layouts.sidebar')

		<!-- Content Wrapper. Contains page content -->
  		<div class="content-wrapper">

				@yield('content')

				<toaster-container></toaster-container>
				
		</div><!-- /.content-wrapper -->

	  	<!-- Footer -->
		@include('layouts.footer')
		<!-- Footer -->

		<!-- Control Sidebar -->
		@include('layouts.control-sidebar')
		<!-- /.control-sidebar -->

		<!-- Add the sidebar's background. This div must be placed
			immediately after the control sidebar -->
		<div class="control-sidebar-bg"></div>

	</div><!-- ./wrapper -->
</body>
</html>
