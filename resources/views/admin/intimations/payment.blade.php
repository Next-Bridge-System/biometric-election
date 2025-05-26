@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Intimation Application - Payment</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('intimations.show',$application->id)}}"
                            class="btn btn-dark">Back</a>
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
            <!-- left column -->
            <div class="col-md-12">
                <!-- jquery validation -->
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Add Payment</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <h4 id="voucher_file" class="text-danger"></h4>

                            <table class=" table table-bordered table-sm" style="width: 100%">
                                <tbody>
                                    <form action="#" method="POST" id="payment_form" enctype="multipart/form-data">
                                        @csrf
                                        <tr>
                                            <th>Application No:</th>
                                            <td>{{$application->application_token_no}}</td>

                                            <th>Application Type:</th>
                                            <td>{{getApplicationType($application->id)}}</td>
                                        </tr>
                                        <tr>
                                            <th>Name:</th>
                                            <td>{{getLawyerName($application->id)}}</td>

                                            <th>Cnic No:</th>
                                            <td>{{$application->cnic_no}}</td>
                                        </tr>
                                        <tr>
                                            <th>Bank Name:<span class="text-danger">*</span></th>
                                            <td><select name="bank_name" class="form-control" id="">
                                                    <option value="Habib Bank Limited" {{ $payment->bank_name == "Habib
                                                        Bank Limited" ? "selected" : "" }}>Habib Bank Limited</option>
                                                </select>
                                            </td>

                                            <th>Transaction ID:<span class="text-danger">*</span></th>
                                            <td>
                                                <input type="text" class="form-control" name="transaction_id"
                                                    placeholder="Transaction ID" required>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Amount Paid:</th>
                                            <td>
                                                @if (Auth::guard('admin')->user()->hasPermission('edit-payment-fees'))
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">PKR</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="amount"
                                                        value="{{$payment->amount}}">
                                                </div>
                                                @else
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">PKR</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="amount"
                                                        value="{{$payment->amount}}" readonly>
                                                </div>
                                                @endif
                                            </td>

                                            <th>Paid Date: <span class="text-danger">*</span></th>
                                            <td>
                                                <div class="input-group date" id="dateOfBirth"
                                                    data-target-input="nearest">
                                                    <input type="text" class="form-control datetimepicker-input"
                                                        data-target="#dateOfBirth" name="paid_date" required
                                                        autocomplete="off" data-toggle="datetimepicker" />
                                                    <div class="input-group-append" data-target="#dateOfBirth"
                                                        data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Voucher No:</th>
                                            <td>{{$payment->voucher_no}}</td>

                                            <th>Voucher File: <span class="text-danger">*</span></th>
                                            <td>
                                                @if (isset($payment->voucher_file))
                                                <img src="{{asset('storage/app/public/'.$payment->voucher_file)}}"
                                                    alt="" class="custom-image-preview">
                                                <a href="{{route('intimations.destroy.voucher')}}"
                                                    class="btn btn-danger btn-sm float-right col-md-12 mt-2"
                                                    onclick="return confirm('Are you sure you want to delete it. This action cannot be revert.')">Remove</a>
                                                @else
                                                <input type="file" id="voucher_file" name="voucher_file"
                                                    accept="image/jpg,image/jpeg,image/png">
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4"><button type="submit" class="btn btn-success">Add
                                                    Payment</button></td>
                                        </tr>
                                    </form>
                                </tbody>
                            </table>
                        </div>
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
<script src="{{asset('public/js/app.js')}}"></script>
<script>
    jQuery(document).ready(function () {
        App.init();
    });
    $(document).ready(function(){
      $("#payment_form").on("submit", function(event){
          event.preventDefault();
          $('span.text-success').remove();
          $('span.invalid-feedback').remove();
          $('input.is-invalid').removeClass('is-invalid');
          var formData = new FormData(this);
          $.ajax({
              method: "POST",
              data: formData,
              url: '{{route('intimations.payment', $application->id)}}',
              processData: false,
              contentType: false,
              cache: false,
              beforeSend: function(){
                $(".custom-loader").removeClass('hidden');
              },
              success: function (response) {
                if (response.status == 1) {
                    window.location.href = '{{route('intimations.show', $application->id)}}';
                }
              },
              error : function (errors) {
                $(".custom-loader").addClass('hidden');
                errorsGet(errors.responseJSON.errors)
                  if(errors.responseJSON.policy != undefined){
                      notifyToast('error',errors.responseJSON.policy)
                  }
                $("#voucher_file").text(errors.responseJSON.voucher_file);
                notifyDialogBox('warning','Validation Error!',errors.responseJSON.voucher_file)
              }
          });
      });
    });

    $('#dateOfBirth').datetimepicker({
        size: 'large',
        format: 'DD-MM-YYYY',
    });
</script>

<script>
    var voucher = FilePond.create(document.querySelector('input[id="voucher_file"]'), {
        acceptedFileTypes: ['image/png' ,'image/jpeg','image/jpg'],
        required: true,
        allowMultiple: false,
        allowFileSizeValidation: true,
        maxFileSize:'1MB',
        allowRevert: true,
        fileValidateTypeDetectType: (source, type) => new Promise((resolve, reject) => {
            resolve(type);
        })
    });
    voucher.setOptions({
      server: {
          url: '{{route('intimations.payment.voucher')}}',
          headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
      }
    });

</script>


@endsection
