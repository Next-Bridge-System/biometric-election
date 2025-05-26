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
                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto">Intimation Payment</legend>
                                <div class="row">
                                    <table class="table table-striped table-sm table-bordered">
                                        <tr>
                                            <th>Applicant Name</th>
                                            <td>{{getLawyerName($application->id)}}</td>
                                        </tr>
                                        <tr>
                                            <th>Father/Husband Name</th>
                                            <td>{{$application->so_of ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Date of Birth (as per CNIC)</th>
                                            <td>{{$application->date_of_birth ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>CNIC</th>
                                            <td>{{$application->cnic_no}}</td>
                                        </tr>
                                        <tr>
                                            <th>Payment</th>
                                            <td>{{ $application->paymentVoucher->bank_name ?? "Bank Islami" }}</td>
                                        </tr>
                                        <tr>
                                            <th>Total Fee</th>
                                            <td><span class="badge badge-success">{{getVoucherAmount($application->id)}}
                                                    PKR</span></td>
                                        </tr>
                                    </table>
                                </div>
                            </fieldset>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            @if($application->paymentVoucher->bank_name == "Bank Islami")
                            <a href="{{route('frontend.intimation.bank-islami-voucher',['download'=>'pdf','application' => $application])}}"
                                class="btn btn-success float-right mr-1" target="_blank"><i class="fas fa-print mr-1"
                                    aria-hidden="true"></i>Get Voucher</a>
                            @else
                            <a href="{{route('frontend.intimation.habib-bank-limited-voucher',['download'=>'pdf','application' => $application])}}"
                                class="btn btn-success float-right mr-1" target="_blank"><i class="fas fa-print mr-1"
                                    aria-hidden="true"></i>Get Voucher</a>
                            @endif
                            <a href="{{route('frontend.intimation.print',['download'=>'pdf','application' => $application])}}"
                                class="btn btn-success float-right mr-1"><i class="fas fa-download mr-1"
                                    aria-hidden="true"></i>Download PDF</a>
                            <a href="{{route('frontend.intimation.pdf',['download'=>'pdf','application' => $application])}}"
                                target="_blank" class="btn btn-success float-right mr-1"><i class="fas fa-print mr-1"
                                    aria-hidden="true"></i>Print Application</a>
                            @if ($application->is_accepted == 0)
                            <a href="{{route('frontend.intimation.create-step-6', $application->id)}}"
                                class="btn btn-secondary float-right mr-1">Back</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
