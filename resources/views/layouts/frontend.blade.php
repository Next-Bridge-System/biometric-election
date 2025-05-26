<!doctype html>
<html lang="en">

@include('frontend.includes.head')

<body data-page="{{ Route::currentRouteName() }}">

    @if (Route::currentRouteName() == 'frontend.lawyer-request-verification' ||
    Route::currentRouteName() == 'frontend.lahore-bar-lawyers' || Route::currentRouteName() == 'frontend.highcourt-lawyers')
    @else
    @include('frontend.includes.header')
    @endif

    <main role="main">
        @yield('content')
    </main>

    @include('frontend.includes.footer')

    @if (Route::currentRouteName() != 'home')
    @component('components.image-view')@endcomponent
    @endif

    @yield('scripts')
    @livewireScripts

    <script>
        window.addEventListener('swal:modal', event => {
            swal.fire({
                title: event.detail.title,
                text: event.detail.text,
                icon: event.detail.type,
            });
        });

        function confirmation(listner = null, id = null, type = null,text) {
            if (listner && id) {
                    Swal.fire({
                    title: 'Are you sure?',
                    text: text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, I agree!'
                }).then((result) => {
                    if (result.value) {
                    if (type) {
                        window.livewire.emit(listner, id, type);
                    } else {
                        window.livewire.emit(listner, id);
                    }
                }
            });
        }
        }
    </script>
</body>

</html>