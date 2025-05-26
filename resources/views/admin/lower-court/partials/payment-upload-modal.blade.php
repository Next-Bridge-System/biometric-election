<button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
    data-target="#upload_payment_modal_{{$payment->id}}">
    Upload Payment Voucher #{{$payment->voucher_no}}
</button>

<div class="modal fade" id="upload_payment_modal_{{$payment->id}}" tabindex="-1"
    aria-labelledby="upload_payment_modal_label_{{$payment->id}}" aria-hidden="true">
    <div class="modal-dialog">
        <form action="" method="POST" id="upload_payment_form_{{$payment->id}}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="upload_payment_modal_label_{{$payment->id}}">Upload Payment vouhcers
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Payment Voucher</label>
                        @if ($payment->voucher_file != NULL)
                        <div class="col-md-12">
                            <img src="{{asset('storage/app/public/'.$payment->voucher_file)}}" alt="" class="w-50">
                        </div>
                        @if ($payment->payment_status == 0)
                        <a href="javascript:void(0)" class="btn btn-danger btn-sm"
                            data-action="{{route('frontend.lower-court.destroy.payment-voucher', $payment->id)}}"
                            onclick="removeLcVoucherFile(this,6)">Remove {{$payment->voucher_name}}</a>
                        @endif
                        @else
                        <input type="file" id="voucher_file_{{$payment->id}}" name="voucher_file_{{$payment->id}}"
                            accept="image/jpg,image/jpeg,image/png">
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="">Voucher Number</label>
                        <input type="text" class="form-control" name="transaction_id" placeholder="Voucher Number"
                            id="transaction_id_{{$payment->id}}" value="{{$payment->transaction_id}}">
                    </div>
                    <div class="form-group">
                        <label for="">Paid Date</label>
                        <div class="input-group date" id="paid_date_{{$payment->id}}" data-target-input="nearest">
                            <input type="text" value="{{getDateFormat($payment->paid_date)}}"
                                class="form-control datetimepicker-input" data-target="#paid_date_{{$payment->id}}"
                                name="paid_date" id="paid_date_input_{{$payment->id}}" required autocomplete="off"
                                data-toggle="datetimepicker" />
                            <div class="input-group-append" data-target="#paid_date_{{$payment->id}}"
                                data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
