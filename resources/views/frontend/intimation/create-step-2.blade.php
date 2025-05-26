@extends('layouts.frontend')

@section('content')

<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Add Intimation Application</h3>
                    </div>

                    @include('frontend.intimation.partials.steps')

                    <form action="#" method="POST" id="create_step_2_form" enctype="multipart/form-data"> @csrf
                        <div class="card-body">
                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto">Legal Identification</legend>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>C.N.I.C. No <span class="text-danger">*</span>:</label>
                                        <input type="text" class="form-control" name="cnic_no" disabled
                                            value="{{isset($application->cnic_no) ? $application->cnic_no : Auth::guard('frontend')->user()->cnic_no}}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Date of Expiry (CNIC) <span class="text-danger">*</span>:</label>
                                        <div class="input-group date" id="cnic_expiry_date" data-target-input="nearest">
                                            <input type="text"
                                                value="{{isset($application->cnic_expiry_date) ? \Carbon\Carbon::parse($application->cnic_expiry_date)->format('Y-m-d') : ''}}"
                                                class="form-control datetimepicker-input"
                                                data-target="#cnic_expiry_date" name="cnic_expiry_date" required
                                                autocomplete="off" data-toggle="datetimepicker" />
                                            <div class="input-group-append" data-target="#cnic_expiry_date"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                        {{--<input type="text" class="form-control" name="cnic_expiry_date"
                                            value="{{isset($application->cnic_expiry_date) ? $application->cnic_expiry_date : ''}}"
                                            required placeholder="dd-mm-yyyy">--}}
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Upload CNIC (Front) <span class="text-danger">*</span>:</label>
                                        @if (isset($application->uploads->cnic_front) &&
                                        $application->uploads->cnic_front != NULL)
                                        <img src="{{asset('storage/app/public/'.$application->uploads->cnic_front)}}"
                                            alt="" class="col-md-12">
                                        <a href="{{route('frontend.intimation.destroy.cnic-front')}}"
                                            class="btn btn-danger btn-sm float-right col-md-12 mt-2"
                                            onclick="return confirm('Are you sure you want to delete it. This action cannot be revert.')">Remove</a>
                                        @else
                                        <input type="file" name="cnic_front" id="cnic_front"
                                            accept="image/jpg,image/jpeg,image/png" required>
                                        @endif
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Upload CNIC (Back) <span class="text-danger">*</span>:</label>
                                        @if (isset($application->uploads->cnic_back) &&
                                        $application->uploads->cnic_back != NULL)
                                        <img src="{{asset('storage/app/public/'.$application->uploads->cnic_back)}}"
                                            alt="" class="col-md-12">
                                        <a href="{{route('frontend.intimation.destroy.cnic-back')}}"
                                            class="btn btn-danger btn-sm float-right col-md-12 mt-2"
                                            onclick="return confirm('Are you sure you want to delete it. This action cannot be revert.')">Remove</a>
                                        @else
                                        <input type="file" id="cnic_back" name="cnic_back"
                                            accept="image/jpg,image/jpeg,image/png" required>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto">Degree Information</legend>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="">Degree Place</label>
                                        <select name="degree_place" id="degree_place" class="form-control custom-select">
                                            <option value="" selected>Select</option>
                                            <option value="1" {{ $application->degree_place == 1 ? 'selected' :''}}>Punjab</option>
                                            <option value="2" {{ $application->degree_place == 2 ? 'selected' : ''}}>Out of Punjab</option>
                                            <option value="3" {{ $application->degree_place == 3 ? 'selected' : ''}}>Out of Pakistan</option>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success float-right" value="save">Save & Next</button>
                            <a href="{{route('frontend.intimation.create-step-1', $application->id)}}"
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
      $("#create_step_2_form").on("submit", function(event){
          event.preventDefault();
          $('span.text-success').remove();
          $('span.invalid-feedback').remove();
          $('input.is-invalid').removeClass('is-invalid');
          var formData = new FormData(this);
          $.ajax({
              method: "POST",
              data: formData,
              url: '{{route('frontend.intimation.create-step-2', $application->id)}}',
              processData: false,
              contentType: false,
              cache: false,
              beforeSend: function(){
                $(".custom-loader").removeClass('hidden');
              },
              success: function (response) {
                if (response.status == 1) {
                    window.location.href = '{{route('frontend.intimation.create-step-3', $application->id)}}';
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
    var cnicFront = FilePond.create(document.querySelector('input[id="cnic_front"]'), {
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
    cnicFront.setOptions({
      server: {
          url: '{{route('frontend.intimation.uploads.cnic-front')}}',
          headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
      }
    });

    var cnicBack = FilePond.create(document.querySelector('input[id="cnic_back"]'), {
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
    cnicBack.setOptions({
      server: {
          url: '{{route('frontend.intimation.uploads.cnic-back')}}',
          headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
      }
    });

    $('#cnic_expiry_date').datetimepicker({
        size: 'large',
        date: '{{ $application->cnic_expiry_date ?? null }}',
        format: 'DD-MM-YYYY',
    });
</script>

@endsection
