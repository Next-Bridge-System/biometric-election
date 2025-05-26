@if ($application->rcpt_date == NULL &&
permission('add-intimation-rcpt-date') && $application->is_intimation_completed == 0)
<button type="button" class="btn btn-primary float-right btn-xs mr-1" data-toggle="modal"
    data-target="#rcpt_current_date_modal">
    <i class="fas fa-plus mr-1"></i>Add Current Date
</button>

<div class="modal fade" id="rcpt_current_date_modal" tabindex="-1" aria-labelledby="rcpt_current_date_modal_label"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rcpt_current_date_modal_label">RCPT - Current Date</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" id="rcpt_current_date_form" method="POST"> @csrf
                <div class="modal-body">
                    <h5 class="modal-title" id="rcpt_current_date_modal_label">Are you sure, you want to add RCPT -
                        Current Date
                        to this application. This action cannot be undone.</h5>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm">Yes Verify</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@if (permission('edit-intimation-rcpt-date') && $application->is_intimation_completed == 0)
<button type="button" class="btn btn-primary float-right btn-xs mr-1" data-toggle="modal"
    data-target="#rcpt_date_modal">
    <i class="far fa-edit mr-1"></i>Edit
</button>

<div class="modal fade" id="rcpt_date_modal" tabindex="-1" aria-labelledby="rcpt_date_modal_label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rcpt_date_modal_label">RCPT Date</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" id="rcpt_date_form" method="POST"> @csrf
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
                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
