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
        {{ HTML::style('css/bootstrap.css') }}
        {{ HTML::style('css/font-awesome.css') }}
        {{ HTML::style('css/magnific-popup.css') }}
        {{ HTML::style('css/datepicker3.css') }}
        {{ HTML::style('css/signin.css')}}


		<!-- Theme CSS -->
        {{ HTML::style('css/theme.css') }}

		<!-- Skin CSS -->
        {{ HTML::style('css/default.css') }}

		<!-- Theme Custom CSS -->
        {{ HTML::style('css/theme-custom.css') }}

		<!-- Head Libs -->
        {{ HTML::script('js/modernizr.js') }}

	</head>
	<body>
		<!-- start: page -->
		<section class="body-sign">
			<div class="center-sign">
				<a href="/" class="logo pull-left">
					<img src="{{asset('images/amen_bank_logo.png')}}" height="54" alt="Porto Admin" />
				</a>

				<div class="panel panel-sign">
					<div class="panel-title-sign mt-xl text-right">
						<h2 class="title text-uppercase text-bold m-none"><i class="fa fa-user mr-xs"></i> Recover Password</h2>
					</div>
					<div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @else
						    <div class="alert alert-info">
                                <p class="m-none text-semibold h6">Enter your e-mail below and we will send you reset instructions!</p>
                            </div>
                        @endif

						<form class="" method="POST" action="{{ route('password.email') }}">
                            {{ csrf_field() }}
							<div class="form-group mb-none{{ $errors->has('email') ? ' has-error' : '' }}">
								<div class="input-group">
                                    <input id="email" name="email" type="email" placeholder="E-mail" class="form-control input-lg" value="{{ old('email') }}" required/>
									<span class="input-group-btn">
										<button class="btn btn-primary btn-lg" type="submit">Reset!</button>
									</span>
                                </div>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
							</div>

							<p class="text-center mt-lg">Remembered? <a href="pages-signin.html">Sign In!</a>
						</form>
					</div>
				</div>

			</div>
		</section>
		<!-- end: page -->

		<!-- Vendor -->
        {{ HTML::script('js/jquery.js') }}
        {{ HTML::script('js/jquery.browser.mobile.js') }}
        {{ HTML::script('js/bootstrap.js') }}
        {{ HTML::script('js/nanoscroller.js') }}
        {{ HTML::script('js/bootstrap-datepicker.js') }}
        {{ HTML::script('js/magnific-popup.js') }}
        {{ HTML::script('js/jquery.placeholder.js') }}

		<!-- Theme Base, Components and Settings -->
        {{ HTML::script('js/theme.js') }}
		
		<!-- Theme Custom -->
        {{ HTML::script('js/theme.custom.js') }}
		
		<!-- Theme Initialization Files -->
        {{ HTML::script('js/theme.init.js') }}

	</body>
</html>
