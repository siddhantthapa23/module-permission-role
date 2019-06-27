<header class="fixed ">
    <div class="main-header">
        <!-- Logo -->
        <a class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>MPR</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg">MPR&nbsp;CMS</span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">

                    <!-- User Account Menu -->
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- The user image in the navbar-->
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('uploads/users/' . auth()->user()->avatar) }}"  class="user-image" alt="{{ auth()->user()->full_name }}"> 
                            @else
                                <img src="{{ asset('images/default-avatar.png') }}" class="user-image" alt="User Image">
                            @endif
                            <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <span class="hidden-xs">{{ auth()->user()->full_name }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->
                            <li class="user-header">
                                @if(auth()->user()->avatar)
                                    <img src="{{ asset('uploads/users/' . auth()->user()->avatar) }}" class="img-circle" alt="{{ auth()->user()->full_name }}"> 
                                @else
                                    <img src="{{ asset('images/default-avatar.png') }}" class="img-circle" alt="User Image">
                                @endif
                                <p>
                                    {{ auth()->user()->full_name }}
                                    <small>Member since {{ auth()->user()->created_at }}</small>
                                </p>
                            </li>
                            <!-- Menu Body -->

                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-right">
                                    <a href="{{ route('logout') }}" class="btn btn-default btn-flat"
                                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </li>
                
                </ul>
            </div>
        </nav>
    </div>
</header>