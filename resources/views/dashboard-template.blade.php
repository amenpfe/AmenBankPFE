<!doctype html>
<html class="fixed">
<head>

    <!-- Basic -->
    <meta charset="UTF-8">

    <title>@yield('title')</title>
    <meta name="keywords" content="HTML5 Admin Template" />
    <meta name="description" content="Porto Admin - Responsive HTML5 Template">
    <meta name="author" content="okler.net">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
<section class="body">

    <!-- start: header -->
    <header class="header">
        <div class="logo-container">
            <a href="../" class="logo">
                <img src="{{ asset('images/amen_bank_logo.png')}}" height="40"  alt="JSOFT Admin" />
            </a>
            <div class="visible-xs toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
                <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
            </div>
        </div>

        <!-- start: search & user box -->
        <div class="header-right">

            

            <span class="separator"></span>

            <ul class="notifications">
                
                @if (Auth::user()->role != App\Enums\UserRole::byKey('Admin')->getValue()) {
                    <li>
                        <a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown">
                            <i class="fa fa-bell"></i>
                            <span class="badge" id="notifications-outer-badge">{{Count(Auth::user()->unreadNotifications)}}</span>
                        </a>
    
                        <div class="dropdown-menu notification-menu">
                            <div class="notification-title">
                                <span class="pull-right label label-default" id="notifications-inner-badge">{{Count(Auth::user()->unreadNotifications)}}</span>
                                Alerts
                            </div>
    
                            <div class="content">
                                    <ul id="notifications-container">
                                        <?php 

                                            $newRequestRouteName = trim(app()->view->getSections()['new-notification-route']); 
                                            $optRequestRouteName = trim(app()->view->getSections()['opt-notification-route']); 
                                        ?>
                                        @foreach (Auth::user()->unreadNotifications as $notification)
                                            <li>
                                                @php
                                                    $routeLink;
                                                    if($notification->data['projectRequest']['requestable_type'] == "App\\NewProjectRequest")
                                                        $routeLink = route($newRequestRouteName, $notification->data['projectRequest']['id']);
                                                    else
                                                        $routeLink = route($optRequestRouteName, $notification->data['projectRequest']['id']);
                                                @endphp
                                                <a href="{{$routeLink}}" class="clearfix">
                                                    {{$notification->data['projectRequest']['id']}}
                                                    <!--<div class="image">
                                                        <i class="fa fa-thumbs-down bg-danger"></i>
                                                    </div>
                                                    <span class="title">Server is Down!</span>
                                                    <span class="message">Just now</span>-->
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>

    
                                <hr />
    
                                <div class="text-right">
                                    <a href="#" class="view-more">View All</a>
                                </div>
                            </div>
                        </div>
                    </li>
                    
                }
                @endif
                </ul>

            <span class="separator"></span>

            <div id="userbox" class="userbox">
                <a href="#" data-toggle="dropdown">
                    <div class="profile-info" data-lock-name="John Doe" data-lock-email="johndoe@JSOFT.com">
                        <span class="name">{{Auth::user()->name}}</span>
                        <span class="role">{{\App\Enums\UserRole::getEnumDescriptionByValue(Auth::user()->role)}}</span>
                    </div>

                    <i class="fa custom-caret"></i>
                </a>

                <div class="dropdown-menu">
                    <ul class="list-unstyled">
                        <li class="divider"></li>
                        <li>
                            
                        </li><a href="@yield('edit')" role="menuitem" tabindex="-1"><span class="icon"><i class="fa fa-user"></span></i> Modifier le profil</a>
                       
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                {{ csrf_field() }}

                                <a role="menuitem" tabindex="-1" onclick="this.parentNode.submit()" style="cursor: pointer"><i class="fa fa-power-off"></i>Déconnexion</a>
                            </form>
                            <!--<a role="menuitem" tabindex="-1" href="{{-- route('logout') --}}"><i class="fa fa-power-off"></i> Logout</a>-->
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- end: search & user box -->
    </header>
    <div class="inner-wrapper">
        <!-- start: sidebar -->
        <aside id="sidebar-left" class="sidebar-left">

            <div class="sidebar-header">
                <div class="sidebar-title">
                   <b>Navigation</b>
                </div>
                <div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
                    <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
                </div>
            </div>

            <div class="nano">
                <div class="nano-content">
                    <nav id="menu" class="nav-main" role="navigation">
                        <ul class="nav nav-main">
                            @yield('navigation')
                        </ul>
                    </nav>

                    <hr class="separator" />



                </div>

            </div>

        </aside>      <!-- end: sidebar -->
        <section role="main" class="content-body">
                <header class="page-header">
                <h2> @yield('content-title')</h2>
                <div class="right-wrapper pull-right" style="margin-right: 10px;">
                    <ol class="breadcrumbs">
                        <li>
                            <a href="index.html">
                                <i class="fa fa-home"></i>
                            </a>
                        </li>
                        @yield('content-path')
                    </ol>
                </div>
            </header>
            @yield('content')
        </section>
    </div>
    
</section>
{!! HTML::script('js/jquery.js') !!}
{!! HTML::script('js/jquery-browser-mobile.js') !!}
{!! HTML::script('js/bootstrap.js') !!}
{!! HTML::script('js/nanoscroller.js') !!}
{!! HTML::script('js/bootstrap-datepicker.js') !!}
{!! HTML::script('js/magnific-popup.js') !!}
{!! HTML::script('js/jquery.placeholder.js') !!}
<!-- specific page vendor -->
@yield('page-scripts')
{!! HTML::script('js/theme.js') !!}
{!! HTML::script('js/theme.custom.js') !!}
{!! HTML::script('js/theme.init.js') !!}

                
@if (Auth::user()->role != App\Enums\UserRole::byKey('Admin')->getValue()) {
<script>
    newRouteLink = '{{route($newRequestRouteName, 0)}}';
    newRouteLink = newRouteLink.substring(0, newRouteLink.lastIndexOf("/")+1);

    optRouteLink = '{{route($optRequestRouteName, 0)}}';
    optRouteLink = optRouteLink.substring(0, optRouteLink.lastIndexOf("/")+1);

    console.log(newRouteLink);
    console.log(optRouteLink);
    window.Laravel = {!! json_encode(['csrfToken' => csrf_token()]) !!};
    window.Laravel.userId = {{Auth::user()->id}}
</script>
}
@endif
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>