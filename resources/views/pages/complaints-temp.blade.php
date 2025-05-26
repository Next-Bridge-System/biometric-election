@extends('layouts.frontend')

@section('content')
@include('frontend.includes.slider')
@include('frontend.includes.buttons')

<div class="d-flex justify-content-center">
    <h4 class="text-center" id="heading">Complaint Management</h4>
</div>

<div class="container">
    <form action="#" method="POST" id="complaint_form" enctype="multipart/form-data"> @csrf
        <div class="card-body">
            <fieldset class="border p-4 mb-4">
                <legend class="w-auto">Complaint Information</legend>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Name: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Email:</label>
                        <input type="email" class="form-control" name="email">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Phone No: <span class="text-danger">*</span></label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><img src="{{asset('public/admin/images/pakistan.png')}}"
                                        alt=""></span>
                                <span class="input-group-text">+92</span>
                            </div>
                            <input type="tel" class="form-control" name="phone">
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Type:</label>
                        <input type="text" class="form-control" name="type">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Uploads:</label>
                        <input type="file" class="form-control custom-image-upload" name="files[]" multiple>
                    </div>
                    <div class="form-group col-md-12">
                        <label>Message: <span class="text-danger">*</span></label>
                        <textarea type="text" class="form-control" name="message" rows="6"></textarea>
                    </div>
                </div>
            </fieldset>

            <div class="form-group col-md-12 text-center">
                <button type="submit" class="btn btn-success mt-4" value="save">Submit Complaint</button>
            </div>
        </div>
    </form>
</div>

@endsection

@section('scripts')
<script src="{{asset('public/js/app.js')}}"></script>
<script>
    jQuery(document).ready(function () {
        App.init();
    });

    $(document).ready(function(){
      $("#complaint_form").on("submit", function(event){
          event.preventDefault();
          $('span.text-success').remove();
          $('span.invalid-feedback').remove();
          $('input.is-invalid').removeClass('is-invalid');

          var formData = new FormData(this);
          $.ajax({
              method: "POST",
              data: formData,
              url: '{{route('frontend.complaints')}}',
              processData: false,
              contentType: false,
              cache: false,
              beforeSend: function(){
                $(".custom-loader").removeClass('hidden');
              },
              success: function (response) {
                if (response.status == 1) {
                    $( '#complaint_form').each(function(){
                        this.reset();
                    });
                    $(".custom-loader").addClass('hidden');
                    Swal.fire('Complaint Sent!',response.message,'success')
                }
              },
              error : function (errors) {
                $('html, body').animate({scrollTop:$('#heading').position().top}, 'slow');
                errorsGet(errors.responseJSON.errors)
                $(".custom-loader").addClass('hidden');
              }
          });
      });

    });

</script>
@endsection
