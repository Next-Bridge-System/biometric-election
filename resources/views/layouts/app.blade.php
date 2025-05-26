<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('frontend.includes.head')
<link rel="stylesheet" href="{{asset('public/css/auth.css')}}">

<body>
    <div id="app">
        <main class="py-4">
            @yield('content')
        </main>
    </div>
    @include('frontend.includes.footer')

    @yield('scripts')

</body>

</html>
