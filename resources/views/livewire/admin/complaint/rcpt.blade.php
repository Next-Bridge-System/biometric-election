<div wire:ignore.self class="modal fade" id="complaint_modal" tabindex="-1" role="dialog"
aria-labelledby="roleFormModalTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Complaint - RCPT</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div wire:loading style="min-height:100px">
                        Processing...Please wait.
                    </div>

                    <div wire:loading.remove class="text-center">
                        <h1>
                            @if ($rcpt_no)
                            <i class="fas fa-check text-success"></i>
                            @else
                            <i class="fas fa-exclamation-circle text-warning"></i>
                            @endif
                        </h1>
                        <h5 class="text-uppercase"><b>Complaint: {{$selected_row_id}}</b></h5>

                        @if ($rcpt_no)
                        <h1>{{$rcpt_no}}</h1>
                        <h3>{{$rcpt_date}}</h3>
                        @else
                        <h5 class="text-danger">Note: Please confirm before proceeding
                            because this action can't be revertable!</h5>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            @if ($rcpt_no)
            <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click="close">Close</button>
            @else
            <button type="button" class="btn btn-danger" data-dismiss="modal" wire:click="close">Cancel</button>
            <button type="button" class="btn btn-success" wire:click="updateRcpt('{{$selected_row_id}}')">Yes, Confirm it!</button>
            @endif
        </div>
    </div>
</div>
</div>