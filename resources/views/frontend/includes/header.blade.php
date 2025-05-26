<nav class="navbar navbar-expand-lg navbar-dark bg-success shadow">
    <div class="container">
        <a class="navbar-brand" href="{{route('frontend.dashboard')}}">
            <img src="{{asset('public/admin/images/qsbu-white.png')}}" style="width:80px" alt="">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="col-md-6">
            <div class="collapse navbar-collapse float-right" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">

                    @if (Auth::guard('frontend')->check())
                    <li class="nav-item active">
                        <a class="nav-link" href="{{route('frontend.dashboard')}}"><i
                                class="fas fa-home mr-1 mr-1"></i>Dashboard
                            <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{Auth::guard('frontend')->user()->name}}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="#"><i class="fas fa-user mr-1 mr-1"></i>Profile</a>
                            <a class="dropdown-item" href="#"><i class="fas fa-cogs mr-1 mr-1"></i>Settings</a>
                            <div class="dropdown-divider"></div>
                            <form action="{{route('frontend.logout')}}" method="POST"> @csrf
                                <button class="dropdown-item" type="submit"><i
                                        class="fas fa-sign-out-alt mr-1"></i>Logout</button>
                            </form>
                        </div>
                    </li>
                    @else
                    <li class="nav-item active">
                        <a class="nav-link" href="{{route('frontend.dashboard')}}"><i
                                class="fas fa-sign-in-alt mr-1"></i>Login
                            <span class="sr-only">(current)</span></a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</nav>
@include('frontend.includes.logo')