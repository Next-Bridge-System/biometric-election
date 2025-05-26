@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>High Court Application - Payment</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('high-court.show',$lc->id)}}"
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
                                            <td>{{$lc->id}}</td>

                                            <th>Application Type:</th>
                                            <td>{{ 'Lower Court' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Name:</th>
                                            <td>{{$lc->user->name ?? '- -'}}</td>

                                            <th>Cnic No:</th>
                                            <td>{{$lc->cnic_no}}</td>
                                        </tr>
                                        <tr>
                                            <th>Bank Name:<span class="text-danger">*</span></th>
                                            <td><select name="bank_name" class="form-control custom-select" id="">
                                                    <option value="Habib Bank Limited" {{ $payment->bank_name == "Habib
                                                        Bank Limited" ? "selected" : "" }}>Habib Bank Limited</option>
                                                </select>
                                            </td>

                                            <th>Transaction ID:<span class="text-danger">*</span></th>
                                            <td>
                                                <input type="text" class="form-control" name="transaction_id"
                                                    placeholder="Transaction ID" value="{{$payment->transaction_id}}">
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Amount Paid:</th>
                                            <td>
                                                @if (permission('lc_edit_payment_fee'))
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
                                                <div class="input-group date" id="paid_date"
                                                    data-target-input="nearest">
                                                    <input type="text" value="{{getDateFormat($payment->paid_date)}}"
                                                        class="form-control datetimepicker-input"
                                                        data-target="#paid_date" name="paid_date" id="paid_date_input"
                                                        required autocomplete="off" data-toggle="datetimepicker" />
                                                    <div class="input-group-append" data-target="#paid_date"
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

                                            <th rowspan="2">Voucher File:</th>
                                            <td rowspan="2" class="text-center">
                                                @if (isset($payment->voucher_file))
                                                <img src="{{asset('storage/app/public/'.$payment->voucher_file)}}"
                                                    alt="" class="custom-image-preview">
                                                <a href="{{route('high-court.destroy.voucher')}}"
                                                    class="btn btn-danger btn-sm float-right col-md-12 mt-2"
                                                    onclick="return confirm('Are you sure you want to delete it. This action cannot be revert.')">Remove</a>

                                                @else
                                                <input type="file" id="voucher_file" name="voucher_file"
                                                    accept="image/jpg,image/jpeg,image/png">
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Voucher Type:</th>
                                            <td>{{ getHcVoucherName($payment->voucher_type) ?? '- -' }}</td>
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
              url: '{{route('high-court.payment', [$lc->id,$payment->voucher_type])}}',
              processData: false,
              contentType: false,
              cache: false,
              beforeSend: function(){
                $(".custom-loader").removeClass('hidden');
              },
              success: function (response) {
                if (response.status == 1) {
                    window.location.href = '{{route('high-court.show', $lc->id)}}';
                }
              },
              error : function (errors) {
                $(".custom-loader").addClass('hidden');
                errorsGet(errors.responseJSON.errors)
                  if(errors.responseJSON.policy != undefined){
                      notifyToast('error',errors.responseJSON.policy)
                  }
                $("#voucher_file").text(errors.responseJSON.voucher_file);
              }
          });
      });
    });

    $('#paid_date').datetimepicker({
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
          url: '{{route('high-court.payment.voucher')}}',
          headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
      }
    });

</script>


@endsection