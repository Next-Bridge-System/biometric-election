<fieldset class="border p-4 mb-4">
    <legend class="w-auto">Lower Court Payments</legend>
    <div class="row">
        @foreach ($application->payments as $payment)
        <div class="col-md-4 form-group">
            <label>{{$payment->voucher_name}}: <span class="text-danger">*</span>
                <small>{{$payment->voucher_no}}</small></label>
            @if ($payment->voucher_file != NULL)
            <div class="col-md-12">
                <img src="{{asset('storage/app/public/'.$payment->voucher_file)}}" alt="" class="w-100">
            </div>
            @if ($payment->payment_status == 0)
            <a href="javascript:void(0)" class="btn btn-danger btn-xs col-md-12 mt-2 mb-4"
                data-action="{{route('frontend.lower-court.destroy.payment-voucher', $payment->id)}}"
                onclick="removeLcVoucherFile(this,6)">Remove {{$payment->voucher_name}}</a>
            @endif
            @else
            <input type="file" id="voucher_file_{{$payment->id}}" name="voucher_file_{{$payment->id}}"
                accept="image/jpg,image/jpeg,image/png">
            @endif

            <div class="errors" data-id="{{$payment->id}}"></div>
        </div>
        @endforeach
    </div>
</fieldset>
