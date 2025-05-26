<!-- Button trigger modal -->
<button type="button" class="btn btn-primary float-right btn-sm mr-1" data-toggle="modal" data-target="#gchcNotesModal">
    <i class="far fa-copy mr-1"></i>Notes
    <span class="badge badge-warning ml-1">{{$notes->count()}}</span>
</button>

<!-- Modal -->
<div class="modal fade" id="gchcNotesModal" tabindex="-1" aria-labelledby="gchcNotesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gchcNotesModalLabel">GC High Court Notes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group col-md-12">
                    <form action="#" id="gchc_notes_form" method="POST"> @csrf
                        <div class="form-group" id="notes">
                            <textarea type="text" name="gchc_notes" id="gchc_notes" class="form-control textarea"
                                rows="6"></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-sm">Save & Submit</button>
                        </div>
                    </form>
                </div>
                <div id="accordion">
                    @foreach ($notes as $note)
                    <div class="card">
                        <div class="card-header" id="headingOne{{$note->id}}">
                            <h5 class="mb-0">
                                <button class="btn btn-link" data-toggle="collapse"
                                    data-target="#collapseOne{{$note->id}}" aria-expanded="true"
                                    aria-controls="collapseOne{{$note->id}}">
                                    <b>Note #{{$note->id}}: </b> Submitted By
                                    {{getAdminName($note->admin_id)}} at {{$note->created_at}}
                                </button>
                            </h5>
                        </div>

                        <div id="collapseOne{{$note->id}}" class="collapse" aria-labelledby="headingOne{{$note->id}}"
                            data-parent="#accordion">
                            <div class="card-body">
                                {!!$note->note!!}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>