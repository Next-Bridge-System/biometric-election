<form action="{{route('lower-court.index')}}" method="POST" id="search_application_form"> @csrf
    <div class="row align-items-center mb-2">
        <div class="col-md-4 form-group">
            <label><strong>Application Date :</strong></label>
            <select class="form-control custom-select" id="application_date">
                <option value="">--Select Date--</option>
                <option value="1">Today</option>
                <option value="2">Yesterday</option>
                <option value="3">Last 7 Days</option>
                <option value="4">Last 30 Days</option>
                <option value="5">Custom Date Range</option>
            </select>
        </div>
        <div class="col-md-4 form-group hidden" id="custom_date_range">
            <label>Custom Date Range:</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                    </span>
                </div>
                <input type="text" class="form-control float-right" id="custom_date_range_input"
                    value="{{date('m')}}/01/{{date('Y')}} - {{date('m')}}/20/{{date('Y')}}" disabled>
            </div>
        </div>
        <div class="col-md-4 form-group">
            <label><strong>Application Submitted By :</strong></label>
            <select class="form-control custom-select" id="application_submitted_by">
                <option value="" selected>--Select Submitted By--</option>
                <option value="frontend">Online</option>
                <option value="operator">Admins</option>
            </select>
        </div>
        <div class="col-md-4 form-group">
            <label><strong>Payment Status :</strong></label>
            <select class="form-control custom-select" id="payment_status">
                <option value="" selected>--Select Payment Status--</option>
                <option value="paid">Paid</option>
                <option value="unpaid">Unpaid</option>
            </select>
        </div>
        <div class="col-md-4 form-group">
            <label><strong>Payment Type :</strong></label>
            <select class="form-control custom-select" id="payment_type">
                <option value="" selected>--Select Type--</option>
                <option value="online">Online</option>
                <option value="operator">Admins</option>
            </select>
        </div>
    </div>
</form>
