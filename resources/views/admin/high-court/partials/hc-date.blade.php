<button type="button" class="btn btn-link float-right btn-sm" data-toggle="modal" data-target="#lcDate">
    <i class="far fa-edit"></i>
</button>

<div class="modal fade" id="lcDate" tabindex="-1" aria-labelledby="hcDateLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hcDateLabel">High court final date</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" id="hc_date_form" method="POST"> @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <div class="input-group date" id="hc_date" data-target-input="nearest">
                            <input type="text" value="{{getDateFormat($application->hc_date)}}"
                                class="form-control datetimepicker-input hc_date" data-target="#hc_date" name="hc_date"
                                required autocomplete="off" data-toggle="datetimepicker" />
                            <div class="input-group-append" data-target="#hc_date" data-toggle="datetimepicker">
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