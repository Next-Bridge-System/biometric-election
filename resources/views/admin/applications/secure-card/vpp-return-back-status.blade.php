@if (isset($application->vppost->vpp_return_back))

@if ($application->vppost->vpp_return_back == 1)
<a href="{{route('secure-card.vpp-return-back-print', ['download'=>'pdf','application' => $application])}}"
    target="_blank" class="btn btn-sm btn-warning">VPP Return Back Print</a>
@endif

<button type="button" class="btn btn-warning btn-sm float-right ml-1" data-toggle="modal"
    data-target="#vpp_return_back_status">
    VPP Return Back Status
</button>

<div class="modal fade" id="vpp_return_back_status" tabindex="-1" aria-labelledby="vpp_return_back_status_label"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{route('secure-card.vpp-return-back-status')}}" method="POST"> @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="vpp_return_back_status_label">VPP Return Back Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="application_id" id="application_id" value="{{$application->id}}">
                    <div class="col-md-12 form-group">
                        <label for="">VPP Return Back</label>
                        <select name="vpp_return_back" id="vpp_return_back" class="form-control custom-select">
                            <option value="">--Select Status--</option>
                            <option value="1" {{$application->vppost->vpp_return_back == 1 ? 'selected' : ''}}>Return
                                Back</option>
                            <option value="0" {{$application->vppost->vpp_return_back == 0 ? 'selected' : ''}}>Not
                                Return Back</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
