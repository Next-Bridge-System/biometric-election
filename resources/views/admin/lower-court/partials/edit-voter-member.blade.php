@if (isset($application->voterMemberLc->id) && $application->voterMemberLc->id &&
Route::currentRouteName() == 'lower-court.show')
<button type="button" class="btn btn-primary btn-xs float-right" data-toggle="modal"
    data-target="#edit_voter_member_lc">
    <i class="fas fa-edit mr-1" aria-hidden="true"></i> Edit
</button>

<div class="modal fade" id="edit_voter_member_lc" tabindex="-1" aria-labelledby="edit_voter_member_label"
    aria-hidden="true">
    <div class="modal-dialog">
        <form action="#" method="POST" class="update_voter_member_lc"> @csrf
            <input type="hidden" name="application_id" id="application_id" value="{{$application->id}}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edit_voter_member_label">
                        Edit Bar Association & Passing Year:
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Voter Member / Bar Association <span class="text-danger">*</span></label>
                        <select name="voter_member_lc" id="voter_member_lc" class="form-control custom-select" required>
                            <option value="">--Select Bar Association --</option>
                            @foreach ($bars as $bar)
                            <option {{ $bar->id == $application->voterMemberLc->id ? 'selected': ''}}
                                value="{{$bar->id}}">{{$bar->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-primary">Save & Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif