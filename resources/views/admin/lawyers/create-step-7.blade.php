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

                    <form action="#" method="POST" id="create_step_7_form" enctype="multipart/form-data"> @csrf
                        <div class="card-body">
                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto">Uploads - Lower Court</legend>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>2 Character Certificate:</label>
                                        <input type="file" id="certificate_lc" name="certificate_lc">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Affidavit:</label>
                                        <input type="file" id="affidavit_lc" name="affidavit_lc">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>20 Case List (Image):</label>
                                        <input type="file" id="cases_lc" name="cases_lc">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Fee Voucher:</label>
                                        <input type="file" id="voucher_lc" name="voucher_lc">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Law GAT Certificate:</label>
                                        <input type="file" id="gat_lc" name="gat_lc">
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto">Uploads - High Court</legend>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>2 Character Certificate:</label>
                                        <input type="file" id="certificate_hc" name="certificate_hc">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Affidavit:</label>
                                        <input type="file" id="affidavit_hc" name="affidavit_hc">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>20 Case List (Image):</label>
                                        <input type="file" id="cases_hc" name="cases_hc">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Fee Voucher:</label>
                                        <input type="file" id="voucher_hc" name="voucher_hc">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Law GAT Certificate:</label>
                                        <input type="file" id="gat_hc" name="gat_hc">
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success float-right" value="save">Save & Next</button>
                            <a href="{{route('lawyers.create-step-6', $lawyer->id)}}"
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
      $("#create_step_7_form").on("submit", function(event){
          event.preventDefault();
          $('span.text-success').remove();
          $('span.invalid-feedback').remove();
          $('input.is-invalid').removeClass('is-invalid');
          var formData = new FormData(this);
          $.ajax({
              method: "POST",
              data: formData,
              url: '{{route('lawyers.create-step-7', $lawyer->id)}}',
              processData: false,
              contentType: false,
              cache: false,
              beforeSend: function(){
                $(".custom-loader").removeClass('hidden');
              },
              success: function (response) {
                if (response.status == 1) {
                    window.location.href = '{{route('lawyers.create-step-8', $lawyer->id)}}';
                }
              },
              error : function (errors) {
                alert('error')
              }
          });
      });
    });
</script>

<script>
    var certificateLC = FilePond.create(document.querySelector('input[id="certificate_lc"]'), {
        acceptedFileTypes: ['image/png' ,'image/jpeg','image/jpg'],
        required: false,
        allowMultiple: false,
        allowFileSizeValidation: true,
        maxFileSize:'1MB',
        allowRevert: true,
        fileValidateTypeDetectType: (source, type) => new Promise((resolve, reject) => {
            resolve(type);
        })
    });
    certificateLC.setOptions({
      server: {
          url: '{{route('lawyers.uploads.certificate-lc')}}',
          headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
      }
    });

    var affidavitLC = FilePond.create(document.querySelector('input[id="affidavit_lc"]'), {
        acceptedFileTypes: ['image/png' ,'image/jpeg','image/jpg'],
        required: false,
        allowMultiple: false,
        allowFileSizeValidation: true,
        maxFileSize:'1MB',
        allowRevert: true,
        fileValidateTypeDetectType: (source, type) => new Promise((resolve, reject) => {
            resolve(type);
        })
    });
    affidavitLC.setOptions({
      server: {
          url: '{{route('lawyers.uploads.affidavit-lc')}}',
          headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
      }
    });

    var casesLC = FilePond.create(document.querySelector('input[id="cases_lc"]'), {
        acceptedFileTypes: ['image/png' ,'image/jpeg','image/jpg'],
        required: false,
        allowMultiple: false,
        allowFileSizeValidation: true,
        maxFileSize:'1MB',
        allowRevert: true,
        fileValidateTypeDetectType: (source, type) => new Promise((resolve, reject) => {
            resolve(type);
        })
    });
    casesLC.setOptions({
      server: {
          url: '{{route('lawyers.uploads.cases-lc')}}',
          headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
      }
    });

    var voucherLC = FilePond.create(document.querySelector('input[id="voucher_lc"]'), {
        acceptedFileTypes: ['image/png' ,'image/jpeg','image/jpg'],
        required: false,
        allowMultiple: false,
        allowFileSizeValidation: true,
        maxFileSize:'1MB',
        allowRevert: true,
        fileValidateTypeDetectType: (source, type) => new Promise((resolve, reject) => {
            resolve(type);
        })
    });
    voucherLC.setOptions({
      server: {
          url: '{{route('lawyers.uploads.voucher-lc')}}',
          headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
      }
    });

    var gatLC = FilePond.create(document.querySelector('input[id="gat_lc"]'), {
        acceptedFileTypes: ['image/png' ,'image/jpeg','image/jpg'],
        required: false,
        allowMultiple: false,
        allowFileSizeValidation: true,
        maxFileSize:'1MB',
        allowRevert: true,
        fileValidateTypeDetectType: (source, type) => new Promise((resolve, reject) => {
            resolve(type);
        })
    });
    gatLC.setOptions({
      server: {
          url: '{{route('lawyers.uploads.gat-lc')}}',
          headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
      }
    });


    // HIGH COURT

    var certificateHC= FilePond.create(document.querySelector('input[id="certificate_hc"]'), {
        acceptedFileTypes: ['image/png' ,'image/jpeg','image/jpg'],
        required: false,
        allowMultiple: false,
        allowFileSizeValidation: true,
        maxFileSize:'1MB',
        allowRevert: true,
        fileValidateTypeDetectType: (source, type) => new Promise((resolve, reject) => {
            resolve(type);
        })
    });
    certificateHC.setOptions({
      server: {
          url: '{{route('lawyers.uploads.certificate-hc')}}',
          headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
      }
    });

    var affidavitHC= FilePond.create(document.querySelector('input[id="affidavit_hc"]'), {
        acceptedFileTypes: ['image/png' ,'image/jpeg','image/jpg'],
        required: false,
        allowMultiple: false,
        allowFileSizeValidation: true,
        maxFileSize:'1MB',
        allowRevert: true,
        fileValidateTypeDetectType: (source, type) => new Promise((resolve, reject) => {
            resolve(type);
        })
    });
    affidavitHC.setOptions({
      server: {
          url: '{{route('lawyers.uploads.affidavit-hc')}}',
          headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
      }
    });

    var casesHC= FilePond.create(document.querySelector('input[id="cases_hc"]'), {
        acceptedFileTypes: ['image/png' ,'image/jpeg','image/jpg'],
        required: false,
        allowMultiple: false,
        allowFileSizeValidation: true,
        maxFileSize:'1MB',
        allowRevert: true,
        fileValidateTypeDetectType: (source, type) => new Promise((resolve, reject) => {
            resolve(type);
        })
    });
    casesHC.setOptions({
      server: {
          url: '{{route('lawyers.uploads.cases-hc')}}',
          headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
      }
    });

    var voucherHC= FilePond.create(document.querySelector('input[id="voucher_hc"]'), {
        acceptedFileTypes: ['image/png' ,'image/jpeg','image/jpg'],
        required: false,
        allowMultiple: false,
        allowFileSizeValidation: true,
        maxFileSize:'1MB',
        allowRevert: true,
        fileValidateTypeDetectType: (source, type) => new Promise((resolve, reject) => {
            resolve(type);
        })
    });
    voucherHC.setOptions({
      server: {
          url: '{{route('lawyers.uploads.voucher-hc')}}',
          headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
      }
    });

    var gatHC= FilePond.create(document.querySelector('input[id="gat_hc"]'), {
        acceptedFileTypes: ['image/png' ,'image/jpeg','image/jpg'],
        required: false,
        allowMultiple: false,
        allowFileSizeValidation: true,
        maxFileSize:'1MB',
        allowRevert: true,
        fileValidateTypeDetectType: (source, type) => new Promise((resolve, reject) => {
            resolve(type);
        })
    });
    gatHC.setOptions({
      server: {
          url: '{{route('lawyers.uploads.gat-hc')}}',
          headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
      }
    });
</script>

@endsection
