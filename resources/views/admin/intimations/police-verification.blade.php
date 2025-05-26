@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Police Verification</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">

                    </li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-gradient-gray-dark">
                        <h3 class="card-title ">
                            Police Verification
                        </h3>
                        <div class="card-tools">
                            @if(isset($records['message']) && $records['message'] != null)
                            @else
                                <a href="{{ route('police-verification.exportPDF',$application->id) }}"
                                   class="btn btn-primary btn-sm" target="_blank">Export PDF</a>
                            @endif
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">

                        <div>
                            <label for="">Application No</label>
                            <h4 class="border-bottom pb-3"> {{ $application->application_token_no }}</h4>
                        </div>
                        <div>
                            <label for="">Name</label>
                            <h4 class="border-bottom pb-3"> {{ getUserName($application->id) }}</h4>
                        </div>
                        <div>
                            <label for="">Application Type</label>
                            <h4 class="border-bottom pb-3"> {{ getApplicationType($application->id) }}</h4>
                        </div>

                        <table id="bars_table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width:20px">#</th>
                                    <th>Fir District</th>
                                    <th>Fir PS</th>
                                    <th>Fir No.</th>
                                    <th>Fir Year</th>
                                    <th>Fir Offence Date</th>
                                    <th>Fir Offecnce</th>
                                    <th>Fir Status</th>
                                    <th>Sus. Name</th>
                                    <th>Sus. Parent_name</th>
                                    <th>Sus. Gender</th>
                                    <th>Sus. Cast</th>
                                    <th>Sus. Address</th>
                                    <th>Sus. Phone</th>
                                    <th>Sus. Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $json = remove_utf8_bom($application->fir->data);
                                $records = json_decode($json, TRUE);

                                @endphp
                                @if(isset($records['message']) && $records['message'] != null)
                                    <tr>
                                        <td colspan="15">{{ $records['message'] }}</td>
                                    </tr>
                                @else
                                    @foreach($records as $key => $record)
                                        <tr>
                                            <td style="width:20px">{{ $key + 1 }}</td>
                                            <td>{{ $record['fir_district'] }}</td>
                                            <td>{{ $record['fir_ps'] }}</td>
                                            <td>{{ $record['fir_no'] }}</td>
                                            <td>{{ $record['fir_year'] }}</td>
                                            <td>{{ $record['fir_offence_date'] }}</td>
                                            <td>{{ $record['fir_offecnce'] }}</td>
                                            <td>{{ $record['fir_status'] }}</td>
                                            <td>{{ $record['sus_name'] }}</td>
                                            <td>{{ $record['sus_parent_name'] }}</td>
                                            <td>{{ $record['sus_gender'] }}</td>
                                            <td>{{ $record['sus_cast'] }}</td>
                                            <td>{{ $record['sus_address'] }}</td>
                                            <td>{{ $record['sus_phone'] }}</td>
                                            <td>{{ $record['sus_status'] }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>

                        <h4>Verification Status</h4>
                        @if($application->fir->verified == 1)
                        <span class="badge badge-success">Verified</span> By {{
                        getAdminName($application->fir->verified_by) ?? "Unknown" }}
                        @else
                        <span class="badge badge-warning" onclick="changeStatus(1)">Unverified</span> <i
                            class="fa fa-arrow-right"></i> <a href="javascript:void(0)" class="badge badge-success "
                            onclick="changeStatus(1)">Verify</a>
                        @endif
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->



@endsection

@section('scripts')
<script src="{{asset('public/js/app.js')}}"></script>
<script>
    $(function () {
            $("#bars_table").DataTable({
                dom: 'tip',
                "responsive": true,
                "autoWidth": false,
            });
        });

        jQuery(document).ready(function () {
            App.init();
        });

        function changeStatus(status){
            Swal.fire({
                title: 'Are you sure?',
                text: "you want to verify!",
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes'
            }).then((result) => {
                console.log(result)
                if (result.value) {
                    $(".custom-loader").removeClass('hidden');
                    $.ajax({
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            'application_id': "{{$application->id}}",
                            'status': status,
                        },
                        url: '{{route('police-verification.changeStatus')}}',
                        beforeSend: function () {

                        },
                        success: function (response) {
                            if (response.status == 1) {
                                notifyBlackToastWithRedirect(response.message,'{{ URL::current() }}');
                            }
                        },
                        error: function (errors) {
                            errorsGet(errors.responseJSON.errors)
                            $(".save_btn").show();
                            $(".loading_btn").addClass('hidden');
                        }
                    });
                }
            })

        }
</script>
@endsection
