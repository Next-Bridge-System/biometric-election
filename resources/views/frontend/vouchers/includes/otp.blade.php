{{-- <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#voucher_otp_modal">Save
    &
    Submit
</button> --}}
<div class="modal fade" id="voucher_otp_modal" tabindex="-1" aria-labelledby="otp_modal_label" aria-hidden="true"
    data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <form action="#" id="otp_verify_form" method="POST"> @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="otp_modal_label">Verify Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6>Are you sure you want to submit this form. Once you submit it you
                        cannot edit or update it. Kindly verify your all changes.Thankyou.</h6>
                    <div class="form-group mt-3">
                        <label for="">Enter OTP to Continue </label> <a id="resendPIN" href="javascript:void(0)"> Resend
                            Pin</a>
                        <input type="text" class="form-control" name="otp" placeholder="Enter OTP" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success float-right submit_btn" value="save">Yes, I Accept
                        It.</button>
                    <button class="btn btn-success hidden loading_btn" type="button" disabled><span
                            class="spinner-grow spinner-grow-sm" role="status"
                            aria-hidden="true"></span>Loading...</button>
                </div>
            </div>
        </form>
    </div>
</div>
