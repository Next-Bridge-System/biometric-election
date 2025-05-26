<form data-action="{{route('intimations.create-step-7',$application->id)}}" action="#" method="POST"
    id="create_step_7_form" enctype="multipart/form-data"> @csrf
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
                        <td>{{ $application->paymentVoucher->bank_name ?? '--' }}</td>
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
        <a href="{{route('frontend.intimation.pdf',['download'=>'pdf','application' => $application])}}" target="_blank"
            class="btn btn-success float-right mr-1"><i class="fas fa-print mr-1" aria-hidden="true"></i>Print
            Application</a>
        @if ($application->is_accepted == 0)
        <a href="javascript:void(0)" onclick="goToStep('{{route('intimations.create-step-6', $application->id)}}',6)"
            class="btn btn-secondary float-right mr-1">Back</a>
        @endif
    </div>
</form>
