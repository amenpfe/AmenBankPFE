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
    <!-- Vendor CSS -->
{!! HTML::style('css/signin.css') !!}

{!! HTML::style('css/bootstrap.css') !!}
{!! HTML::style('css/font-awesome.css') !!}
{!! HTML::style('css/magnific-popup.css') !!}
{!! HTML::style('css/datepicker3.css') !!}
{!! HTML::style('css/theme.css') !!}
{!! HTML::style('css/default.css') !!}
{!! HTML::style('css/theme-custom.css') !!}
<!-- Head Libs -->
    {!! HTML::script('js/modernizr.js') !!}
</head>
<body>
<!-- start: page -->
<section class="body-sign">
    <div class="center-sign">
        <a href="/" class="logo pull-left">
            <img src="{{ asset('images/amen_bank_logo.png')}}" height="54" alt="Porto Admin" />
        </a>

        <div class="panel panel-sign">
            <div class="panel-title-sign mt-xl text-right">
                <h2 class="title text-uppercase text-bold m-none"><i class="fa fa-user mr-xs"></i> Sign In</h2>
            </div>
            <div class="panel-body">
                <form action="index.html" method="post">
                    <div class="form-group mb-lg">
                        <label>Username</label>
                        <div class="input-group input-group-icon">
                            <input name="username" type="text" class="form-control input-lg" />
                            <span class="input-group-addon">
										<span class="icon icon-lg">
											<i class="fa fa-user"></i>
										</span>
									</span>
                        </div>
                    </div>

                    <div class="form-group mb-lg">
                        <div class="clearfix">
                            <label class="pull-left">mot de passe</label>
                            <a href="pages-recover-password.html" class="pull-right">Lost Password?</a>
                        </div>
                        <div class="input-group input-group-icon">
                            <input name="pwd" type="password" class="form-control input-lg" />
                            <span class="input-group-addon">
										<span class="icon icon-lg">
											<i class="fa fa-lock"></i>
										</span>
									</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-8">
                            <div class="checkbox-custom checkbox-default">
                                <input id="RememberMe" name="rememberme" type="checkbox"/>
                                <label for="RememberMe">Remember Me</label>
                            </div>
                        </div>
                        <div class="col-sm-4 text-right">
                            <button type="submit" class="btn btn-primary hidden-xs">Sign In</button>
                            <button type="submit" class="btn btn-primary btn-block btn-lg visible-xs mt-lg">Sign In</button>
                        </div>
                    </div>
                    <p class="text-center">Don't have an account yet? <a href="pages-signup.html">Sign Up!</a>

                </form>
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
{!! HTML::script('js/examples.dashboard.js') !!}

</body>
</html>