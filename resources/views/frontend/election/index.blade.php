@extends('layouts.election')

@section('content')
<!-- Main Card -->
<div class="card p-4 stepper-card text-center" id="vote-card">

    <!-- Screens -->
    <div id="vote-steps">

        <div class="vote-step" data-step="0">
             <h1 class=" text-center font-weight-bold">Welcome to</h4>
        <h3 class="">Biometric Election System</h3>
          <h4>بائیو میٹرک  الیکشن سسٹم </h4>
            <img src="{{asset('public/election/assets/fingerprint.png')}}" class="my-5" alt="" style="width: 200px;">
        <div class="d-flex justify-content-end">
                <button class="btn btn-green next-btn w-100 bigbtn">
  Get Started | <small>شروع کریں</small>
</button>
        </div>
        </div>

        <div class="vote-step d-none" data-step="1">
             <h3 class="mb-3 text-center font-weight-bold">Scan your CNIC card</h3>
        <h5>اپنا شناختی کارڈ سکین کریں </h5>
       
            <input id="cnic-scan" type="text" class="form-control my-5" placeholder="123412345671" value="" autocomplete="off" autofocus>
            <div id="cnic-error" class="text-danger d-none my-3">براہ کرم درست CNIC درج کریں۔</div>
            <div class="text-center mb-3">
                <img src="{{asset('public/election/assets/QR.png')}}" alt="Scan QR" class="img-fluid"
                    style="max-width:200px;">
            </div>
            {{-- <div class="d-flex justify-content-end">
         <button class="btn btn-green next-btn w-100">
  Get Started | <small>شروع کریں</small>
</button>


            </div> --}}
        </div>

        <div class="vote-step d-none" data-step="2">
            <h3 class="mb-3 font-weight-bold">Scan your Fingerprint</h4>
            <h5 class="mb-4">اپنی بایومیٹرک تصدیق کریں</h5>

            <div class="d-flex justify-content-center align-items-center flex-column">
                <img id="FPImage1" src="{{asset('public/election/assets/FingerScan.png')}}" alt="Fingerprint"
                    style=" margin-bottom: 1rem;">
            </div>

            {{-- <div class="d-flex justify-content-between mt-4">
                <button class="btn btn-secondary back-btn"><i class="fa-solid fa-arrow-left"></i></button>
                <button class="btn btn-blue next-btn"><i class="fa-solid fa-arrow-right"></i></button>
            </div> --}}
        </div>

        <div class="vote-step d-none" data-step="3">
           <h3 class="mb-3 text-center font-weight-bold">Voter Information</h3>
            <h5 class="mb-4">ووٹر کی تفصیل</h5>

            <div class=" p-3 rounded text-left bg-white position-relative">
                <div class="row align-items-center">
                    <div class="col-sm-7">
                        <p class="mb-1"><strong>Name:</strong> <span id="voter-name"></span></p>
                        <p class="mb-1"><strong>CNIC:</strong> <span id="voter-cnic"></span></p>
                        <p class="mb-1"><strong>Voter ID:</strong> <span id="voter-id"></span></p>
                        <h6 class="mt-3 mb-0 text-success">Biometric verification has been done.</h6>
                        <p class="mb-0">آپ کی بایومیٹرک تصدیق ہو گئی ہے</p>
                    </div>
                    <div class="col-sm-5 rounded text-center shadow ">
                        <img id="voter-photo" src="" alt="Voter Photo" class="img-fluid rounded"
                            style="max-width: 180px;">
                        <div class="btn-circle btn-success mt-2"><i class="fa-solid fa-check"></i></div>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-4">
                    {{-- <button class="btn btn-secondary back-btn"><i class="fa-solid fa-arrow-left"></i></button> --}}
                    <button class="btn btn-green next-btn w-100 bigbtn" >
  Next | <small>اگلا قدم</small>
</button>

                    
                </div>

            </div>
        </div>

        <!-- Step 6: Multi-Category Voting Table -->
        <div class="vote-step d-none" data-step="4">
           <h3 class="mb-3 text-center font-weight-bold">Select Candidate Category</h4>
            <h5 class="mb-4">امیدوار کی قسم منتخب کریں</h5>

            <div class="table-responsive">
                <table class="table text-center" id="category-vote-table">
                    <tbody></tbody>
                </table>
            </div>

            <button class="btn btn-blue mt-3 next-btn bigbtn" style="padding:18px;" id="submit-multi-vote">Submit | <span class="ml-1">جمع
                    کروائیں</span></button>
        </div>

        {{-- <div class="container mt-4 vote-step d-none" data-step="5">
            <h4 class="text-center font-weight-bold mb-2">You have voted for these candidates.</h4>
            <h5 class="text-center text-muted mb-4">آپ نے ان امیدواروں کو ووٹ کر چکے ہیں</h5>

            <table class="table table-bordered text-center" id="final-vote-table">
                <tbody></tbody>
            </table>

            <div class="text-center">
                <button class="btn btn-blue mt-3 bigbtn" id="submit-final">Submit – No Changes<br><small>تصدیق اور
                        ارسال</small></button>
            </div>
        </div> --}}

        <!-- Modal -->
        {{-- <div class="modal fade" id="reselectModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content p-3">
                    <h3 id="category-name-review"></h3>
                    <h5 class="text-center">Reselect Candidates </h5>
                    <h6 class="text-center text-muted mb-3">امیدوار دوبارہ منتخب کریں</h6>

                    <div id="reselect-body" class="table-responsive"></div>

                    <div class="text-center mt-3">
                        <button class="btn btn-warning px-5" id="confirm-reselect">Update<br><small>تبدیل
                                کریں</small></button>
                    </div>
                </div>
            </div>
        </div> --}}

        <!-- Step : Confirmation -->
        <div class="vote-step d-none" data-step="5">
            <h4 class="mb-3 text-success"> Vote Submitted Successfully!</h4>
            <h5 class="mb-3">آپ کا ووٹ کامیابی سے درج ہو چکا ہے۔</h5>
            <div class="btn-circle btn-success mx-auto my-3">&#10003;</div>
            <p>Thank you for participating in the election.<br>الیکشن میں حصہ لینے کا شکریہ۔</p>

        </div>

    </div>
</div>
@endsection

@section('scripts')

<script>
    const votingData = @json($final_candidates);
    var savedFingerTemplates = [];
    let current = 0;
    let returnedVotingData;

    function onSuccessFingerPrintVerification() {
        current = 3;
        returnedVotingData = renderCategoryVoteTable(votingData);
    }

    $(document).ready(function () {

        const steps = $('.vote-step');

        window.showStep = function(index) {
            steps.addClass('d-none');
            steps.eq(index).removeClass('d-none');

            if (index === 2) {
                fingerprintVerification();
            }
            if (index === 3) {
                fetchVerifiedVoterData();
            }
        }

        function focusCnicInput() {
            setTimeout(() => {
                const $input = $('#cnic-scan');
                if ($input.is(':visible')) {
                    $input.focus();
                }
            }, 100);
        }

        $('.next-btn').on('click', function () {
            if (!validateStep(current)) return;

            current++;
            showStep(current);
            if (current ===4) {
                $('.stepper-card').css('width', '95%');
            }
             if (current ===5) {
                $('.stepper-card').css('max-width','1000px');
            }
            if (current === 1) {
                focusCnicInput();
            }
        });

        $('.back-btn').on('click', function () {
            if (current > 0) {
                current--;
                showStep(current);
            }
        });

        // $('#cnic-scan').on('keyup', function (e) {
        //     const cnic = $(this).val().replace(/-/g, '');

        //     if (cnic.length === 13) {
        //         $.ajax({
        //             url: '{{ route("frontend.election.fetchSavedFingerTemplates") }}',
        //             method: 'POST',
        //             data: { cnic_no: cnic },
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             },
        //             success: function (response) {
        //                 alert('success');

        //                 savedFingerTemplates.length = 0;
        //                 if (Array.isArray(response)) {
        //                     response.forEach(function (bio) {
        //                         savedFingerTemplates.push({
        //                             id: bio.id,
        //                             template: bio.template_2,
        //                             finger_name: bio.finger_name
        //                         });
        //                     });
        //                 }

        //                 $('#cnic-error').addClass('d-none');
        //                 // current = 2;
        //                 // showStep(current);
        //             },
        //             error: function () {
        //                 alert('Failed to fetch fingerprint data.');
        //             }
        //         });
        //     } else {
        //         $('#cnic-error').removeClass('d-none').text('Invalid CNIC scanned.');
        //     }
        // });

       let cnicScanTimeout;

        $('#cnic-scan').on('input', function () {
            clearTimeout(cnicScanTimeout);

            cnicScanTimeout = setTimeout(function () {
                const inputVal = $('#cnic-scan').val().replace(/-/g, '');

                let cnic;

                // If scanned string is longer than 25 chars, extract CNIC
                if (inputVal.length > 25) {
                    cnic = inputVal.substring(12, 25);
                    $('#cnic-scan').val(cnic); // Only override when scanned
                } else {
                    cnic = inputVal; // Use user input directly
                }

                if (cnic.length === 13) {
                    $.ajax({
                        url: '{{ route("frontend.election.fetchSavedFingerTemplates") }}',
                        method: 'POST',
                        data: { cnic_no: cnic },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            savedFingerTemplates.length = 0;
                            if (Array.isArray(response)) {
                                response.forEach(function (bio) {
                                    savedFingerTemplates.push({
                                        id: bio.id,
                                        template: bio.template_2,
                                        finger_name: bio.finger_name
                                    });
                                });
                            }

                            current = 2;
                            showStep(current);

                            $('#cnic-error').addClass('d-none');
                            formData.cnic = cnic;
                        },
                        error: function (errResponse, status, error) {
                            Swal.fire({
                                title: 'Already Voted',
                                text: "You have already voted.",
                                icon: 'warning',
                            });

                            setTimeout(() => {
                                    location.reload();
                                }, 3000);

                            // if (errResponse?.status === 409) {
                                
                            // }
                        }
                    });
                } else {
                    $('#cnic-error').removeClass('d-none').text('Invalid CNIC scanned.');
                }
            }, 300);
        });

        $('#submit-multi-vote').off('click').on('click', () => {
            if (!returnedVotingData) {
                console.error('votingData not set');
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: 'Are you sure you want to continue?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, continue',
                cancelButtonText: 'No, go back'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '{{ route("frontend.election.submitVote") }}',
                        method: 'POST',
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            showStep(5);
                            setTimeout(() => {
                                location.reload();
                            }, 3000);
                        },
                        error: function(errResponse, status, error) {
                            Swal.fire({
                                title: 'Error',
                                text: errResponse?.responseJSON?.message || 'Failed to submit vote. Please try again.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });

        function fingerprintVerification()
        {
            const cnic = $('#cnic-scan').val().replace(/-/g, '');
            if (!cnic || cnic.length !== 13) {
                current = 1;
                showStep(current);
                return;
            }

            CallSGIFPGetData(SuccessFunc1, ErrorFunc);
        }

        function fetchVerifiedVoterData()
        {
            // Remove alert for production use
            const cnic = $('#cnic-scan').val().replace(/-/g, '');
            $.ajax({
            url: '{{ route("frontend.election.fetchVerifiedVoterData") }}',
            method: 'POST',
            data: { cnic_no: cnic },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#voter-name').text(response.name || '');
                $('#voter-cnic').text(response.cnic_no || '');
                $('#voter-id').text(response.id || '');
                if(response.webcam_image_path){
                    $('#voter-photo').attr('src', '{{ asset("storage/app/public") }}/' + response.webcam_image_path.replace(/^public\//, ''));
                }
            },
            error: function(xhr, status, error) {
                alert('fetchVerifiedVoterData error');
            }
            });
        }

        // $('#submit-final').on('click', () => {
        //     showStep(6);
        // });

    });

</script>

<script src="{{asset('public/election/js/vote-cast/finger-verification.js')}}"></script>

@endsection