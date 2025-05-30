<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Biometric Election Software</title>

    <link rel="stylesheet" href="{{asset('public/election/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{asset('public/election/css/index/index.css')}}" />
    <link rel="stylesheet" href="{{asset('public/election/css/vote-cast/categoryVoteTable.css')}}">

    <link rel="stylesheet" href="{{asset('public/admin/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body class="overflow-hidden">

    <div class="d-flex justify-content-center align-items-center h-100 vw-100 position-relative">
        <img src="{{asset('public/admin/images/logo.png')}}" class="deco deco-logo" alt="Logo">
        <img src="{{asset('public/election/assets/backround-left.png')}}" class="deco deco-tl" alt="">
        <img src="{{asset('public/election/assets/background.png')}}" class="deco deco-br" alt="">

        @yield('content')

    </div>

    <!-- Fingerprint Failed Modal -->
    <div class="modal fade" id="fingerFailModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content text-center p-4 border border-primary">
                <h4>Your biometric verification was not successful.</h4>
                <img src="{{asset('public/election/assets/cross.png')}}" alt="X"
                    style="width: 40px; margin: 1rem auto;">
                <h5 class="font-weight-light">آپ کی بایومیٹرک تصدیق نہیں ہوئی</h5>
            </div>
        </div>
    </div>

    <!-- Vote Confirmation Modal -->
    <div class="modal fade" id="confirmVoteModal" tabindex="-1" role="dialog" aria-labelledby="confirmVoteLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content text-center p-4">
                <h5 class="modal-title mb-3" id="confirmVoteLabel">Confirm Your Vote</h5>
                <p>Are you sure you want to vote for <strong id="confirm-candidate-name">this candidate</strong>?</p>
                <div class="mt-4 d-flex justify-content-center">
                    <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-green " id="confirm-vote-btn">Yes, Vote</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js"
        integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous">
    </script>

    <script src="{{asset('public/admin/plugins/sweetalert2/sweetalert2.min.js')}}"></script>

    @yield('scripts')

    <script src="{{asset('public/election/js/vote-cast/globals.js')}}"></script>
    {{-- <script src="{{asset('public/election/js/vote-cast/dummy-data/categories.js')}}"></script> --}}
    <script src="{{asset('public/election/js/vote-cast/categoryVoteTable.js')}}"></script>
    {{-- <script src="{{asset('public/election/js/vote-cast/reviewTable.js')}}"></script> --}}
    <script src="{{asset('public/election/js/vote-cast/formValidation.js')}}"></script>
    {{-- <script src="{{asset('public/election/js/vote-cast/index.js')}}"></script> --}}


</body>

</html>