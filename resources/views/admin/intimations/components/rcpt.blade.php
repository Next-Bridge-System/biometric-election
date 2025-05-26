@php
$number_type = html_entity_decode($type);
$application = App\Application::where('id',$application_id)->first();
$number = App\Application::where('id',$application_id)->pluck($number_type)->toArray();
@endphp

@if ($type != 'rcpt_no')
<span> {{$number[0] ?? 'N/A'}} </span>
@if ($number[0] == null)
@if (permission('add-intimation-rcpt-date'))
<button type="button" class="btn btn-primary float-right btn-xs mr-1 lc_number_btn" data-toggle="modal"
    data-target="#{{$type}}_modal"><i class="fas fa-plus mr-1"></i>Add</button>
@endif
@else
@if (permission('edit-intimation-rcpt-date'))
<button type="button" class="btn btn-primary float-right btn-xs mr-1 lc_number_btn" data-toggle="modal"
    data-target="#{{$type}}_modal"><i class="far fa-edit mr-1"></i>Edit</button>
@endif
@endif

<div class="modal fade" id="{{$type}}_modal" tabindex="-1" aria-labelledby="{{$type}}_modal_label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase" id="{{$type}}_modal_label">{{$type}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" class="lc_number_form" id="{{$type}}_form" method="POST"> @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" name="lc_number" id="{{$type}}" class="form-control"
                            value="{{$number[0] ?? ''}}" required>
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

@if ($type == 'rcpt_no')
@if ($application->rcpt_date)
<span>{{date('d-m-Y',strtotime($application->rcpt_date))}}</span>
<span class="badge badge-primary ml-2"> {{$number[0]}}</span>
@else N/A @endif

@if ($application->is_intimation_completed == 0)
<section>
    @if ($application->rcpt_date == null && permission('add-intimation-rcpt-date'))
    <button type="button" class="btn btn-primary float-right btn-xs mr-1" data-toggle="modal"
        data-target="#add_rcpt_current_date_modal">
        <i class="fas fa-plus mr-1"></i>Add</button>

    <div class="modal fade" id="add_rcpt_current_date_modal" tabindex="-1"
        aria-labelledby="add_rcpt_current_date_modal_label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="add_rcpt_current_date_modal_label">RCPT - Current Date</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="#" class="rcpt_current_date_form" method="POST"> @csrf
                    <div class="modal-body">
                        @if ($application->rcpt_date == null)
                        <h5 class="modal-title" id="add_rcpt_current_date_modal_label">Are you sure, you want to add
                            RCPT -
                            Current Date to this application. This action cannot be undone.</h5>
                        @else
                        <div class="form-group">
                            <div class="input-group date" id="rcpt_date" data-target-input="nearest">
                                <input type="text"
                                    value="{{isset($application->rcpt_date) ? \Carbon\Carbon::parse($application->rcpt_date)->format('d-m-Y') : ''}}"
                                    class="form-control datetimepicker-input rcpt_date" data-target="#rcpt_date"
                                    name="rcpt_date" required autocomplete="off" data-toggle="datetimepicker" />
                                <div class="input-group-append" data-target="#rcpt_date" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-sm">Yes, Verify & Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    @if (permission('edit-intimation-rcpt-date'))
    <button type="button" class="btn btn-primary float-right btn-xs mr-1" data-toggle="modal"
        data-target="#edit_rcpt_current_date_modal">
        <i class="fas fa-edit mr-1"></i>Edit</button>

    <div class="modal fade" id="edit_rcpt_current_date_modal" tabindex="-1"
        aria-labelledby="edit_rcpt_current_date_modal_label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edit_rcpt_current_date_modal_label">RCPT - Current Date</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="#" class="rcpt_current_date_form" method="POST"> @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="input-group date" id="rcpt_date" data-target-input="nearest">
                                <input type="text"
                                    value="{{isset($application->rcpt_date) ? \Carbon\Carbon::parse($application->rcpt_date)->format('d-m-Y') : ''}}"
                                    class="form-control datetimepicker-input rcpt_date" data-target="#rcpt_date"
                                    name="rcpt_date" required autocomplete="off" data-toggle="datetimepicker" />
                                <div class="input-group-append" data-target="#rcpt_date" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-sm">Yes, Verify & Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</section>
@endif

@endif