@extends('layouts.admin')

@section('styles')
{{--
<link rel="stylesheet" href="{{asset('public/digital-persona/css/bootstrap-min.css')}}"> --}}
<link rel="stylesheet" href="{{asset('public/digital-persona/app.css')}}" type="text/css" />
<style>
    #saveAndFormats {
        position: fixed;
        left: 830px;
        top: -750px;
        width: 135px;
    }
</style>
@endsection

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                {{-- <h1>Biometric Verification "{{getLawyerName($application->id)}}"</h1> --}}
            </div>
            <div class="col-sm-6">
                <h1 class="breadcrumb float-sm-right">
                    {{-- Application # {{$application->application_token_no}} --}}
                </h1>
            </div>
            <div class="col-md-12">
                {{-- @if ($application->fingerprints->count() > 0)
                <a href="{{route('biometrics.pdf',$application->id)}}" target="_blank"
                    class="btn btn-info btn-sm mt-2 float-right"><i class="fas fa-print mr-1"
                        aria-hidden="true"></i>Print Token</a>
                @endif --}}
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
                        <h3 class="card-title">FINGER PRINT</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <div class="card-body">
                        <div class="container">
                            <span id="show_selected_finger"></span>
                        </div>
                        <div id="Container">
                            <nav class="navbar navbar-inverse">
                                <div class="container-fluid">
                                    <ul class="nav navbar-nav">
                                        <li id="Reader" class="active">
                                            <button type="button" class="btn btn-primary btn-block"
                                                onclick="toggle_visibility(['content-reader','content-capture']);setActive('Reader','Capture')">
                                                Reader</button>
                                        </li>
                                    </ul>
                                    <ul class="nav navbar-nav">
                                        <li id="Capture" class="">
                                            <button type="button" class="btn btn-primary btn-block"
                                                onclick="toggle_visibility(['content-capture','content-reader']);setActive('Capture','Reader')">
                                                Capture</button>
                                        </li>
                                    </ul>
                                </div>
                            </nav>
                            <div id="Scores">
                                <h5 class="mt-2">Scan Quality : <input type="text" id="qualityInputBox" size="20"
                                        style="background-color:#DCDCDC;text-align:center;"></h5>

                            </div>
                            <div id="content-capture" style="display : none;">
                                <div id="status"></div>
                                <div id="imagediv"></div>
                                <div id="contentButtons">
                                    <table align="center">
                                        <tr>
                                            <td>
                                                @include('admin.biometric.finger')
                                                <input type="button" class="btn btn-primary btn-lg mr-1" id="start"
                                                    value="Start Scan" onclick="Javascript:onStart()">
                                            </td>
                                            <td data-toggle="tooltip" title="Will work with the .png format.">
                                                <input type="button" class="btn btn-success btn-lg mr-1" id="save"
                                                    value="Save Fingerprint">
                                            </td>
                                            <td>
                                                <input type="button" class="btn btn-warning btn-lg mr-1" id="stop"
                                                    value="Stop Scan" onclick="Javascript:onStop()">
                                            </td>
                                            <td>
                                                <input type="button" class="btn btn-danger btn-lg" id="clearButton"
                                                    value="Clear Result" onclick="Javascript:onClear()">
                                            </td>
                                            {{-- <td>
                                                <input type="button" class="btn btn-primary" id="info" value="Info"
                                                    onclick="Javascript:onGetInfo()">
                                            </td> --}}
                                    </table>
                                </div>

                                <div id="imageGallery" style="display:none">
                                </div>
                                {{-- <div id="deviceInfo">
                                </div> --}}

                                <div id="saveAndFormats">
                                    <form name="myForm" style="border : solid grey;padding:5px;">
                                        <b>Formats :</b><br>
                                        <table>
                                            <tr data-toggle="tooltip" title="Will save data to a .raw file.">
                                                <td>
                                                    <input type="checkbox" name="Raw" value="1"
                                                        onclick="checkOnly(this)"> RAW <br>
                                                </td>
                                            </tr>
                                            <tr data-toggle="tooltip" title="Will save data to a Intermediate file">
                                                <td>
                                                    <input type="checkbox" name="Intermediate" value="2"
                                                        onclick="checkOnly(this)"> Feature
                                                    Set<br>
                                                </td>
                                            </tr>
                                            <tr data-toggle="tooltip" title="Will save data to a .wsq file.">
                                                <td>
                                                    <input type="checkbox" name="Compressed" value="3"
                                                        onclick="checkOnly(this)"> WSQ<br>
                                                </td>
                                            </tr>
                                            <tr data-toggle="tooltip" title="Will save data to a .png file.">
                                                <td>
                                                    <input type="checkbox" name="PngImage" checked="true" value="4"
                                                        onclick="checkOnly(this)">
                                                    PNG
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                    <br>
                                    <input type="button" class="btn btn-primary" id="saveImagePng" value="Export"
                                        onclick="Javascript:onImageDownload()">
                                </div>

                            </div>

                            <div id="content-reader" class="col-md-6 offset-md-3">
                                <h5 class="mt-4">Select Finger Reader :</h5>
                                <select class="form-control" id="readersDropDown" onchange="selectChangeEvent()">
                                </select>
                                <div id="readerDivButtons">
                                    <table width=70% align="center">
                                        <tr>
                                            <td>
                                                <input type="button" class="btn btn-primary" id="refreshList"
                                                    value="Refresh List"
                                                    onclick="Javascript:readersDropDownPopulate(false)">
                                            </td>
                                            <td>
                                                <input type="button" class="btn btn-primary" id="capabilities"
                                                    value="Capabilities" data-toggle="modal" data-target="#myModal"
                                                    onclick="Javascript:populatePopUpModal()">
                                            </td>
                                        </tr>
                                    </table>

                                    <!-- Modal - Pop Up window content-->
                                    <div class="modal fade" id="myModal" role="dialog">
                                        <div class="modal-dialog">

                                            <!-- Modal content -->
                                            <div class="modal-content" id="modalContent">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Reader Information</h4>
                                                </div>
                                                <div class="modal-body" id="ReaderInformationFromDropDown">

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default"
                                                        data-dismiss="modal">Close</button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <fieldset class="border p-4 mt-4">
                            <legend class="w-auto">FINGERPRINTS -
                                {{-- {{getLawyerName($application->id)}} --}}
                            </legend>
                            <div class="row">
                                <table class="table table-sm table-striped table-bordered text-center">
                                    <thead>
                                        <tr>
                                            <th>Sr.no</th>
                                            <th>Fingerprint</th>
                                            <th>Activity Log</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    {{-- @forelse ($application->fingerprints as $fp)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>
                                            <img src="{{asset('storage/app/public/'.$fp->uri)}}"
                                                class="custom-image-preview" alt=""> <br>
                                            <b>{{getFingerName($fp->finger)}}</b>
                                        </td>
                                        <td>
                                            Created At: {{$fp->created_at}} <br>
                                            Updated At: {{$fp->updated_at}} <br>
                                            Created By: {{getAdminName($fp->created_by)}} <br>
                                            Updated By: {{getAdminName($fp->updated_by)}} <br>
                                        </td>
                                        <td>
                                            <button class="btn btn-danger btn-sm" @if (Auth::guard('admin')->user()
                                                ->hasPermission('delete-biometric-fingerprint'))
                                                onclick="destroyFingerprint('{{$fp->id}}')" @else disabled
                                                @endif>Delete</button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td class="text-danger text-center" colspan="4">No Record Found.</td>
                                    </tr>
                                    @endforelse --}}
                                </table>
                            </div>
                        </fieldset>
                    </div>
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
<script src="{{asset('public/digital-persona/lib/jquery.min.js')}}"></script>
<script src="{{asset('public/digital-persona/lib/bootstrap.min.js')}}"></script>
<script src="{{asset('public/digital-persona/scripts/es6-shim.js')}}"></script>
<script src="{{asset('public/digital-persona/scripts/websdk.client.bundle.min.js')}}"></script>
<script src="{{asset('public/digital-persona/scripts/fingerprint.sdk.min.js')}}"></script>
<script src="{{asset('public/digital-persona/app.js')}}"></script>

<script>
    function biometricVerification(uri){
        var finger = $('#finger').val();

        $.ajax({
            method: "POST",
            url: '{{route('biometrics.store')}}',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                'uri': uri,
                'finger': finger,
            },
            success: function (response) {
               if (response.status == 1) {
                    Swal.fire('Fingerprint Saved!',response.message,'success').then(function() {
                        $(".custom-loader").removeClass('hidden');
                        window.location.reload();
                    });
               } else {
                   Swal.fire('Error!',response.message,'error');
               }
            },
        });
    }

    function destroyFingerprint(id) {
        var url = '{{ route("biometrics.destroy", ":id") }}';
        url = url.replace(':id', id);
        var token = $("meta[name='csrf-token']").attr("content");

        Swal.fire({
                title: 'Are you sure you want to delete this fingerprint?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'red',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes! Delete it.'
            }).then((result) => {
            if (result.value) {
                $.ajax({
                        url:url,
                        type: 'DELETE',
                        data: {
                        "id": id,
                        "_token": token,
                    },
                    beforeSend: function(){
                        $(".custom-loader").removeClass('hidden');
                    },
                    success: function (){
                        location.reload();
                    }
                });
            }
        })
    }
</script>
@endsection
