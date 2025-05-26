@extends('layouts.admin')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Manage Existing Lawyers</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('lawyers.index')}}" class="btn btn-dark">Back</a>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Add Existing Lawyer</h3>
                    </div>

                    @include('admin.lawyers.partials.steps')

                    <form action="#" method="POST" id="create_step_6_form" enctype="multipart/form-data"> @csrf
                        <div class="card-body">
                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto">Lawyer Information</legend>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>License No. (LC):</label>
                                        <input type="text" class="form-control" name="license_no_lc">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>License No. (HC):</label>
                                        <input type="text" class="form-control" name="license_no_hc">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>PLJ No. (LC):</label>
                                        <input type="text" class="form-control" name="plj_no_lc">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>BF No. (HC):</label>
                                        <input type="text" class="form-control" name="bf_no_hc">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Registration/Ledger No. (LC):</label>
                                        <input type="text" class="form-control" name="reg_no_lc">
                                    </div>
                                </div>
                            </fieldset>

                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success float-right" value="save">Save & Next</button>
                            <a href="{{route('lawyers.create-step-5', $lawyer->id)}}"
                                class="btn btn-secondary float-right mr-1">Back</a>
                        </div>
                    </form>
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
      $("#create_step_6_form").on("submit", function(event){
          event.preventDefault();
          $('span.text-success').remove();
          $('span.invalid-feedback').remove();
          $('input.is-invalid').removeClass('is-invalid');
          var formData = new FormData(this);
          $.ajax({
              method: "POST",
              data: formData,
              url: '{{route('lawyers.create-step-6', $lawyer->id)}}',
              processData: false,
              contentType: false,
              cache: false,
              beforeSend: function(){
                $(".custom-loader").removeClass('hidden');
              },
              success: function (response) {
                if (response.status == 1) {
                    window.location.href = '{{route('lawyers.create-step-7', $lawyer->id)}}';
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
@endsection
