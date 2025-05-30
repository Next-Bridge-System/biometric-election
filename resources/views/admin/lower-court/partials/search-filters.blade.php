<form action="{{route('lower-court.index')}}" method="POST" id="search_application_form"> @csrf
    <div class="row align-items-center mb-2">
        <div class="col-md-4 form-group">
            <label><strong>Find By :</strong></label>
            <select class="form-control custom-select text-uppercase" id="find_by">
                <option value="lic_no">License No</option>
                <option value="name">Name</option>
                <option value="father">Father/Husband</option>
                <option value="dob">DOB</option>
                <option value="leg_no">Ledger No</option>
                <option value="cnic">CNIC</option>
                <option value="enr_date">Enrollment Date</option>
                <option value="bf_no">BF No</option>
            </select>
        </div>
        <div class="col-md-4 form-group">
            <label><strong>Find Data :</strong></label>
            <input type="text" name="find_data" id="find_data" class="form-control">
        </div>
        <div class="col-md-4 form-group">
            <label><strong>Filter On Station</strong></label>
            <select class="form-control custom-select" id="bar_id" name="bar_id">
                <option value="">--Select Voter member--</option>
                @foreach (App\Bar::orderBy('name','asc')->get() as $bar)
                <option value="{{$bar->id}}">{{$bar->name}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <p>
        <a class="btn btn-info btn-sm" data-toggle="collapse" href="#collapseExample" role="button"
            aria-expanded="false" aria-controls="collapseExample">
            <i class="fas fa-plus mr-2"></i>Additional Filters
        </a>
    </p>
    <div class="collapse" id="collapseExample">
        <div class="card card-body bg-light">
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
                <div class="col-md-4 form-group">
                    <label><strong>Application System Status</strong></label>
                    <select class="form-control custom-select" id="app_system_status" name="app_system_status">
                        <option value="">--Select System Status--</option>
                        <option value="direct-entry">Direct Entry</option>
                        <option value="move-from-intimation">Move From Intimation</option>
                    </select>
                </div>

                <div class="col-md-4 form-group">
                    <div class="row">
                        <div class="col"><label><strong>Age From:</strong></label>
                            <input type="text" name="age_from" id="age_from" class="form-control" placeholder="Age From">
                        </div>
                        <div class="col"><label><strong>Age To:</strong></label>
                            <input type="text" name="age_to" id="age_to" class="form-control" placeholder="Age To">
                        </div>
                    </div>

                </div>

                <div class="col-md-4 form-group">
                    <label><strong>University :</strong></label>
                    <select class="form-control custom-select" id="university">
                        <option value="" selected>--Select University--</option>
                        @foreach ($universities as $university)
                        <option value="{{$university->id}}">{{$university->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</form>
