@if ($application->assignMembers->count() > 0)
<fieldset class="border p-4 mb-4" id="interview_section">
    <legend class="w-auto">Interview Section</legend>
    <div class="row">
        <div class="table-responsive">
            <table class="table table-striped table-sm table-bordered">
                <tr>
                    <th>Candidate Name:</th>
                    <td colspan="2">{{$application->lawyer_name}}</td>
                    <th>Interview Status:</th>
                    <td>
                        <span class="badge badge-{{getLcInterviewStatus($application)['badge']}}">
                            {{getLcInterviewStatus($application)['name']}}
                        </span>
                    </td>
                </tr>
                <tr class="bg-success text-white text-center">
                    <th colspan="5">Assigned Members</th>
                </tr>
                @foreach ($application->assignMembers as $assignMember)
                <tr>
                    <th>
                        {{$loop->iteration}} - Member

                        @if ($assignMember->is_code_verified == 0)
                        <button type="button" class="btn btn-primary btn-xs ml-2" data-toggle="modal"
                            data-target="#editMember{{$assignMember->id}}">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </button>

                        <div class="modal fade" id="editMember{{$assignMember->id}}" tabindex="-1"
                            aria-labelledby="editMemberLabel" aria-hidden="true">
                            <form action="#" class="edit_member_form" method="POST"> @csrf
                                <input type="hidden" name="assign_member_id" value="{{$assignMember->id}}">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editMemberLabel">Edit Member</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="">Member</label>
                                                <select name="member_id" id="member_id"
                                                    class="form-control custom-select">
                                                    @foreach (App\Member::orderBy('name','asc')->get() as $member)
                                                    <option value="{{$member->id}}" {{$member->id ==
                                                        $assignMember->member_id ?
                                                        'selected' : ''}}>{{$member->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save & Update</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @endif
                    </th>
                    <td>
                        {{$assignMember->member->name ?? '-'}}
                    </td>
                    <th>Secret Code</th>
                    <td>
                        @if(Auth::guard('admin')->user()
                        ->hasPermission('add-interview-secret-code-lc'))
                        @if ($assignMember->is_code_verified == 1)
                        <span>{{$assignMember->code}}</span>
                        @else
                        <form action="#" class="assign_code_verification_form" method="POST"> @csrf
                            <input type="hidden" name="lower_court_id" value="{{$application->id}}">
                            <input type="hidden" name="member_id" value="{{$assignMember->member_id}}">
                            <input type="text" name="verification_code" required>
                            <button type="sumit" class="btn btn-success btn-sm mb-1">Save</button>
                        </form>
                        @endif
                        @else ************ @endif
                    </td>
                    <td>
                        @if ($assignMember->is_code_verified == 1)
                        <span class="badge badge-success">Verified</span>
                        @else
                        <span class="badge badge-danger">Not Verified</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</fieldset>
@endif