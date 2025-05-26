@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Members</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('members.index')}}" class="btn btn-dark">Back</a>
                    </li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- jquery validation -->
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Add Member</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form action="{{route('members.store')}}" method="POST" enctype="multipart/form-data"> @csrf
                        <div class="card-body">

                            <div class="row">
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Name <span class="required-star">*</span></label>
                                    <input type="text" maxlength="100" class="form-control" name="name"
                                        value="{{ old('name') }}">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Father Name</label>
                                    <input type="text" maxlength="100" class="form-control" name="father_name"
                                        value="{{ old('father_name') }}">
                                    @error('father_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Committee Name</label>
                                    <input type="text" maxlength="100" class="form-control" name="committee_name"
                                        value="{{ old('committee_name') }}">
                                    @error('committee_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Mobile No<span class="required-star">*</span></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">+92</span>
                                        </div>
                                        <input type="text" maxlength="100" class="form-control" name="mobile_no"
                                            value="{{ old('mobile_no') }}">
                                    </div>

                                    @error('mobile_no')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-sm-12">
                                    <label>Address<span class="required-star">*</span></label>
                                    <input type="text" maxlength="100" class="form-control" name="address"
                                        value="{{ old('address') }}">
                                    @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Division</label>
                                    <select class="form-control form-select division_id" name="division_id">
                                        <option value="">-- Choose --</option>
                                        @foreach($divisions as $division)
                                        <option value="{{$division->id}}" {{ old('division_id'==$division->id ?
                                            'selected' : '') }}>{{ $division->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('division_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>District</label>
                                    <select class="form-control form-select district_id" name="district_id" {{
                                        old('district_id') !=null ? 'data-id="' .old('district_id').'"' : 'disabled' }}>
                                        <option value="">-- Choose --</option>

                                    </select>
                                    @error('district_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Tehsil</label>
                                    <select class="form-control form-select tehsil_id" name="tehsil_id" {{
                                        old('tehsil_id') !=null ? 'data-id="' .old('tehsil_id').'"' : 'disabled' }}>
                                        <option value="">-- Choose --</option>
                                    </select>
                                    @error('tehsil_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Bar</label>
                                    <select class="form-control form-select bar_id" name="bar_id" {{ old('bar_id')
                                        !=null ? 'data-id="' .old('bar_id').'"' : 'disabled' }}>
                                        <option value="">-- Choose --</option>
                                    </select>
                                    @error('bar_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Tenure Start Date <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group date" id="startDate" data-target-input="nearest">
                                        <input type="text"
                                               class="form-control datetimepicker-input" data-target="#startDate"
                                               name="tenure_start_date" required autocomplete="off"
                                               data-toggle="datetimepicker" />
                                        <div class="input-group-append" data-target="#startDate"
                                             data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                    @error('tenure_start_date')
                                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Tenure End Date <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group date" id="endDate" data-target-input="nearest">
                                        <input type="text"
                                               class="form-control datetimepicker-input" data-target="#endDate"
                                               name="tenure_end_date" required autocomplete="off"
                                               data-toggle="datetimepicker" />
                                        <div class="input-group-append" data-target="#endDate"
                                             data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                    @error('tenure_end_date')
                                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Signature Image: <span class="text-danger">*</span></label>
                                    <input type="file" id="signature_url" name="signature_url" class="form-control"
                                           accept="image/jpg,image/jpeg,image/png" required>
                                    <img src="{{ old('signature_url') }}" alt="">
                                    @error('signature_url')
                                    <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Designation</label>
                                    <select class="form-control form-select" name="designation">
                                        <option value="">-- Choose --</option>
                                        <option value="Chairman">Chairman</option>
                                        <option value="Vice Chairman">Vice Chairman</option>
                                        <option value="Secretary">Secretary</option>

                                    </select>
                                    @error('designation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success float-right">Save & Submit</button>
                        </div>
                    </form>
                </div>
                <!-- /.card -->
            </div>
            <!--/.col (left) -->
            <!-- right column -->
            <div class="col-md-6">

            </div>
            <!--/.col (right) -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

@endsection


@section('scripts')
<script src="{{asset('public/js/app.js')}}"></script>
<script>


    $('#startDate').datetimepicker({
        size: 'large',
        format: 'DD-MM-YYYY',
    });

    $('#endDate').datetimepicker({
        size: 'large',
        format: 'DD-MM-YYYY',
    });


    $(document).on("submit", 'form', function (event) {
            event.preventDefault();
            url = event.target.action;
            console.log(event.target.action);
            $('span.text-success').remove();
            $('span.invalid-feedback').remove();
            $('input.is-invalid').removeClass('is-invalid');
            var formData = new FormData(this);
            $.ajax({
                method: "POST",
                data: formData,
                url: url,
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function () {
                    $(".custom-loader").removeClass('hidden');
                },
                success: function (response) {
                    if(response.status == 1){
                        notifyDialogBoxReDirect('success','Record Added',response.message,response.redirect_url)
                    }else{
                        notifyBlackToast(response.message);
                        $(".custom-loader").addClass('hidden');
                    }
                },
                error: function (errors) {
                    errorsGet(errors.responseJSON.errors)
                    $(".custom-loader").addClass('hidden');
                }
            });
        });

        $('.division_id').on('change',function(){
            let division_id = $(this).find(":selected").val();
            $.ajax({
                method: "POST",
                url: '{{route('getDistrictByDivision')}}',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    'division_id': division_id
                },
                success: function (response) {
                    appendSelectOption($('.district_id'),response.district);
                }
            });
        })

        $('.district_id').on('change',function(){
            let district_id = $(this).find(":selected").val();
            $.ajax({
                method: "POST",
                url: '{{route('getTehsilsByDistrict')}}',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    'district_id': district_id
                },
                success: function (response) {
                    appendSelectOption($('.tehsil_id'),response.tehsils);
                }
            });
        })

        $('.tehsil_id').on('change',function(){
            let tehsil_id = $(this).find(":selected").val();
            $.ajax({
                method: "POST",
                url: '{{route('getBarsByTehsil')}}',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    'tehsil_id': tehsil_id
                },
                success: function (response) {
                    appendSelectOption($('.bar_id'),response.bars);
                }
            });
        })

        function appendSelectOption(selector,responseArray){
            let option = '';
            let id = selector.data('id');
            selector.empty();
            selector.append(' <option value="" selected>-- Choose --</option>');
            responseArray.forEach(function (item, index) {
                let ifSelect = (id != undefined && id == item.id) ? "selected" : "";
                option = "<option value='" + item.id + "' "+ ifSelect +">" + item.name + "</option>"
                selector.append(option);
            });
            if(responseArray.length > 0){
                selector.prop('disabled',false);
            }
        }

        @if(old('division_id') != null)
            $('.division_id').trigger('change');
            @if(old('district_id') != null)
                $('.district_id').trigger('change');
                @if(old('tehsil_id') != null)
                    $('.tehsil_id').trigger('change');
                @endif
            @endif
        @endif


</script>

@endsection
