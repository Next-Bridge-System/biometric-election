<button type="button" class="btn btn-primary btn-sm float-right m-1" data-toggle="modal" data-target="#bankVch">
    <i class="fas fa-print mr-1" aria-hidden="true"></i>Bank Vouchers
</button>

<div class="modal fade" id="bankVch" tabindex="-1" aria-labelledby="bankVchLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bankVchLabel">Intimation - Bank Vouchers</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @foreach ($payments as $payment)
                @if(isset($payment) && $payment->bank_name != null)
                <a href="{{route('intimations.prints.bank-voucher',['download'=>'pdf','application_id' => $application->id,'payment_id'=>$payment->id])}}"
                    target="_blank" class="btn btn-primary btn-sm mr-1"><i class="fas fa-print mr-1"></i>
                    <span>Print Bank Voucher {{$payments->count() > 1 ? '#'.$loop->iteration : ''}}</span>
                </a>
                @endif
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
