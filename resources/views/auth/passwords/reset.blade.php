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
						<h2 class="title text-uppercase text-bold m-none"><i class="fa fa-user mr-xs"></i> Sign In</h2>
					</div>
					<div class="panel-body">
                        <form class="" method="POST" action="{{ route('password.request') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} mb-lg">
                                <label for="email" class="control-label">E-Mail Address</label>
    
                                <div class="">
                                    <div class="input-group input-group-icon">
                                        <input id="email" type="email" class="form-control input-lg" name="email" value="{{ $email or old('email') }}" required autofocus>
                                        <span class="input-group-addon">
                                            <span class="icon icon-lg">
                                                <i class="fa fa-user"></i>
                                            </span>
                                        </span>
                                    </div>

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} mb-lg">
                                <label for="password" class="control-label">Password</label>
    
                                <div class="">
                                    <div class="input-group input-group-icon">
                                        <input id="password" type="password" class="form-control input-lg" name="password" required>
                                        <span class="input-group-addon">
                                            <span class="icon icon-lg">
                                                <i class="fa fa-lock"></i>
                                            </span>
                                        </span>
                                    </div>
                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }} mb-lg">
                                <label for="password-confirm" class="control-label">Confirm Password</label>
    
                                <div class="">
                                    <div class="input-group input-group-icon">
                                        <input id="password-confirm" type="password" class="form-control input-lg" name="password_confirmation" required>
                                        <span class="input-group-addon">
                                            <span class="icon icon-lg">
                                                <i class="fa fa-lock"></i>
                                            </span>
                                        </span>
                                    </div>
                                    @if ($errors->has('password_confirmation'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

							<div class="row">
								<div class="col-sm-5 col-sm-offset-7">
									<button type="submit" class="btn btn-primary hidden-xs col-sm-12">Reset Password</button>
									<button type="submit" class="btn btn-primary btn-block btn-lg visible-xs mt-lg">Reset Password</button>
								</div>
                            </div>
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
