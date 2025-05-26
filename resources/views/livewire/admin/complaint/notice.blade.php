<!-- Button trigger modal -->
<button type="button" class="btn btn-primary btn-sm mr-1" data-toggle="modal" data-target="#notice_modal" wire:click="openNotes('notice')">
    <i class="far fa-copy mr-1"></i>Notices
    <span class="badge badge-warning ml-1">{{$complaint->notices->count()}}</span>
</button>

<button type="button" class="btn btn-primary btn-sm mr-1" data-toggle="modal" data-target="#notice_modal" wire:click="openNotes('hearing')">
    <i class="fas fa-gavel mr-1"></i>Hearings
    <span class="badge badge-warning ml-1">{{$complaint->hearings->count()}}</span>
</button>

<div wire:ignore.self class="modal fade" id="notice_modal" tabindex="-1" aria-labelledby="notice_modal_label"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-capitalize" id="notice_modal_label">Add {{$selected_row_notice_type}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 form-group" id="notes" wire:ignore>
                        <textarea type="text" wire:model.defer="complaint_notice_desc" id="complaint_notice_desc"
                            class="form-control textarea" rows="6"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" id="accordion">
                        @foreach ($complaint_notice_list as $note)
                        <div class="card">
                            <div class="card-header" id="headingOne{{$note->id}}">
                                <h5 class="mb-0">
                                    <button class="btn btn-link text-capitalize" data-toggle="collapse"
                                        data-target="#collapseOne{{$note->id}}" aria-expanded="true"
                                        aria-controls="collapseOne{{$note->id}}">
                                        <b>{{$note->notice_type}} #{{$loop->iteration}}: </b> Submitted By
                                        {{getAdminName($note->admin_id)}} at {{$note->created_at}}
                                    </button>
                                </h5>
                            </div>

                            <div id="collapseOne{{$note->id}}" class="collapse"
                                aria-labelledby="headingOne{{$note->id}}" data-parent="#accordion">
                                <div class="card-body">
                                    {!!$note->description!!}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success btn-sm text-capitalize" wire:click.prevent="saveNotes()">Save
                    {{$selected_row_notice_type}}</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:load', function () {
        $('#notice_modal').on('shown.bs.modal', function () {
            $('#complaint_notice_desc').summernote({
                height: 350,
                callbacks: {
                    onChange: function (contents) {
                        Livewire.emit('set-complaint-notice', contents);
                    }
                }
            });
        });
    });
</script>