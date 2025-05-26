<div>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="text-capitalize">Reports - General Search Report</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <div>
                            {{-- <button wire:click="export" class="btn btn-primary" {{$records->total() > 10000 ?
                                'disabled': ''}}><i class="fa fa-print mr-1"></i>Print</button> --}}


                        </div>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="filters row" style=" overflow-y: scroll;">
                                <div class="col-md-4 form-group mr-1">
                                    <label for="">CNIC NO:</label>
                                    <input type="text" wire:model.defer="search_cnic" id="" class="form-control"
                                        autofocus autocomplete="off">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-sm btn-success" wire:click="search"><i
                                            class="fa fa-search mr-1"></i>Search</button>
                                    <button class="btn btn-sm btn-danger" wire:click="clear" {{!$search ? 'disabled'
                                        : '' }}><i class="fa fa-refresh mr-1"></i>Reset</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="" class="table table-bordered table-sm text-uppercase"
                                    style="font-size: 14px; white-space: nowrap;">
                                    <thead class="bg-success text-white">
                                        <tr>
                                            <th>Sr #</th>
                                            <th>Lawyer Type</th>
                                            <th>Lawyer Name</th>
                                            <th>Father/Husband</th>
                                            <th>CNIC NO</th>
                                            <th>DOB</th>
                                            <th>Phone</th>
                                            <th>App Status</th>
                                            <th>Final Submitted At</th>
                                            <th>RCPT NO - RCPT Date</th>
                                            <th>Notes</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($records as $record)
                                        <tr class="{{$record->lawyer_type == $record->current_user_type ? '': ''}}">
                                            <td>{{$loop->iteration}}</td>
                                            <td>
                                                @if ($record->lawyer_type == 'user')
                                                {{$record->current_user_type}} {{$record->lawyer_type}} -
                                                {{$record->main_id}}
                                                @else
                                                {{$record->lawyer_type}} - {{$record->main_id}}
                                                @endif
                                            </td>
                                            <td>{{$record->lawyer}}</td>
                                            <td>{{$record->father}}</td>
                                            <td>{{$record->cnic}}</td>
                                            <td>{{$record->dob}}</td>
                                            <td>{{$record->phone}}</td>
                                            <td>{{$record->app_status}}</td>
                                            <td>{{getDateTimeFormat($record->final_submitted_at)}}</td>
                                            <td>
                                                @if ($record->rcpt_no)
                                                {{$record->rcpt_no}} - {{getDateFormat($record->rcpt_date)}}
                                                @endif

                                                @if (($record->lawyer_type == $record->current_user_type) &&
                                                ($record->final_submitted_at && !$record->rcpt_no))
                                                <button class="btn btn-primary btn-xs" data-toggle="modal" data-bs-toggle="tooltip"
                                                    data-target="#rcpt_modal"
                                                    wire:click="openRcpt('{{ $record->main_id }}', '{{$record->lawyer_type}}')">
                                                    <i class="fas fa-plus mr-1"></i>RCPT NO
                                                </button>
                                                @endif
                                            </td>
                                            <td>
                                                @if (($record->lawyer_type == $record->current_user_type) && ($record->final_submitted_at))
                                                <button class="btn btn-primary btn-xs" href="#" data-toggle="modal"
                                                    data-bs-toggle="tooltip" data-target="#notes_modal"
                                                    wire:click="openNotes('{{ $record->main_id }}', '{{ $record->lawyer_type }}')">
                                                    <i class="fas fa-copy mr-1"></i> Notes
                                                </button>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($record->lawyer_type == 'intimation')
                                                <a href="{{route('intimations.show', $record->main_id)}}"
                                                    target="_blank">
                                                    <span class="badge badge-primary">
                                                        <i class="fas fa-list mr-1"></i>Detail
                                                    </span>
                                                </a>
                                                @endif

                                                @if ($record->lawyer_type == 'lc')
                                                <a href="{{route('lower-court.show', $record->main_id)}}"
                                                    target="_blank">
                                                    <span class="badge badge-primary">
                                                        <i class="fas fa-list mr-1"></i>Detail
                                                    </span>
                                                </a>
                                                @endif

                                                @if ($record->lawyer_type == 'gc_lc')
                                                <a href="{{route('gc-lower-court.show', $record->main_id)}}"
                                                    target="_blank">
                                                    <span class="badge badge-primary"><i
                                                            class="fas fa-list mr-1"></i>Detail
                                                    </span>
                                                </a>
                                                @endif

                                                @if ($record->lawyer_type == 'hc')
                                                <a href="{{route('high-court.show', $record->main_id)}}"
                                                    target="_blank">
                                                    <span class="badge badge-primary">
                                                        <i class="fas fa-list mr-1"></i>Detail
                                                    </span>
                                                </a>
                                                @endif

                                                @if ($record->lawyer_type == 'gc_hc')
                                                <a href="{{route('gc-high-court.show', $record->main_id)}}"
                                                    target="_blank">
                                                    <span class="badge badge-primary"><i
                                                            class="fas fa-list mr-1"></i>Detail
                                                    </span>
                                                </a>
                                                @endif
                                            </td>


                                            {{-- <td>
                                                <div class="d-flex justify-content-between">

                                                    @if ($record->hc_id != null)
                                                    <a target="_blank"
                                                        href="{{ route('high-court.prints.short-detail', ['id' => $record->hc_id, 'type' => 'hc']) }}">
                                                        <span class="badge badge-primary mr-1"><i
                                                                class="fas fa-print mr-1"
                                                                aria-hidden="true"></i>Report</span></a>
                                                    @elseif (isset($record->gc_hc_id) && $record->gc_hc_id != null)
                                                    <a target="_blank"
                                                        href="{{ route('high-court.prints.short-detail', ['id' => $record->gc_hc_id, 'type' => 'gc_hc']) }}">
                                                        <span class="badge badge-primary mr-1"><i
                                                                class="fas fa-print mr-1"
                                                                aria-hidden="true"></i>Report</span></a>
                                                    @elseif ($record->lc_id != null)
                                                    <a target="_blank"
                                                        href="{{ route('lower-court.prints.short-detail', ['id' => $record->lc_id, 'type' => 'lc']) }}">
                                                        <span class="badge badge-primary mr-1"><i
                                                                class="fas fa-print mr-1"
                                                                aria-hidden="true"></i>Report</span></a>
                                                    @elseif ($record->gc_lc_id != null)
                                                    <a target="_blank"
                                                        href="{{ route('lower-court.prints.short-detail', ['id' => $record->gc_lc_id, 'type' => 'gc_lc']) }}">
                                                        <span class="badge badge-primary mr-1"><i
                                                                class="fas fa-print mr-1"
                                                                aria-hidden="true"></i>Report</span></a>
                                                    @else
                                                    N/A
                                                    @endif

                                                </div>

                                            </td> --}}
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No Record Found.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                @if ($records)
                                <div class="row">
                                    <div class="col-lg-6">
                                        Showing {{ $records->firstItem() }} to {{ $records->lastItem() }} of total
                                        {{ $records->total() }} entries
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="d-flex justify-content-end px-2 mx-2 my-2">
                                            {{ $records->links() }}
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div wire:ignore.self class="modal fade" id="rcpt_modal" tabindex="-1" role="dialog"
        aria-labelledby="roleFormModalTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">HIGH COURT - RCPT</h5>
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
                                <h5 class="text-uppercase"><b>{{$selected_lawyer_type}}: {{$selected_row_id}}</b></h5>

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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        wire:click="close">Close</button>
                    @else
                    <button type="button" class="btn btn-danger" data-dismiss="modal" wire:click="close">Cancel</button>
                    <button type="button" class="btn btn-success" wire:click="updateRcpt('{{$selected_row_id}}')">
                        Yes, Confirm it!</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

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
                            <textarea type="text" wire:model.defer="application_note" id="application_note"
                                class="form-control textarea" rows="6"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" id="accordion">
                            @foreach ($application_notes_list as $note)
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

                                <div id="collapseOne{{$note->id}}" class="collapse"
                                    aria-labelledby="headingOne{{$note->id}}" data-parent="#accordion">
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
                    <button type="button" class="btn btn-success btn-sm" wire:click.prevent="saveNotes()">Save
                        changes</button>
                </div>
            </div>
        </div>
    </div>

    @include('livewire.loader')

    <script>
        document.addEventListener('livewire:load', function () {
            $('#notes_modal').on('shown.bs.modal', function () {
                $('#application_note').summernote({
                    height: 350,
                    callbacks: {
                        onChange: function (contents) {
                            Livewire.emit('set-application-notes', contents);
                        }
                    }
                });
            });

            Livewire.on('clearApplicationNotes', function() {
                $('#application_note').summernote('code', ''); // Clears the summernote editor
            });
        });
    </script>
</div>

<style>
    .filters .form-control {
        font-size: 12px;
    }

    .filters label {
        font-size: 12px;
    }
</style>