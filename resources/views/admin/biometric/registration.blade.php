@extends('layouts.admin')

@section('styles')
<link rel="stylesheet" href="{{asset('public/biometric/css/index/index.css')}}">
<link rel="stylesheet" href="{{asset('public/biometric/css/index/finger.css')}}">
@endsection

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Fingerprint Registration</h1>
            </div>
            <div class="col-sm-6">
                <h6 class="breadcrumb float-sm-right">
                    <a href="#">Back</a>
                </h6>
            </div>
            <div class="col-md-12">

            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- jquery validation -->
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">
                            Biometric Registration "{{$user->name}}" "{{$user->cnic_no}}" 
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <div class="card-body">

                        <div class="">
                            <!-- Decorations -->
                            <img src="{{asset('public/biometric/assets/background-left.png')}}" class="deco deco-tl"
                                alt="">
                            <img src="{{asset('public/biometric/assets/background.png')}}" class="deco deco-br" alt="">

                            <div class="card p-4 stepper-card">
                                <!-- Stepper -->
                                <div class="d-flex align-items-center stepper mb-4">
                                    <div class="step active">1</div>
                                    <div class="line mx-2"></div>
                                    <div class="step">2</div>
                                    <div class="line mx-2"></div>
                                    <div class="step">3</div>
                                </div>

                                <!-- Steps Container -->
                                <div id="form-steps">

                                    <div class="form-step" data-step="1" id="step1">
                                        <h5 class="mb-3">Capturing Image</h5>

                                        <div class="camera-container text-center mb-3">
                                            <video id="camera-stream" width="300" height="225" autoplay
                                                class="border rounded"></video>

                                            <canvas id="capture-canvas" width="300" height="225"
                                                class="d-none border rounded"></canvas>

                                            @if ($user->webcam_image_path)
                                            <img src="{{asset('storage/app/public/'.$user->webcam_image_path)}}" alt=""
                                                style="border: 2px solid #28a745; width: 100px; height: 75px; object-fit: cover; border-radius: 4px;">
                                            @endif

                                            <div class="mt-2">
                                                <button type="button" id="capture-btn" class="btn btn-success mr-2"><i
                                                        class="fas fa-camera mr-1"></i> Capture</button>
                                                <button type="button" id="retake-btn"
                                                    class="btn btn-secondary d-none"><i
                                                        class="fas fa-sync mr-1"></i>Retake</button>
                                            </div>

                                            <!-- error when trying to Next without capture -->
                                            <div id="camera-error" class="text-danger d-none mt-2">
                                                Please capture your image before proceeding.
                                            </div>
                                        </div>


                                        <button class="btn btn-green btn-block next-btn">Next</button>

                                    </div>

                                    <div class="form-step d-none" data-step="2">
                                        <b class="mb-3 text-danger" id="error-message">Select any finger to scan and
                                            start the biometric Process.</b>
                                        <div class="">
                                            @php
                                            $titles = [
                                            1 => 'Left Little Finger',
                                            2 => 'Left Ring Finger',
                                            3 => 'Left Middle Finger',
                                            4 => 'Left Index Finger',
                                            5 => 'Left Thumb',
                                            6 => 'Right Thumb',
                                            7 => 'Right Index Finger',
                                            8 => 'Right Middle Finger',
                                            9 => 'Right Ring Finger',
                                            10 => 'Right Little Finger',
                                            ];
                                            // Get saved finger ids for this user
                                            $savedFingers = \DB::table('biometrics')->where('user_id',
                                            $user->id)->pluck('finger_id')->toArray();
                                            @endphp

                                            @for ($i = 1; $i <= 10; $i++) @php $isSaved=in_array($i, $savedFingers);
                                                @endphp <button data-toggle="tooltip" data-placement="top"
                                                title="{{ $titles[$i] }}" type="button"
                                                class="btn finger finger_{{ $i }} {{ $isSaved ? 'btn-success' : '' }}"
                                                finger-id="{{ $i }}"
                                                onclick="CallSGIFPGetData(SuccessFunc1, ErrorFunc)">
                                                </button>
                                                @endfor

                                                @include('admin.biometric.hands')
                                        </div>
                                        <div class="position-relative text-center mb-3">
                                            <img border="2" id="FPImage1" alt="Fingerprint-1" style="width:60px;"
                                                src="{{asset('public/biometric/assets/FingerScan.png')}}">
                                            <img border="2" id="FPImage2" alt="Fingerprint-2" style="width:60px;"
                                                src="{{asset('public/biometric/assets/FingerScan.png')}}"> <br>
                                        </div>

                                        <div id="selected-finger" class="text-primary text-center  mb-2"></div>

                                        <div id="finger-error" class="text-danger  text-center d-none mt-2  d-none">
                                            Please select a finger.
                                        </div>

                                        <button id="finish-btn" onclick="matchScore(succMatch, failureFunc)"
                                            class="btn  btn-green btn-block next-btn">Next</button>
                                    </div>

                                    <div class="form-step d-none w-100" data-step="3">
                                        <div class="receipt-wrapper mx-auto">
                                            <!-- “Printer head” -->
                                            <div class="receipt-printer"></div>

                                            <!-- “Receipt paper” -->
                                            <div class="receipt-card text-center">
                                                <h5 class="mb-2">Receipt</h5>
                                                <p class="mb-1">
                                                    <strong>Token:</strong>
                                                    <span id="out-email">{{$user->id}}</span>
                                                </p>
                                                <p class="mb-3">
                                                    <strong>CNIC :</strong>
                                                    <span id="out-cnic">{{$user->cnic_no}}</span>
                                                </p>

                                                <!-- big green check -->
                                                <div class="mb-3">
                                                    <div class="btn-circle btn-success">
                                                        &#10003;
                                                    </div>
                                                </div>

                                                <p>Your entry has been successfully submitted.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!--/.col (left) -->
            <!-- right column -->
            <div class="col-md-6">

            </div>
            <!--/.col (right) -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

@endsection

@section('scripts')

<script src="{{asset('public/biometric/js/index/index.js')}}"></script>
<script src="{{asset('public/biometric/js/index/finger.js')}}"></script>
{{-- <script src="{{asset('public/biometric/js/index/form-validation.js')}}"></script> --}}
<script src="{{asset('public/biometric/js/index/camera.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
    integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
</script>

<script type="text/javascript">
    const cameraImageUploadURL = "{{ route('biometrics.uploadCameraImage') }}";
    const userID = "{{ $user->id }}";
    const fingerVerification = false;
    const webcamImagePath = "{{ $user->webcam_image_path }}";
    const getBiometricCountUrl = "{{ route('biometrics.getBiometricCount') }}";

    var template_1 = "";
    var template_2 = "";
    var result_1 = "";
    var result_2 = "";
    var finger_id = "";
    var finger_name = "";

    function SuccessFunc1(result) {
        if (result.ErrorCode == 0) {
            if (result != null && result.BMPBase64.length > 0) {
                document.getElementById('FPImage1').src = "data:image/bmp;base64," + result.BMPBase64;
            }
            template_1 = result.TemplateBase64;
            result_1 = result;

            CallSGIFPGetData(SuccessFunc2, ErrorFunc)
        }
        else {
            var error_message = document.getElementById('error-message');
            error_message.textContent = "Device Scanner Turned Off. Please click the finger to restart the scanning process.";

           swal.fire({
                title: 'Device Scanner Turned Off',
                text: 'Please click the finger to restart the scanning process.',
                icon: 'error',
            });
        }
    }

    function SuccessFunc2(result) {
        if (result.ErrorCode == 0) {
            if (result != null && result.BMPBase64.length > 0) {
                document.getElementById('FPImage2').src = "data:image/bmp;base64," + result.BMPBase64;
            }
            template_2 = result.TemplateBase64;
            result_2 = result;
        }
        else {

            var error_message = document.getElementById('error-message');
            error_message.textContent = "Device Scanner Turned Off. Please click the finger to restart the scanning process.";

           swal.fire({
                title: 'Device Scanner Turned Off',
                text: 'Please click the finger to restart the scanning process.',
                icon: 'error',
            });
        }
    }

    function ErrorFunc(status) {
        swal.fire({
            title: 'Biometric Device Connection Error',
            text: 'Unable to connect to the biometric device. Please ensure the device is properly connected and try again. Contact support if the problem continues.',
            icon: 'error',
        });
        console.error("Error connecting to SGI_BIO_SRV: " + status);
    }

    function CallSGIFPGetData(successCall, failCall) {

        var error_message = document.getElementById('error-message');
        error_message.textContent = "Scan Process Started ...";
        
        var uri = "https://localhost:8443/SGIFPCapture";
        var XML_HTTP = new XMLHttpRequest();
        XML_HTTP.onreadystatechange = function () {
            if (XML_HTTP.readyState == 4 && XML_HTTP.status == 200) {
                FP_OBJECT = JSON.parse(XML_HTTP.responseText);
                successCall(FP_OBJECT);
            }
            else if (XML_HTTP.status == 404) {
                failCall(XML_HTTP.status)
            }
        }
        XML_HTTP.onerror = function () {
            failCall(XML_HTTP.status);
        }
        var params = "Timeout=" + "10000";
        params += "&Quality=" + "50";
        // params += "&licstr=" + encodeURIComponent(secugen_lic);
        params += "&templateFormat=" + "ISO";
        XML_HTTP.open("POST", uri, true);
        XML_HTTP.send(params);
    }

    function matchScore(succFunction, failFunction) {

        if (finger_id == "") {
            swal.fire({
                title: 'Finger Selection Required',
                text: 'Please select a finger before proceeding.',
                icon: 'warning',
            });
            return;
        }

        if (template_1 == "" || template_2 == "") {
            swal.fire({
                title: 'Fingerprint Scan Required',
                text: 'Please scan the selected finger twice to proceed with verification.',
                icon: 'warning',
            });
            return;
        }
        
        var uri = "https://localhost:8443/SGIMatchScore";

        var XML_HTTP = new XMLHttpRequest();
        XML_HTTP.onreadystatechange = function () {
            if (XML_HTTP.readyState == 4 && XML_HTTP.status == 200) {
                FP_OBJECT = JSON.parse(XML_HTTP.responseText);
                succFunction(FP_OBJECT);
            }
            else if (XML_HTTP.status == 404) {
                failFunction(XML_HTTP.status)
            }
        }

        XML_HTTP.onerror = function () {
            failFunction(XML_HTTP.status);
        }
        var params = "template1=" + encodeURIComponent(template_1);
        params += "&template2=" + encodeURIComponent(template_2);
        // params += "&licstr=" + encodeURIComponent(secugen_lic);
        params += "&templateFormat=" + "ISO";
        XML_HTTP.open("POST", uri, false);
        XML_HTTP.send(params);
    }

    function succMatch(result) {

        var idQuality = 100;
        if (result.ErrorCode == 0) {
            if (result.MatchingScore >= idQuality) {
                $.ajax({
                    method: "POST",
                    url: '{{route('biometrics.store')}}',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        'finger_id': finger_id,
                        'finger_name': finger_name,
                        'user_id': {{ $user->id }},
                        'result': result,
                        'template_1': template_1,
                        'template_2': template_2,
                        'result_1': result_1,
                        'result_2': result_2,
                    },
                    success: function (response) {
                        const savedFingerId = response.finger_id;
                        const $button = $(`.finger[finger-id="${savedFingerId}"]`);
                        $button.addClass('btn-success');
                        $button.prop('disabled', true);
                        
                        if (response.biometric_count > 1) {
                            window.stepper.goToStep(3);
                        } else {
                            window.stepper.goToStep(2);

                            swal.fire({
                                title: 'Matching Score: '+ result.MatchingScore ,
                                text: 'The first fingerprint was registered and verified successfully.',
                                icon: 'success',
                            });

                            var error_message = document.getElementById('error-message');
                            error_message.textContent = "";

                           resetFPImages();
                        }

                    },
                });

                
            } else {
                resetFPImages();
                swal.fire({
                    title: 'No Match',
                    text: 'The selected finger did not match. Please try again. Matching score: ' + result.MatchingScore,
                    icon: 'error',
                });
            }
        }
        else {
            alert("Error Scanning Fingerprint ErrorCode = " + result.ErrorCode);
        }
    }

    function failureFunc(error) {
        alert("On Match Process, failure has been called");
    }

    function resetFPImages() {
        document.getElementById('FPImage1').src = "{{asset('public/biometric/assets/FingerScan.png')}}";
        document.getElementById('FPImage2').src = "{{asset('public/biometric/assets/FingerScan.png')}}";
    }

</script>
@endsection