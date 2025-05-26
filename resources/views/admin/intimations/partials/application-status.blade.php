@if ($application->is_accepted == 1 && $application->is_intimation_completed == 0 &&
Route::currentRouteName() == 'intimations.show' && Auth::guard('admin')->user()->hasPermission('intimation-approved'))
<div class="col-md-12 mb-4">
    @if ($application->application_status == 6)
    <form action="#" method="POST" id="intimation_app_status_form" enctype="multipart/form-data"> @csrf
        <div class="mt-2">
            <button type="submit" class="btn btn-success btn-sm" value="1">Active</button>
            <button type="submit" class="btn btn-danger btn-sm" value="7">Rejected</button>
        </div>
    </form>
    @else
    <button type="button" class="btn btn-primary float-right btn-xs mr-1" data-toggle="modal"
        data-target="#update_app_status_modal"><i class="far fa-edit mr-1"></i>Edit</button>
    <div class="modal fade" id="update_app_status_modal" tabindex="-1" aria-labelledby="update_app_status_modal"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="update_app_status_modal">Edit Application Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="#" id="update_intimation_app_status_form" method="POST"> @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Status:</label>
                            <select name="app_status" id="app_status" class="form-control custom-select" required>
                                @foreach ($app_status as $status)
                                <option {{$status->key == $application->app_status ? 'selected' : ''}}
                                    value="{{$status->key}}">{{$status->value}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" id="notes">
                            <label for="">Status Reason:</label>
                            <textarea type="text" name="app_status_reason" id="app_status_reason"
                                class="form-control textarea" rows="6" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
@endif