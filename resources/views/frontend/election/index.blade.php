<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Vote Casting – Biometric Election System</title>

    <!-- Bootstrap 4.5 -->
    <link rel="stylesheet" href="{{asset('public/election/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{asset('public/election/css/index/index.css')}}" />
    <link rel="stylesheet" href="{{asset('public/election/css/vote-cast/categoryVoteTable.css')}}">

    <link rel="stylesheet" href="../css/vote-cast/categoryVoteTable.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body class="overflow-hidden">

    <div class="d-flex justify-content-center align-items-center h-100 vw-100 position-relative">
        <!-- Decorations -->
        
        <img src="{{asset('public/admin/images/logo.png')}}" class="deco deco-logo" alt="Logo">
        <img src="{{asset('public/election/assets/backround-left.png')}}" class="deco deco-tl" alt="">
        <img src="{{asset('public/election/assets/background.png')}}" class="deco deco-br" alt="">

        <!-- Main Card -->
        <div class="card p-4 stepper-card text-center" id="vote-card">

            <!-- Screens -->
            <div id="vote-steps">
                <div class="vote-step " data-step="0">
                    <h5 class="mb-3 text-center">Scan Your CNIC</h5>
                    <input id="cnic-scan" type="text" class="form-control mb-3" placeholder="1234-1234567-1" value="1234-1234567-1">
                    <div id="cnic-error" class="text-danger d-none my-3">براہ کرم درست CNIC درج کریں۔</div>
                    <div class="text-center mb-3">
                        <img src="{{asset('public/election/assets/QR.png')}}" alt="Scan QR" class="img-fluid" style="max-width:200px;">
                    </div>
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-green ml-auto next-btn"><i class="fa-solid fa-arrow-right"></i></button>
                    </div>
                </div>

                <div class="vote-step d-none" data-step="1">
                    <h4 class="mb-3 font-weight-bold">Welcome to</h4>
                    <h5 class="mb-2">Biometric Election System</h5>
                    <img src="{{asset('public/election/assets/fingerprint.png')}}" class="my-5" alt="" style="width: 200px;">
                    <h5 class="mb-4">بائیو میٹرک الیکشن سسٹم</h5>
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-green ml-auto next-btn"><i class="fa-solid fa-arrow-right"></i></button>
                    </div>
                </div>



                <div class="vote-step d-none" data-step="2">
                    <h4 class="mb-3 font-weight-bold">Scan your Fingerprint</h4>
                    <h5 class="mb-4">اپنی بایومیٹرک تصدیق کریں</h5>

                    <div class="d-flex justify-content-center align-items-center flex-column">
                        <img src="{{asset('public/election/assets/FingerScan.png')}}" alt="Fingerprint" style="width: 90px; margin-bottom: 1rem;">
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
                                <p class="mb-1"><strong>Name:</strong> <span id="voter-name">Sample</span></p>
                                <p class="mb-1"><strong>CNIC:</strong> <span id="voter-cnic">123-12321-12</span></p>
                                <p class="mb-1"><strong>Voter ID:</strong> <span id="voter-id">12323</span></p>
                                <h6 class="mt-3 mb-0 text-success">Biometric verification has been done.</h6>
                                <p class="mb-0">آپ کی بایومیٹرک تصدیق ہو گئی ہے</p>
                            </div>
                            <div class="col-sm-5 rounded text-center shadow ">
                                <img id="voter-photo" src="{{asset('public/election/assets/face.png')}}" alt="Voter Photo"
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

                <div class="container mt-4 vote-step d-none" data-step="5">
                    <h4 class="text-center font-weight-bold mb-2">You have voted for these candidates.</h4>
                    <h5 class="text-center text-muted mb-4">آپ نے ان امیدواروں کو ووٹ کر چکے ہیں</h5>

                    <table class="table table-bordered text-center" id="final-vote-table">
                        <tbody></tbody>
                    </table>

                    <div class="text-center">
                        <button class="btn btn-green mt-3" id="submit-final">Submit – No Changes<br><small>تصدیق اور
                                ارسال</small></button>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="reselectModal" tabindex="-1" role="dialog" aria-hidden="true">
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
                </div>

                <!-- Step : Confirmation -->
                <div class="vote-step d-none" data-step="6">
                    <h4 class="mb-3 text-success"> Vote Submitted Successfully!</h4>
                    <h5 class="mb-3">آپ کا ووٹ کامیابی سے درج ہو چکا ہے۔</h5>
                    <div class="btn-circle btn-success mx-auto my-3">&#10003;</div>
                    <p>Thank you for participating in the election.<br>الیکشن میں حصہ لینے کا شکریہ۔</p>

                </div>

            </div>
        </div>
    </div>
    <!-- Fingerprint Failed Modal -->
    <div class="modal fade" id="fingerFailModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content text-center p-4 border border-primary">
                <h4>Your biometric verification was not successful.</h4>
                <img src="{{asset('public/election/assets/cross.png')}}" alt="X" style="width: 40px; margin: 1rem auto;">
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

    <script src="{{asset('public/election/js/vote-cast/globals.js')}}"></script>
    <script src="{{asset('public/election/js/vote-cast/dummy-data/categories.js')}}"></script>
    <script src="{{asset('public/election/js/vote-cast/categoryVoteTable.js')}}"></script>
    <script src="{{asset('public/election/js/vote-cast/reviewTable.js')}}"></script>
    <script src="{{asset('public/election/js/vote-cast/formValidation.js')}}"></script>
    <script src="{{asset('public/election/js/vote-cast/index.js')}}"></script>

</body>

</html>