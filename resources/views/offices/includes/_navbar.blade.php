<header class="main-header">

    {{--<!-- Logo -->--}}
    <a href="#" class="logo">
        {{--<!-- mini logo for sidebar mini 50x50 pixels -->--}}
        <span class="logo-mini">Hiring</span>
        <span class="logo-lg">Hiring</span>
    </a>

    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

                <!-- Messages: style can be found in dropdown.less-->
                {{-- <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-envelope-o"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <li><!-- start message -->
                                    <a href="#">
                                        <div class="pull-left">
                                            <img src="{{ asset('public/admin/theme2/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
                                        </div>
                                        <h4>
                                            Support Team
                                            <small>
                                                <i class="fa fa-clock-o"></i> 5 mins
                                            </small>
                                        </h4>
                                        <p>Why not buy a new awesome theme?</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="footer">
                            <a href="#">See All Messages</a>
                        </li>
                    </ul>
                </li> --}}

                {{--<!-- Notifications: style can be found in dropdown.less -->--}}
                {{-- <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        <span class="label label-warning">10</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You have 10 notifications</li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <li>
                                    <a href="#">
                                        <i class="fa fa-users text-aqua"></i> 5 new members joined today
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="footer">
                            <a href="#">View all</a>
                        </li>
                    </ul>
                </li> --}}

                <li class="dropdown tasks-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-flag-o"></i></a>
                    <ul class="dropdown-menu">
                        <li>
                            <ul class="menu">
                                <li>
                                    <a rel="alternate" hreflang="" href="#">
                                        users
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

                {{--<!-- User Account: style can be found in dropdown.less -->--}}
                <li class="dropdown user user-menu">

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"> 
                        <img src="{{auth('office')->user()->getImage()}}" class="user-image" alt="User Image">
                        <span class="hidden-xs">{{auth('office')->user()->username}}</span>
                    </a>
                    <ul class="dropdown-menu">

                        {{--<!-- User image -->--}}
                        <li class="user-header">
                            <img src="{{auth('office')->user()->getImage()}}" class="img-circle" alt="User Image">
                            <p>
                                {{auth('office')->user()->username}}
                            </p>
                        </li>

                        {{--<!-- Menu Footer-->--}}
                        <li class="user-footer">


                            <a href="{{url('offices/logout')}}" class="btn btn-default btn-flat">logout</a>

                            <form id="logout-form" action="#" method="POST" style="display: none;">
                                @csrf
                            </form>

                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

</header>