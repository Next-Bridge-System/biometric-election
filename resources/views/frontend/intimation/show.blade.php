@extends('layouts.frontend')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="col-md-12">
                        <h6>Note:</h6>
                        <ol>
                            <li>Sent intimation hard copy
                                along with all documents otherwise your application will
                                be terminated or expired.</li>
                            <li>Punjab Bar Council will strictly take action if any of information or
                                documents is fake.
                            </li>
                        </ol>

                        @if (getIntimationDuration($application->intimation_start_date) >= 6)
                        <button id="enrolled_lc_btn" @if (isset($application->error_logs) ||
                            isset($application->objections)) class="btn btn-secondary btn-sm
                            float-right m-1" @else class="btn btn-success btn-sm float-right m-1" @endif>
                            <i class="fas fa-copy mr-1"></i>Enrolled Lower Court</button>

                        @if ($application->error_logs != NULL)
                        <button type="button" class="btn btn-danger btn-sm float-right m-1" data-toggle="modal"
                            data-target="#reasons"><i class="fas fa-print mr-1"></i>Reasons & Objection Lower Court
                        </button>

                        <div class="modal fade" id="reasons" tabindex="-1" aria-labelledby="reasonsLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="reasonsLabel">Reasons Required For Lower Court
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        @foreach (json_decode($application->error_logs) as $reason)
                                        <span class="badge badge-danger text-uppercase">{{$reason}}</span>
                                        @endforeach

                                        @php
                                        $objections = json_decode($application->objections, TRUE)
                                        @endphp
                                        @isset($objections)

                                        @foreach ($objections as $item)
                                        <span class="badge badge-danger">{{getObjections($item)}}</span>
                                        @endforeach
                                        @endisset
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary btn-sm"
                                            data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endif

                    </div>
                    <div class="col-md-12 col-sm-12">
                        <a href="{{route('frontend.intimation.pdf',['download'=>'pdf','application' => $application])}}"
                            target="_blank" class="btn btn-sm btn-success float-right m-1"><i class="fas fa-print mr-1"
                                aria-hidden="true"></i>Print Intimation</a>

                        @foreach ($payments as $payment)
                        @if(isset($payment) && $payment->bank_name != null)
                        <a href="{{route('intimations.prints.bank-voucher',['download'=>'pdf','application_id' => $application->id,'payment_id'=>$payment->id])}}"
                            target="_blank" class="btn btn-primary float-right btn-sm m-1"><i
                                class="fas fa-print mr-1"></i>
                            <span>Print Bank Voucher {{$payments->count() > 1 ? '#'.$loop->iteration : ''}}</span>
                        </a>
                        @endif
                        @endforeach
                    </div>
                </div>

                <div class="card-body">
                    @include('admin.intimations.partials.application-section')
                    <div class="card-footer">
                        <a href="{{route('frontend.intimation.pdf',['download'=>'pdf','application' => $application])}}"
                            target="_blank" class="btn btn-sm btn-success float-right mr-1"><i class="fas fa-print mr-1"
                                aria-hidden="true"></i>Print Intimation</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(function () {
        $("#datatable").DataTable({
        "responsive": true,
        "autoWidth": false,
        });
    });

    $("#enrolled_lc_btn").click(function (e) {
        e.preventDefault();
        $.ajax({
            method: "POST",
            url: '{{route('frontend.intimation.transfer')}}',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                'id': '{{$application->id}}'
            },
            beforeSend: function(){
                $(".custom-loader").removeClass('hidden');
            },
            success: function (response) {
                if (response.status == 1) {
                    swal.fire({
                    title: response.title,
                    icon: response.icon,
                    text: response.message,
                    type: "success"
                }).then(function() {
                    window.location.href = response.url;
                });
                    $(".custom-loader").addClass('hidden');
                }
            }
        });
    });
</script>
@endsection