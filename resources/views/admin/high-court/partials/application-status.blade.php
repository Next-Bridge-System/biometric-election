@if ($application->is_final_submitted == 1 && Route::currentRouteName() == 'high-court.show')
<section>
    @if ($application->app_status == 6)
    <form action="#" method="POST" id="hc_app_status_form"> @csrf
        <div class="mt-2">
            <button type="submit" class="btn btn-success btn-sm" value="1">Active</button>
            <button type="submit" class="btn btn-danger btn-sm" value="7">Rejected</button>
        </div>
    </form>
    @else
    <button type="button" class="btn btn-link float-right btn-sm" data-toggle="modal"
        data-target="#hc_app_status_modal"><i class="far fa-edit"></i></button>
    <div class="modal fade" id="hc_app_status_modal" tabindex="-1" aria-labelledby="hc_app_status_modal"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="hc_app_status_modal">Edit Application Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="#" id="hc_update_app_status_form" method="POST"> @csrf
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
</section>
@endif