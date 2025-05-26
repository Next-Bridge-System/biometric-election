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

                    <form action="#" method="POST" id="create_step_7_form" enctype="multipart/form-data"> @csrf
                        <div class="card-body">
                            @include('frontend.intimation.partials._application-view')
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="button" class="btn btn-success float-right" data-toggle="modal"
                                data-target="#exampleModal">Save & Submit </button>
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                                aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Verify Intimation Application
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <h6>Are you sure you want to submit this application. Once you submit it you
                                                cannot edit or update it. Kindly verify your all changes & uploads.
                                                Thankyou.</h6>
                                            @if($application->paymentVoucher == NULL)
                                            <h6 class="mt-4">Bank Payment Voucher</h6>
                                            <div class="row">
                                                <div class="col-12 form-group-inline">
                                                    <input type="radio" value="Habib Bank Limited" name="bank_name"
                                                        id="HBL" required>
                                                    <label for="HBL">HBL (Online/Branch)</label>
                                                </div>
                                                {{-- <div class="col-12 form-group-inline">
                                                    <input type="radio" value="Bank Islami" name="bank_name"
                                                        id="bankIslami" required>
                                                    <label for="bankIslami">Online Voucher (Bank Islami)</label>
                                                </div> --}}
                                            </div>
                                            @endif
                                            <div class="form-group mt-3">
                                                <label for="">Enter OTP to Continue </label> <a id="resendPIN"
                                                    href="javascript:void(0)"> Resend Pin</a>
                                                <input type="text" class="form-control" name="otp"
                                                    placeholder="6 Digit OTP" required>
                                            </div>

                                            <img src="{{asset('public/admin/images/intimation_final_note.jpeg')}}"
                                                class="w-100" alt="">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-success float-right submit_btn"
                                                value="save">Yes, I Accept It.</button>

                                            <button class="btn btn-success hidden loading_btn" type="button"
                                                disabled><span class="spinner-grow spinner-grow-sm" role="status"
                                                    aria-hidden="true"></span>Loading...</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a href="{{route('frontend.intimation.create-step-5', $application->id)}}"
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
              url: '{{route('frontend.intimation.create-step-6', $application->id)}}',
              processData: false,
              contentType: false,
              cache: false,
              beforeSend: function(){
                $(".loading_btn").removeClass('hidden');
                $(".submit_btn").addClass('hidden');
              },
              success: function (response) {
                if (response.status == 1) {
                    window.location.href = '{{route('frontend.intimation.create-step-7', $application->id)}}';
                }
              },
              error : function (errors) {
                  errorsGet(errors.responseJSON.errors)
                  $(".loading_btn").addClass('hidden');
                  $(".submit_btn").removeClass('hidden');
                /*alert('Server not responding. Please try again later');
                location.reload();*/
              }
          });
      });

      $('.confirmation-btn').on('click',function(){
          $('#exampleModal').modal('hide');
          $('#exampleModalSubmission').modal('show');
      })
    });

    $('#exampleModal').on('show.bs.modal',function (){
        $.get('{{ route('frontend.intimation.sendOTP',$application->id) }}')
    })

    $('#resendPIN').on('click',function (){
        $.get("{{ route('frontend.resend-otp',$application->id) }}", function (data, status) {
            notifyBlackToast('OTP has sent successfully')
        });
    })
</script>
@endsection
