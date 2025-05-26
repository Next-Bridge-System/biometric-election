<form class="steps-form" data-action="{{route('intimations.create-step-7',$application->id)}}" action="#" method="POST"
    id="create_step_7_form" enctype="multipart/form-data"> @csrf
    <div class="card-body">
        @include('frontend.intimation.partials._application-view')
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        @if($application->is_accepted)
        <a href="{{route('intimations.show',$application->id)}}" class="btn btn-success float-right">Save &
            Update</a>
        @else
        <button type="button" class="btn btn-success float-right" data-toggle="modal"
            data-target="#exampleModalFinal">Save & Submit
        </button>
        @endif

        <div class="modal fade" id="exampleModalFinal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Verify Application</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h6>Are you sure you want to submit this application. Kindly verify your all changes & uploads.
                            Thankyou.</h6>

                        <h6 class="mt-4">Bank Payment Voucher</h6>
                        <div class="row">
                            <div class="col-12 form-group-inline">
                                <input type="radio" value="Habib Bank Limited" name="bank_name" id="HBL" checked>
                                <label for="HBL">HBL (Online/Branch)</label>
                            </div>
                        </div>

                        <h5 class="mt-2">Select Option.</h5>
                        <div class="form-group">
                            <input type="radio" name="final_submission" value="1" checked> Print Voucher
                            <br>
                            @if (Auth::guard('admin')->user()->hasPermission('add-payments'))
                            <input type="radio" name="final_submission" value="2"> Submit Payment
                            @endif
                        </div>

                        <div class="form-group mt-3">
                            <input type="checkbox" class="mr-2" name="is_otp" id="is_otp"><label for="is_otp"> Add
                                OTP</label>
                        </div>
                        <div class="form-group mt-3 otp-section hidden">
                            <label for="">Enter OTP to Continue </label> <a id="resendPIN" href="javascript:void(0)">
                                Resend Pin</a>
                            <input type="text" class="form-control" name="otp" placeholder="Enter One Time Password"
                                required disabled>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="{{route('intimations.token',['download'=>'pdf','application' => $application])}}"
                            target="_blank" class="btn btn-primary">Print Token</a>
                        <button type="submit" class="btn btn-success float-right submit_btn" value="save">Yes, I Accept
                            It.
                        </button>
                        <button class="btn btn-success hidden loading_btn" type="button" disabled><span
                                class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>Loading...
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <a href="javascript:void(0)" onclick="goToStep('{{route('intimations.create-step-6', $application->id)}}',6)"
            class="btn btn-secondary float-right mr-1">Back</a>
    </div>
</form>
