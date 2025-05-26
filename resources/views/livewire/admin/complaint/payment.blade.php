@if ($payment->payment_status == 1)
<span class="badge badge-success"><i class="fas fa-check mr-1"></i>Payment Added</span>

<button class="btn btn-danger btn-sm float-right" wire:click="resetPayment">
    <i class="fas fa-redo mr-1"></i>Reset</button>

@endif
@if ($payment->payment_status == 0)
<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#add_payment">
    <i class="fas fa-plus mr-1"></i>Add Payment
</button>
<div wire:ignore.self class="modal fade" id="add_payment" tabindex="-1" aria-labelledby="add_payment"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add_payment">Payment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class=" table table-bordered table-sm" style="width: 100%">
                    <tbody>
                        <tr>
                            <th>Transaction ID:<span class="text-danger">*</span></th>
                            <td>
                                <input wire:model.defer="transaction_id" type="text" class="form-control"
                                    placeholder="Transaction ID" required>
                                @error('transaction_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </td>

                            <th>Amount Paid:</th>
                            <td>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">PKR</span>
                                    </div>
                                    <input type="text" class="form-control" value="{{$payment->amount}}" disabled>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>Voucher No:</th>
                            <td>
                                <div>{{$payment->voucher_no}}</div>
                            </td>
                            <th>Paid Date: <span class="text-danger">*</span></th>
                            <td>
                                <input wire:model.defer="paid_date" type="date" class="form-control">
                                @error('paid_date')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <label>Bank Voucher File<span class="required-star">*</span></label>
                                <input type="file" wire:model="voucher_file">

                                @if ($voucher_file)
                                <img src="{{ $voucher_file->temporaryUrl() }}" class="custom-image-preview mt-2">
                                @endif

                                @if (!$voucher_file)
                                <span wire:ignore>
                                    <img src="{{ asset('storage/app/public/'.$payment->voucher_file)}}"
                                        class="custom-image-preview mt-2">
                                </span>
                                @endif

                                <div>
                                    @error('voucher_file') <span class="text-danger">
                                        {{ $message}}</span>
                                    @enderror
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success btn-sm" wire:click="savePayment()">Save
                    changes</button>
            </div>
        </div>
    </div>
</div>
@endif