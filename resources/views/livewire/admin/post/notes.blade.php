<div wire:ignore.self class="modal fade" id="notes_modal" tabindex="-1" aria-labelledby="notes_modal_label"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notes_modal_label">Add Notes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 form-group" id="notes" wire:ignore>
                        <textarea type="text" wire:model.defer="post_note" id="post_note" class="form-control textarea" rows="6"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" id="accordion">
                        @foreach ($post_notes_list as $note)
                        <div class="card">
                            <div class="card-header" id="headingOne{{$note->id}}">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne{{$note->id}}" aria-expanded="true" aria-controls="collapseOne{{$note->id}}">
                                        <b>Note #{{$note->id}}: </b> Submitted By
                                        {{getAdminName($note->admin_id)}} at {{$note->created_at}}
                                    </button>
                                </h5>
                            </div>
    
                            <div id="collapseOne{{$note->id}}" class="collapse" aria-labelledby="headingOne{{$note->id}}" data-parent="#accordion">
                                <div class="card-body">
                                    {!!$note->note!!}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success btn-sm" wire:click.prevent="saveNotes()">Save  changes</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:load', function () {
        $('#notes_modal').on('shown.bs.modal', function () {
            $('#post_note').summernote({
                height: 350,
                callbacks: {
                    onChange: function (contents) {
                        Livewire.emit('set-post-notes', contents);
                    }
                }
            });
        });

        // $('#notes_modal').on('hidden.bs.modal', function () {
        //     $('#post_note').summernote('destroy');
        // });
    });
</script>