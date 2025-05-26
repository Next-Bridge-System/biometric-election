@if ($application->assignMembers->count() == 0)

@if (Auth::guard('admin')->user()->hasPermission('assign-interview-members-lc'))
<button type="button" class="btn btn-primary btn-sm float-right m-1" data-toggle="modal" data-target="#assignMember">
    <i class="far fa-comments mr-1"></i>Interview Assign Members
</button>

<div class="modal fade" id="assignMember" tabindex="-1" aria-labelledby="assignMemberLabel" aria-hidden="true">
    <form action="#" class="assign_member_form" method="POST"> @csrf
        <input type="hidden" name="lower_court_id" value="{{$application->id}}">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignMemberLabel">Assign Members To Candidate For Interview</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Member 1</label>
                        <select name="member_1" id="member_1" class="form-control custom-select">
                            <option value="" selected>--Select Member--</option>
                            @foreach (App\Member::orderby('name','asc')->get() as $member)
                            <option value="{{$member->id}}">{{$member->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Member 2</label>
                        <select name="member_2" id="member_2" class="form-control custom-select">
                            <option value="" selected>--Select Member--</option>
                            @foreach (App\Member::orderby('name','asc')->get() as $member)
                            <option value="{{$member->id}}">{{$member->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endif

@else
<a href="#interview_section" class="btn btn-success btn-sm float-right m-1"><i class="fas fa-check mr-1"></i>Members
    Assigned</a>

@if (Auth::guard('admin')->user()->hasPermission('print-interview-letter-lc'))
<a href="{{route('lower-court.prints.candidate-interview', $application->id)}}" target="_blank"
    class="btn btn-primary btn-sm float-right m-1"><i class="fas fa-print mr-1"></i>Candidate Interview Letter
</a>
@endif

@endif