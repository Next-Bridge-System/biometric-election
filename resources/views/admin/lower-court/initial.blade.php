@extends('layouts.admin')

@section('content')

<section class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Manage Lower Court Applications</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{route('lower-court.index')}}" class="btn btn-dark">Back</a>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12" id="stepManagement">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">LOWER COURT APPLICATION</h3>
                    </div>
                    <div class="card-body">
                        <form action="#" method="POST" id="search_application" enctype="multipart/form-data"> @csrf
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="">Application Type</label>
                                    <select class="form-control custom-select" name="search_application_type"
                                        id="search_application_type">
                                        <option value="">--Select Type --</option>
                                        <option value="1">New Application</option>
                                        <option value="2">Move From Intimation</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6 intimation hidden">
                                    <label for="">CNIC No.</label>
                                    <input type="text" class="form-control" name="search_cnic_no" id="search_cnic_no">
                                </div>
                                <div class="form-group col-md-12 hidden intimation">
                                    <button type="submit" class="btn btn-success btn-sm">Search Application</button>
                                </div>
                            </div>
                        </form>

                        <div class="record hidden">
                            <h4 class="text-success">Record Found</h4>
                            FIRST NAME: <span id="get_first_name"></span> <br>
                            LAST NAME: <span id="get_last_name"></span> <br>
                            CNIC NO: <span id="get_cnic_no"></span>
                            <br><br>
                            <div id="errorBox" class="hidden">
                                <h6 class="text-danger">Reasons</h6>
                                <ul id="errors-if">

                                </ul>
                            </div>
                            <div id="objectionsBox" class="hidden">
                                <h6 class="text-danger">Objections</h6>
                                <ul id="objections-if">

                                </ul>
                            </div>
                            <a href="#" id="move_application" class="btn btn-success float-right m-1 hidden">Move
                                Application</a>
                        </div>
                        {{--<a href="#" id="existing_btn" class="btn btn-success float-right m-1 hidden record">Continue
                            to exiting application</a>--}}
                        @include('admin.lower-court.partials.register-modal')
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script src="{{asset('public/js/app.js')}}"></script>

<script>
    jQuery(document).ready(function () {
        App.init();
    });

    $(document).ready(function(){

        $('#search_application_type').change(function (e) {
            e.preventDefault();
            if (this.value == 1) {
                $(".intimation").addClass('hidden');
                $(".lower-court").removeClass('hidden');
                $(".record").addClass('hidden');
            } else {
                $(".intimation").removeClass('hidden');
                $(".lower-court").addClass('hidden');
            }
        });
      $("#search_application").on("submit", function(event){
          event.preventDefault();
          $('span.text-success').remove();
          $('span.invalid-feedback').remove();
          $('input.is-invalid').removeClass('is-invalid');
          var formData = new FormData(this);
          $.ajax({
              method: "POST",
              data: formData,
              url: '{{route('lower-court.initial-step')}}',
              processData: false,
              contentType: false,
              cache: false,
              beforeSend: function(){
                $(".custom-loader").removeClass('hidden')
                $(".record").addClass('hidden');
                $("#errorBox").addClass('hidden');
                $("#objectionsBox").addClass('hidden');
                $("#move_application").addClass('hidden');
                $('#errors-if').empty();
              },
              success: function (response) {
                if (response.code == 200) {
                    $(".record").removeClass('hidden');
                    document.getElementById("get_first_name").innerHTML = response.data.application.user.fname;
                    document.getElementById("get_last_name").innerHTML = response.data.application.user.lname;
                    document.getElementById("get_cnic_no").innerHTML = response.data.application.user.cnic_no;
                    if(response.data.move_application != undefined && response.data.move_application == true) {
                        $("#move_application").data('id',response.data.application.id);
                        $("#move_application").removeClass('hidden');
                    }
                    notifyDialogBox('success','Success!',response.message);
                    $(".custom-loader").addClass('hidden');
                }
                if (response.code == 404) {
                    notifyDialogBox('warning','Oops!',response.message);
                    $(".custom-loader").addClass('hidden');
                }

                if(response.code == 400){
                    notifyDialogBox('warning','Warning!',response.message);
                    $(".record").removeClass('hidden');
                    document.getElementById("get_first_name").innerHTML = response.data.application.user.fname;
                    document.getElementById("get_last_name").innerHTML = response.data.application.user.lname;
                    document.getElementById("get_cnic_no").innerHTML = response.data.application.user.cnic_no;
                    if(response.data.errors.length > 0){
                        $('#errorBox').removeClass('hidden');
                        let list = '';
                        response.data.errors.forEach(function (value, index, array){
                            list += '<li>'+value+'</li>';
                        })
                        $('#errors-if').empty();
                        $('#errors-if').append(list);
                        $(".custom-loader").addClass('hidden');
                    }

                    if(response.data.objections.length > 0){
                        $('#objectionsBox').removeClass('hidden');
                        let list = '';
                        response.data.objections.forEach(function (value, index, array){
                            list += '<li>'+value+'</li>';
                        })
                        $('#objections-if').empty();
                        $('#objections-if').append(list);
                        $(".custom-loader").addClass('hidden');
                    }
                }
              },
              error : function (errors) {
                errorsGet(errors.responseJSON.errors)
                $(".custom-loader").addClass('hidden');
              }
          });
      });
    });
</script>

<script>
    $('#userRegistrationForm').on('submit',function (event){
        event.preventDefault();
        console.log(event.target.dataset.target);
        url = event.target.dataset.target;
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
            beforeSend: function(){
                $(".save_btn").hide();
                $(".save_btn_loader").show();
            },
            success: function (response) {
                $('#addIntimationApplication').modal('hide');
                $(".custom-loader").removeClass('hidden');
                setTimeout(function (){
                    location.href = response.url
                },800)
            },
            error : function (errors) {
                if (errors.responseJSON.message) {
                    Swal.fire('Warning!',errors.responseJSON.message,'warning')
                }
                $(".save_btn").show();
                $(".save_btn_loader").hide();
                $(".custom-loader").addClass('hidden');
                errorsGet(errors.responseJSON.errors)
            }
        });
    })

    $('#move_application').on('click',function (){
        let id = $(this).data('id');
        $.ajax({
            method: "POST",
            url: '{{route('lower-court.move-application')}}',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                'id': id
            },
            success: function (response) {
                if (response.code == 200) {
                    notifyDialogBoxReDirect('success','Success!',response.message,response.data.url)
                    $(".custom-loader").addClass('hidden');
                }
                if (response.code == 404) {
                    notifyDialogBox('warning','Oops!',response.message);
                    $(".custom-loader").addClass('hidden');
                }
            }
        });

        notifyBlackToast('Moving application ' + $(this).data('id') + ' to lower court');
    })
</script>

@endsection