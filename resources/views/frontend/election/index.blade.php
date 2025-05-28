@extends('layouts.election')

@section('content')
<!-- Main Card -->
<div class="card p-4 stepper-card text-center" id="vote-card">

    <!-- Screens -->
    <div id="vote-steps">

        <div class="vote-step" data-step="0">
            <h4 class="mb-3 font-weight-bold">Welcome to</h4>
            <h5 class="mb-2">Biometric Election System</h5>
            <img src="{{asset('public/election/assets/fingerprint.png')}}" class="my-5" alt="" style="width: 200px;">
            <h5 class="mb-4">بائیو میٹرک الیکشن سسٹم</h5>
            <div class="d-flex justify-content-end">
                <button class="btn btn-green ml-auto next-btn"><i class="fa-solid fa-arrow-right"></i></button>
            </div>
        </div>

        <div class="vote-step d-none" data-step="1">
            <h5 class="mb-3 text-center">Scan Your CNIC</h5>
            <input id="cnic-scan" type="text" class="form-control mb-3" placeholder="1234-1234567-1" value="">
            <div id="cnic-error" class="text-danger d-none my-3">براہ کرم درست CNIC درج کریں۔</div>
            <div class="text-center mb-3">
                <img src="{{asset('public/election/assets/QR.png')}}" alt="Scan QR" class="img-fluid"
                    style="max-width:200px;">
            </div>
            <div class="d-flex justify-content-end">
                <button class="btn btn-green ml-auto next-btn"><i class="fa-solid fa-arrow-right"></i></button>
            </div>
        </div>

        <div class="vote-step d-none" data-step="2">
            <h4 class="mb-3 font-weight-bold">Scan your Fingerprint</h4>
            <h5 class="mb-4">اپنی بایومیٹرک تصدیق کریں</h5>

            <div class="d-flex justify-content-center align-items-center flex-column">
                <img id="FPImage1" src="{{asset('public/election/assets/FingerScan.png')}}" alt="Fingerprint"
                    style="width: 90px; margin-bottom: 1rem;">
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button class="btn btn-secondary back-btn"><i class="fa-solid fa-arrow-left"></i></button>
                <button class="btn btn-green next-btn"><i class="fa-solid fa-arrow-right"></i></button>
            </div>
        </div>

        <div class="vote-step d-none" data-step="3">
            <h3 class="font-weight-bold">Voter Information</h3>
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
                        <img id="voter-photo" src="" alt="Voter Photo"
                            class="img-fluid rounded" style="max-width: 180px;">
                        <div class="btn-circle btn-success mt-2"><i class="fa-solid fa-check"></i></div>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <button class="btn btn-secondary back-btn"><i class="fa-solid fa-arrow-left"></i></button>
                    <button class="btn btn-green next-btn "><i class="fa-solid fa-arrow-right"></i></button>
                </div>

            </div>
        </div>

        <!-- Step 6: Multi-Category Voting Table -->
        <div class="vote-step d-none" data-step="4">
            <h4 class="font-weight-bold">Select Candidate Category</h4>
            <h5 class="mb-4">امیدوار کی قسم منتخب کریں</h5>

            <div class="table-responsive">
                <table class="table table-bordered text-center" id="category-vote-table">
                    <tbody></tbody>
                </table>
            </div>

            <button class="btn btn-green mt-3 next-btn" id="submit-multi-vote">Submit <span class="ml-1">جمع
                    کروائیں</span></button>
        </div>

        {{-- <div class="container mt-4 vote-step d-none" data-step="5">
            <h4 class="text-center font-weight-bold mb-2">You have voted for these candidates.</h4>
            <h5 class="text-center text-muted mb-4">آپ نے ان امیدواروں کو ووٹ کر چکے ہیں</h5>

            <table class="table table-bordered text-center" id="final-vote-table">
                <tbody></tbody>
            </table>

            <div class="text-center">
                <button class="btn btn-green mt-3" id="submit-final">Submit – No Changes<br><small>تصدیق اور
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
        <div class="vote-step d-none" data-step="6">
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

    $(document).ready(function () {

        let returnedVotingData;

        let current = 0;
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

        function collectData(step) {
            if (step === 0) {
                formData.cnic = $('#cnic-scan').val().trim();
            }
        }

        $('.next-btn').on('click', function () {
            if (!validateStep(current)) return;
            collectData(current);

            if (current === 2) {                
                returnedVotingData = renderCategoryVoteTable(votingData);
            }

            current++;
            showStep(current);
        });

        $('.back-btn').on('click', function () {
            if (current > 0) {
                current--;
                showStep(current);
            }
        });
        
        $('#cnic-scan').on('keyup', function () {
            const cnic = $(this).val().replace(/-/g, '');
            if (cnic.length === 13) {
                $.ajax({
                    url: '{{ route("frontend.election.fetchSavedFingerTemplates") }}',
                    method: 'POST',
                    data: { cnic_no: cnic },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        savedFingerTemplates.length = 0;
                        if (Array.isArray(response)) {
                            response.forEach(function(bio) {
                                savedFingerTemplates.push({
                                    id: bio.id,
                                    template: bio.template_2,
                                    finger_name: bio.finger_name
                                });
                            });
                        }
                        
                        $('#cnic-error').addClass('d-none');
                        current = 2;
                        showStep(current);
                    },
                    error: function(xhr, status, error) {
                        alert('error');
                    }
                });                
            } else {
                $('#cnic-error').addClass('d-none');
            }
        });

        $('#submit-multi-vote').off('click').on('click', () => {
            if (!returnedVotingData) {
            console.error('votingData not set');
            return;
            }

            const missing = returnedVotingData.filter(entry => {
                return !formData.votes.some(vote => vote.seat_id === entry.id);
            });

            if (missing.length > 0) {
            alert(`Please select a candidate for: ${missing.map(e => e.category).join(', ')}`);
            return;
            }

            $.ajax({
                url: '{{ route("frontend.election.submitVote") }}',
                method: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    showStep(6);
                    console.log('Vote submitted:', response);
                },
                error: function(xhr, status, error) {
                    alert('An error occurred while submitting your vote.');
                    console.error(error);
                }
            });

        });

        function fingerprintVerification()
        {
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
        //     console.log('Form Data:', formData);
        //     showStep(6);
        // });

    });

</script>

<script src="{{asset('public/election/js/vote-cast/finger-verification.js')}}"></script>

@endsection