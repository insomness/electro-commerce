<!-- HEADER -->
<header>
    <!-- TOP HEADER -->
    <div id="top-header">
        <div class="container">
            <ul class="header-links pull-left">
                <li><a href="#"><i class="fa fa-phone"></i> +628 5156 720 890</a></li>
                <li><a href="#"><i class="fa fa-envelope-o"></i> fitrahmaulana111@gmail.com</a></li>
                <li><a href="#"><i class="fa fa-map-marker"></i> Lambro bileu, Aceh Besar</a></li>
            </ul>
            <ul class="header-links pull-right">
                {{-- Account --}}
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                        <i class="fa fa-user-o"></i>
                        <span style="cursor: default">{{auth()->check() ? Auth::user()->first_name . ' ' . Auth::user()->last_name : 'My Account' }}</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        @guest
                        <li><a href="{{route('login')}}" style="color: #d10024" >Login</a></li>
                        <li><a href="{{route('register')}}" style="color: #d10024" >Register</a></li>
                        @else
                            <li>
                                <a  href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" style="color: #d10024">
                                    {{ __('Logout') }}
                                </a>
                            </li>
                            <li>
                                <a  href="{{ url('profiles') }}" style="color: #d10024">
                                    Profiles
                                </a>
                            </li>
                            @if(Auth::user()->hasRole('Admin'))
                            <li>
                                <a href="{{url('admin/products')}}" style="color: #d10024">Admin Dashboard</a>
                            </li>
                            @endif
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        @endguest
                    </ul>
                </li>
                {{-- EndAccount --}}
            </ul>
        </div>
    </div>
    <!-- /TOP HEADER -->

    <!-- MAIN HEADER -->
    <div id="header">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">
                <!-- LOGO -->
                <div class="col-md-9">
                    <div class="header-logo">
                        <a href="{{ url('/') }}" class="logo">
                            <img src="{{asset("themes/electro")}}/img/logo.png" alt="" style="object-fit: cover;height: 90px;">
                        </a>
                    </div>
                </div>
                <!-- /LOGO -->
                @include('themes.electro.partials.cart')
            </div>
            <!-- row -->
        </div>
        <!-- container -->
    </div>
    <!-- /MAIN HEADER -->
</header>
<!-- /HEADER -->
