<!-- Button trigger modal -->
<button type="button" class="btn btn-primary float-right btn-xs mr-1" data-toggle="modal" data-target="#intimationDate">
    <i class="far fa-edit mr-1"></i>Edit
</button>

<!-- Modal -->
<div class="modal fade" id="intimationDate" tabindex="-1" aria-labelledby="intimationDateLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="intimationDateLabel">Intimation Start Date</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" id="intimation_date_form" method="POST"> @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <div class="input-group date" id="intimation_start_date" data-target-input="nearest">
                            <input type="text"
                                value="{{isset($application->intimation_start_date) ? \Carbon\Carbon::parse($application->intimation_start_date)->format('d-m-Y') : ''}}"
                                class="form-control datetimepicker-input intimation_start_date"
                                data-target="#intimation_start_date" name="intimation_start_date" required
                                autocomplete="off" data-toggle="datetimepicker" />
                            <div class="input-group-append" data-target="#intimation_start_date"
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
