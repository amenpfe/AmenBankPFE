<!doctype html>
<html class="fixed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<meta name="keywords" content="HTML5 Admin Template" />
		<meta name="description" content="Porto Admin - Responsive HTML5 Template">
		<meta name="author" content="okler.net">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- Web Fonts  -->
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

        <!-- Vendor CSS -->
        {!! HTML::style('css/bootstrap.css') !!}
        {!! HTML::style('css/font-awesome.css') !!}
        {!! HTML::style('css/magnific-popup.css') !!}
        {!! HTML::style('css/datepicker3.css') !!}
        <!-- specific page vendor css -->
            @yield('page-stylesheets')
        {!! HTML::style('css/theme.css') !!}
        {!! HTML::style('css/default.css') !!}
        {!! HTML::style('css/theme-custom.css') !!}
        <!-- Head Libs -->
        {!! HTML::script('js/modernizr.js') !!}
	</head>
	<body>
		<!-- start: page -->
			<section class="body-error error-outside">
				<div class="center-error">

					<div class="error-header">
						<div class="row">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-8">
										<a href="/" class="logo">
											<img src="{{asset('images/amen_logo.png')}}" height="54" alt="Porto Admin" />
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="main-error mb-xlg">
								<h2 class="error-code text-dark text-center text-semibold m-none">UNAUTHORIZED </i></h2>
								<p class="error-explanation text-center">We're sorry, but the page you were looking for is only accissible by {{$role}}</p>
							</div>
						</div>
					</div>
				</div>
			</section>
		<!-- end: page -->

		<!-- Vendor -->
        {!! HTML::script('js/jquery.js') !!}
        {!! HTML::script('js/jquery-browser-mobile.js') !!}
        {!! HTML::script('js/bootstrap.js') !!}
        {!! HTML::script('js/nanoscroller.js') !!}
        {!! HTML::script('js/bootstrap-datepicker.js') !!}
        {!! HTML::script('js/magnific-popup.js') !!}
        {!! HTML::script('js/jquery.placeholder.js') !!}
        {!! HTML::script('js/theme.js') !!}
        {!! HTML::script('js/theme.custom.js') !!}
        {!! HTML::script('js/theme.init.js') !!}

	</body>
</html>