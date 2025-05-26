@if (isset($payments))
<fieldset class="border p-4 mb-4">
    <legend class="w-auto">Payment Details</legend>
    <div class="row">
        <table class="table table-striped table-sm table-bordered">
            <tr>
                <th>Applicant Name:</th>
                <td>{{getLawyerName($application->id)}}</td>
            </tr>
            <tr>
                <th>Father/Husband Name:</th>
                <td>{{$application->so_of ?? 'N/A'}}</td>
            </tr>
            <tr>
                <th>CNIC:</th>
                <td>{{$application->cnic_no}}</td>
            </tr>
            <tr>
                <th>Total Amount:</th>
                <td>
                    <span class="badge badge-success">{{getVoucherAmount($application->id)['total_amount']}} PKR</span>
                </td>
            </tr>
        </table>

        @foreach($payments as $payment)
        <table class="table table-striped table-sm table-bordered">
            <tr class="text-center bg-success">
                <th colspan="2">Payment # {{$loop->iteration}}</th>
            </tr>

            @if (Route::currentRouteName() == 'intimations.show' && $application->is_intimation_completed == 0)
            <tr>
                <th>Payment:</th>
                <td>
                    @if (isset($payment) && $payment->payment_status == 1)
                    @else
                    @if(Auth::guard('admin')->user()->hasPermission('add-payments'))
                    <a href="{{(route('intimations.payment', $application->id))}}" class="btn btn-success btn-sm">Add
                        Payment</a>
                    @endif
                    @endif

                    @if(Auth::guard('admin')->user()
                    ->hasPermission('intimation-reset-payment'))
                    <button onclick="deletePayment('{{$payment->id}}')" class="btn btn-danger btn-sm">Reset
                        Payment</button>
                    @endif

                    @if(Auth::guard('admin')->user()
                    ->hasPermission('account-department-approve-payment') &&
                    $payment->acct_dept_payment_status == 0)
                    <button onclick="acctDeptPaymentStatus('{{$payment->id}}', 1)"
                        class="btn btn-primary btn-sm">Approve
                        Payment</button>
                    @endif

                    @if(Auth::guard('admin')->user()
                    ->hasPermission('account-department-disapprove-payment') &&
                    $payment->acct_dept_payment_status == 1)
                    <button onclick="acctDeptPaymentStatus('{{$payment->id}}', 0)"
                        class="btn btn-danger btn-sm">Disapprove
                        Payment</button>
                    @endif

                </td>
            </tr>
            @endif

            <tr>
                <th>Bank Name:</th>
                <td>{{isset($payment->bank_name) ? $payment->bank_name : 'N/A'}}</td>
            </tr>
            <tr>
                <th>Voucher Amount</th>
                <td>
                    <span class="badge badge-success"> Intimation Fee: {{$payment->enr_fee_pbc}} PKR</span> +
                    Degree Fee: <span class="badge badge-success">{{$payment->degree_fee}} PKR</span>
                </td>
            </tr>
            <tr>
                <th>Payment Status:</th>
                <td>
                    @if (isset($payment) && $payment->payment_status == 1)
                    <span class="badge badge-success">Paid</span>
                    @else
                    <span class="badge badge-danger">Unpaid</span>
                    @endif

                    @if (isset($payment) && $payment->acct_dept_payment_status == 1)
                    <span class="badge badge-success">Approved</span>
                    @elseif(isset($payment) && $payment->acct_dept_payment_status == 0)
                    <span class="badge badge-danger">Disapproved</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Paid Date:</th>
                <td>
                    {{isset($payment->paid_date) ? date('d-m-Y', strtotime($payment->paid_date))
                    : 'N/A'}}
                </td>
            </tr>
            @if ($application->is_accepted == 1 && Route::currentRouteName() != 'intimations.print-detail' &&
            $application->is_intimation_completed == 0)
            <tr>
                <th>Attached Voucher File:</th>
                <td>
                    @if (isset($payment->voucher_file) && $payment->payment_status == 1)
                    <a href="{{asset('storage/app/public/'.$payment->voucher_file)}}"
                        download="Vocuher-{{$payment->voucher_no}}" class="btn btn-primary btn-xs mr-4"><i
                            class="fas fa-download mr-1"></i>Download File</a>
                    <img src="{{asset('storage/app/public/'.$payment->voucher_file)}}" alt=""
                        class="custom-image-preview">
                    @else N/A @endif
                </td>
            </tr>
            <tr>
                <th>Print Bank Voucher:</th>
                <td>
                    @if($payment->bank_name != null)
                    <a href="{{route('intimations.prints.bank-voucher',['download'=>'pdf','application_id' => $application->id,'payment_id'=>$payment->id])}}"
                        target="_blank" class="btn btn-primary btn-xs"><i class="fas fa-print mr-1"></i>Print Bank
                        Voucher</a>
                    @else
                    N/A
                    @endif
                </td>
            </tr>
            @endif
            <tr>
                <th>Voucher No:</th>
                <td>{{$payment->voucher_no}}</td>
            </tr>
            <tr>
                <th>Transaction ID:</th>
                <td>{{isset($payment->transaction_id) ? $payment->transaction_id : 'N/A'}}</td>
            </tr>
            <tr>
                <th>Payment added by:</th>
                <td>
                    @if ($payment->payment_status == 1 && $payment->online_banking == 1)
                    Online Banking
                    @elseif ($payment->payment_status == 1 && $payment->online_banking == 0)
                    {{getAdminName($payment->admin_id)}}
                    @else N/A @endif
                </td>
            </tr>
            <tr>
                <th>Payment created at:</th>
                <td>
                    {{date('d-m-Y', strtotime($payment->created_at))}}
                </td>
            </tr>
            <tr>
                <th>Payment updated at:</th>
                <td>
                    {{date('d-m-Y', strtotime($payment->updated_at))}}
                </td>
            </tr>
        </table>
        @endforeach
    </div>
</fieldset>
@endif