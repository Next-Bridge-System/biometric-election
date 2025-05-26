@if (isset($payments) && $application->is_final_submitted == 1)
<fieldset class="border p-4 mb-4">
    <legend class="w-auto">Payment Section</legend>
    <div class="row">
        <div class="table-responsive">
            <table class="table table-striped table-sm table-bordered" id="lc-table">
                <tr>
                    <th>Applicant Name:</th>
                    <td>{{$application->lawyer_name}}</td>

                    <th>Father/Husband Name:</th>
                    <td>{{$application->father_name ?? '-'}}</td>
                </tr>
                <tr>
                    <th>CNIC:</th>
                    <td>{{$application->cnic_no}}</td>

                    <th>Total Amount:</th>
                    <td>
                        <span class="badge badge-success">
                            {{$application->payments()->where('is_voucher_print',1)->sum('amount')}} PKR
                        </span>
                    </td>
                </tr>
                <tr>
                    @if($application->is_final_submitted && Route::currentRouteName() == 'high-court.show')
                    <th>Download Vouchers:</th>
                    <td colspan="2"><a
                            href="{{route('high-court.prints.bank-voucher',['download'=>'pdf','high_court_id' => $application->id])}}"
                            target="_blank" class="btn btn-primary btn-xs"><i class="fas fa-print mr-1"></i>Print Bank
                            Vouchers</a></td>
                    @endif
                    @if (permission('lc_reset_payment'))
                    <td>
                        <div class="text-justify">
                            <span class="text-sm text-danger text-bold">Note: This action will reset only the unpaid
                                payments of this lower court application.If you want to reset the paid payment then you
                                must reset that specific payment
                                seprately.</span>
                        </div>
                        <div>
                            <button id="reset-payments" class="btn btn-danger btn-sm float-right m-1">Reset All
                                Payments</button>
                        </div>
                    </td>
                    @endif
                </tr>
            </table>
        </div>

        @foreach($payments as $voucher)

        @foreach ($voucher as $payment)
        <div class="table-responsive">
            <table class="table table-striped table-sm table-bordered" id="lc-table">
                @if ($loop->iteration == 1)
                <tr class="text-center bg-success">
                    <th colspan="4">
                        <span class="text-bold text-lg">{{$payment->voucher_name}}</h1>
                    </th>
                </tr>
                @endif
                @if ($voucher->count() > 1)
                <tr class="text-center bg-secondary">
                    <th colspan="4"><span class="text-sm">Payment # {{$loop->iteration}}</span></th>
                </tr>
                @endif
                <tr>
                    @if (Route::currentRouteName() == 'high-court.show')
                    <th>Payment:</th>
                    <td>
                        @if (isset($payment) && $payment->payment_status == 1)
                        @else
                        @if(permission('add-payments'))
                        <a href="{{(route('high-court.payment', [$application->id,$payment->voucher_type]))}}"
                            class="btn btn-success btn-sm">Add Payment</a>
                        @endif
                        @endif

                        @if(permission('lc_reset_payment'))
                        <button onclick="deletePayment('{{$payment->id}}')" class="btn btn-danger btn-sm">Reset
                            Payment</button>
                        @endif
                    </td>
                    @endif
                </tr>
                <tr>
                    <th>Voucher Name</th>
                    <td><span class="badge badge-success">{{isset($payment->voucher_name) ?
                            $payment->voucher_name : '-'}} </span></td>

                    <th>Voucher Amount</th>
                    <td><span class="badge badge-success">{{isset($payment->amount) ?
                            $payment->amount : '-'}} PKR</span></td>
                </tr>
                <tr>
                    <th>Payment Status:</th>
                    <td>
                        @if (isset($payment) && $payment->payment_status == 1)
                        <span class="badge badge-success">Paid</span>
                        @else
                        <span class="badge badge-danger">Unpaid</span>
                        @endif
                    </td>

                    <th>Paid Date:</th>
                    <td>
                        {{getDateFormat($payment->paid_date)}}
                    </td>
                </tr>
                <tr>
                    <th>Voucher No:</th>
                    <td>{{$payment->voucher_no}}</td>

                    <th>Transaction ID:</th>
                    <td>{{isset($payment->transaction_id) ? $payment->transaction_id : '-'}}</td>
                </tr>

                @if (Route::currentRouteName() == 'high-court.show')
                @if ($application->is_final_submitted == 1 && isset($payment->voucher_file))
                <tr>
                    <th>Attached Voucher File:</th>
                    <td class="text-center">
                        <img src="{{asset('storage/app/public/'.$payment->voucher_file)}}" alt=""
                            class="custom-image-preview p-2">
                        <a href="{{asset('storage/app/public/'.$payment->voucher_file)}}"
                            download="Vocuher-{{$payment->voucher_no}}" class="btn btn-primary btn-xs"><i
                                class="fas fa-download mr-1"></i>Download</a>
                    </td>
                    <th>Bank Name:</th>
                    <td>{{isset($payment->bank_name) ? $payment->bank_name : '-'}}</td>
                </tr>
                @endif

                <tr>
                    <th>Payment added by:</th>
                    <td>
                        @if ($payment->payment_status == 1 && $payment->online_banking == 1)
                        <span class="badge badge-secondary">Online Banking</span>
                        @elseif ($payment->payment_status == 1 && $payment->online_banking == 0)
                        <span class="badge badge-secondary">{{getAdminName($payment->admin_id)}}</span>
                        @else - @endif
                    </td>

                    <th>Payment updated at:</th>
                    <td>
                        {{date('d-m-Y', strtotime($payment->updated_at))}}
                    </td>
                </tr>

                @endif

                @if (Route::currentRouteName() == 'frontend.high-court.show')
                <tr>
                    @if ($payment->payment_status == 0)
                    <td>
                        @include('admin.high-court.partials.payment-upload-modal')

                        @if ($payment->voucher_file)
                        <span class="badge badge-success">File Attached</span>
                        @endif
                    </td>
                    @endif
                </tr>
                @endif

            </table>
        </div>
        @endforeach <br> <br> <br>

        @endforeach
    </div>
</fieldset>
@endif