@extends('layouts.frontend')

@section('content')
<section class="content">
    <div class="container">
        <div class="row mb-2">
            <div class="col-md-12">
                @if ($application->is_final_submitted == 1)
                <a href="{{route('high-court.prints.bank-voucher',['download'=>'pdf','high_court_id' => $application->id])}}"
                    target="_blank" class="btn btn-primary btn-sm m-1"><i class="fas fa-print mr-1"></i>Bank
                    Voucher</a>

                @php
                $C1 = $application->payments->where('online_banking','!=',1)->where('is_voucher_print', 1)->where('voucher_file','!=',NULL)->count();
                $C2 = $application->payments->where('online_banking','!=',1)->where('is_voucher_print', 1)->count();
                @endphp

                <span>
                    @if ($C1 == $C2 || getHcPaymentStatus($application->id)['name'] == 'Paid')
                    <a href="{{route('high-court.prints.form-hc',$application->id)}}" target="_blank"
                        class="btn btn-primary btn-sm m-1"><i class="fas fa-print mr-1"></i>Form HC</a>
                    @endif
                </span>

                <span>
                    @if (getHcAdvocateCertificateStatus($application))
                    <a href="{{route('high-court.prints.advocate-certificate',$application->id)}}" target="_blank"
                        class="btn btn-primary btn-sm"><i class="fas fa-print mr-1"></i>Advocate Certificate HC </a>
                    @endif
                </span>

                <div class="mt-2">
                    <p class="text-bold text-lg" style="text-align: right">
                        آپ نے کامیابی کے ساتھ اپنی آن لائن ہائی کورٹ انرولمنٹ فائل مکمل کر لی ہے۔ اب آپ اس آن لائن
                        درخواست فارم کا پرنٹ لے لیں اور جو دستاویزات آپ نے اپلوڈ کی ہیں، ان کو جمع شدہ چالان کے ساتھ
                        فائل بنا کر پنجاب بار کونسل میں جمع کروائیں۔
                    </p>
                </div>

                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">High Court Application</h3>
                    </div>
                    <div class="card-body">
                        @include('admin.high-court.partials.application-section')
                        @include('admin.high-court.partials.payment-section')
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
</script>

@foreach ($application->payments as $payment)
<script>
    var url = '{{ route("frontend.high-court.upload.payment-voucher",":id") }}';
        url = url.replace(':id', '{{$payment->id}}');
        var image = FilePond.create(document.querySelector('input[id="voucher_file_{{$payment->id}}"]'), {
                    acceptedFileTypes: ['image/png', 'image/jpeg', 'image/jpg'],
                    required: false,
                    allowMultiple: false,
                    allowFileSizeValidation: true,
                    maxFileSize: '1MB',
                    allowRevert: false,
                    fileValidateTypeDetectType: (source, type) => new Promise((resolve, reject) => {
                    resolve(type);
                    })
                });

            image.setOptions({
                server: {
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }
            });
</script>

@if ($payment->payment_status == 0)
<script>
    $('#paid_date_{{$payment->id}}').datetimepicker({
        size: 'large',
        format: 'DD-MM-YYYY',
    });

    $("#hc_upload_payment_form_{{$payment->id}}").on("submit", function(event){
        
        event.preventDefault();
        var paid_date = $("#paid_date_input_{{$payment->id}}").val();
        var transaction_id = $("#transaction_id_{{$payment->id}}").val();

        var url = '{{ route("frontend.high-court.upload.payment-voucher",":id") }}';
        url = url.replace(':id', '{{$payment->id}}');

        $.ajax({
            method: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                'transaction_id': transaction_id,
                'paid_date': paid_date,
            },
            url: url,
            beforeSend: function(){
                $(".custom-loader").removeClass('hidden');
            },
            success: function (response) {
                // $('#upload_payment_modal_{{$payment->id}}').modal('toggle');
                location.reload();
            },
            error : function (errors) {
                $(".custom-loader").addClass('hidden');
            }
        });
    });

    function removeHcVoucherFile(event, step) {
        Swal.fire({
            icon: 'warning',
            title: 'Warning!',
            text: "Are you sure you want to remove this payment voucher file? This action cannot be revert or undone.",
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, Remove it!'
        }).then((result) => {
            if (result.value) {
                    $(".custom-loader").removeClass('hidden');
                    console.log(event.dataset.action)
                    $.get(event.dataset.action, function (data, status) {
                    location.reload();
                });
            }
        })
    }
</script>
@endif
@endforeach

@endsection