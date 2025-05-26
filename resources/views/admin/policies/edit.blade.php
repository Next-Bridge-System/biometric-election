@extends('layouts.admin')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Policies</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('policies.index')}}" class="btn btn-dark">Back</a></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Edit Policy</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="{{route('policies.update',$policy->id)}}" method="POST" enctype="multipart/form-data"> @csrf
                            <div class="card-body">

                                <div class="row">
                                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                        <label>Title <span class="required-star">*</span></label>
                                        <input type="text" maxlength="100" class="form-control" name="title"
                                               value="{{ $policy->title }}" required>
                                        @error('title')
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Start Date <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group date" id="startDate" data-target-input="nearest">
                                            <input type="text"
                                                   value="{{ date('d-m-Y',strtotime($policy->start_date)) }}"
                                                   class="form-control datetimepicker-input" data-target="#startDate"
                                                   name="start_date" required autocomplete="off"
                                                   data-toggle="datetimepicker" />
                                            <div class="input-group-append" data-target="#startDate"
                                                 data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                        @error('start_date')
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>End Date <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group date" id="endDate" data-target-input="nearest">
                                            <input type="text"
                                                   value="{{ date('d-m-Y',strtotime($policy->end_date)) }}"
                                                   class="form-control datetimepicker-input" data-target="#endDate"
                                                   name="end_date" required autocomplete="off"
                                                   data-toggle="datetimepicker" />
                                            <div class="input-group-append" data-target="#endDate"
                                                 data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                        @error('end_date')
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Policy Image: </label>
                                        <input type="file" id="policy_url" name="policy_url" class="form-control"
                                               accept="image/jpg,image/jpeg,image/png">
                                        <img style="max-width:100px;height:auto" class="mt-2" src="{{ asset('storage/app/public/'.$policy->policy_url) }}" alt="">
                                        @error('policy_url')
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                </div>

                                <fieldset class="border p-4 mb-4">
                                    <legend class="w-auto">Policy Fees</legend>
                                    <div class="row">
                                        <div class="form-group col-12 col-md-8 offset-md-2">
                                            <label>Application Type</label>
                                            <select class="form-control form-select" name="application_type" id="application_type">
                                                <option value="">-- Choose --</option>
                                                <option {{ $policy->application_type == '1' ? 'selected' : '' }} value="1">Intimation</option>
                                                <option {{ $policy->application_type == '2' ? 'selected' : '' }} value="2">Lower Court</option>
                                                {{--<option value="3">Higher Court</option>--}}
                                            </select>
                                        </div>

                                        <div class="dynamic-div col-12">
                                            @if($policy->application_type == 1)
                                                @foreach($policy->policyFees as $key => $item)
                                                    <div class="row">
                                                        <div class="form-group col-sm-6 col-md-4 offset-md-2">
                                                            <label for="">Age Group</label>
                                                            <input class="form-control" type="text" name="policy_fees[{{ $key }}][age_group]" value="{{ !$loop->last ? $item->from.' - '.$item->to : $item->from }}" readonly required>
                                                        </div>
                                                        <div class="form-group col-sm-6 col-md-4 ">
                                                            <label for="">Amount</label>
                                                            <input class="form-control" type="number" name="policy_fees[{{ $key }}][amount]" value="{{ $item->amount }}" required>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </fieldset>


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
    <script>
        //Initialize Select2 Elements
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })

        $('#startDate').datetimepicker({
            size: 'large',
            format: 'DD-MM-YYYY',
        });

        $('#endDate').datetimepicker({
            size: 'large',
            format: 'DD-MM-YYYY',
        });

        $('#application_type').on('change',function(){
            console.log($(this).val())
            let obj = [];
            let val = $(this).val();
            let ageGroup = ['1 - 35','35 - 40','40 - 50','50 - 60','60+'];
            if(val == 1){
                for(let i = 0; i < 5; i++){
                    obj += `<div class="row">
                                                <div class="form-group col-sm-6 col-md-4 offset-md-2">
                                                    <label for="">Age Group</label>
                                                    <input class="form-control" type="text" name="policy_fees[${i}][age_group]" value="${ageGroup[i]}" readonly required>
                                                </div>
                                                <div class="form-group col-sm-6 col-md-4 ">
                                                    <label for="">Amount</label>
                                                    <input class="form-control" type="number" name="policy_fees[${i}][amount]" required>
                                                </div>
                                            </div>`;
                }

                if($('.dynamic-div .row').length != 5){
                    $('.dynamic-div').empty();
                    $('.dynamic-div').append(obj);
                }

            }else{
                $('.dynamic-div').empty();
            }
        })
    </script>

@endsection
