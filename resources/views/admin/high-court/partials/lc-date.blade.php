<button type="button" class="btn btn-link float-right btn-sm" data-toggle="modal" data-target="#enr_date_lc_modal">
    <i class="far fa-edit"></i>
</button>

<div class="modal fade" id="enr_date_lc_modal" tabindex="-1" aria-labelledby="enr_date_lc" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="enr_date_lc">Lower Court Enr Date</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" id="enr_date_lc_form" method="POST"> @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <div class="input-group date" id="enr_date_lc" data-target-input="nearest">
                            <input type="text" value="{{getDateFormat($application->enr_date_lc)}}"
                                class="form-control datetimepicker-input enr_date_lc"
                                data-target="#enr_date_lc" name="enr_date_lc" required autocomplete="off"
                                data-toggle="datetimepicker" />
                            <div class="input-group-append" data-target="#enr_date_lc"
                                data-toggle="datetimepicker">
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