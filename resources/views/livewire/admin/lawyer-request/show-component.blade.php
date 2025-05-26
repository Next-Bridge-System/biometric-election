<div>
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Lawyer Requests #{{$lawyer_request->id}}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{route('lawyer-requests.index',$lawyer_request->lawyer_request_sub_category->slug)}}"
                                class="btn btn-dark">Back</a>
                            @if (permission('lawyer_request_reset') &&
                            $lawyer_request->getMedia('lawyer_request_file')->first())
                            <button onclick="confirmation('lawyer-request-reset','{{$lawyer_request->id}}')"
                                class="btn btn-danger m-1">
                                <i class="fa fa-remove mr-1"></i>Reset</button>
                            @endif
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Lawyer Request Detail</h3>
                        </div>
                        <div class="card-body">
                            <div class="row table-responsive">
                                <table class="table table-bordered table-sm">
                                    <tr>
                                        @if ($payment->payment_status == 1)
                                        <td colspan="3">
                                            @if (!$lawyer_request->getMedia('lawyer_request_file')->first())
                                            <div class="col-md-6 form-group border pt-3 pb-3 pl-4">
                                                <label>Certificate Document<span class="required-star">*</span></label>
                                                <input type="file" wire:model="lawyer_request_file">
                                                @if ($lawyer_request_file)
                                                <div style="font-size:50px"><i class="fas fa-file-pdf"></i></div>
                                                @endif
                                                <div>
                                                    @error('lawyer_request_file') <span class="text-danger text-bold">{{
                                                        $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <button type="button" class="btn btn-success btn-sm"
                                                    wire:click="upload_lawyer_request_file()">Upload</button>
                                            </div>
                                            @else
                                            <section>
                                                @if ($lawyer_request->approved == 1)
                                                <a href="{{route('lawyer-requests.generate', $lawyer_request->id)}}"
                                                    target="_blank" class="btn btn-warning btn-sm mr-1">
                                                    <i class="fas fa-print mr-1"></i>Print System Generated File</a>
                                                @endif
                                            </section>
                                            @endif
                                        </td>
                                        @endif

                                        @php
                                        $condition_1 = $lawyer_request->getMedia('lawyer_request_file')->first() &&
                                        $payment->payment_status == 1;
                                        $condition_2 = $lawyer_request->lawyer_request_sub_category_id == 7 &&
                                        $payment->payment_status == 1;
                                        @endphp

                                        @if ($condition_1 || $condition_2)
                                        <td>
                                            @if ($lawyer_request->approved == 1)
                                            <button class="btn btn-danger btn-sm"
                                                wire:click="change_status({{$lawyer_request->id}}, 2)">Disapprove</button>
                                            @else
                                            <button class="btn btn-primary btn-sm"
                                                wire:click="change_status({{$lawyer_request->id}}, 1)">Approved</button>
                                            @endif

                                            @if ($lawyer_request->approved == 1)
                                            <span class="badge badge-success">
                                                <i class="fas fa-check mr-1"></i>Approved</span>
                                            @elseif($lawyer_request->approved == 2)
                                            <span class="badge badge-danger">Disapproved</span>
                                            @elseif($lawyer_request->approved == 3)
                                            <span class="badge badge-warning">Pending</span>
                                            @endif
                                        </td>
                                        @endif

                                    </tr>

                                    <tr>
                                        <th>Lawyer Request</th>
                                        <td colspan="3">
                                            <span class="badge badge-primary">{{$lawyer_request->id ?? '-'}}</span>
                                            <span class="badge badge-primary">{{$lawyer_request->lawyer_type ??
                                                ''}}</span>
                                            <span
                                                class="badge badge-primary">{{$lawyer_request->lawyer_request_category->name
                                                ?? ''}}</span>
                                            <span
                                                class="badge badge-primary">{{$lawyer_request->lawyer_request_sub_category->name
                                                ?? ''}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Lawyer Name</th>
                                        <td>{{$lawyer_request->lawyer_name ?? '-'}}</td>
                                        <th>Father/Husband Name</th>
                                        <td>{{$lawyer_request->father_name ?? '-'}}</td>
                                    </tr>
                                    <tr>
                                        <th>Cnic No</th>
                                        <td>{{$lawyer_request->cnic_no ?? '-'}}</td>
                                        <th>License no</th>
                                        <td>{{$lawyer_request->license_no ?? '-'}}</td>
                                    </tr>
                                    <tr>
                                        <th>Enr Date LC</th>
                                        <td>{{$lawyer_request->enr_date_lc ?? '-'}}</td>
                                        <th>Enr Date HC</th>
                                        <td>{{$lawyer_request->enr_date_hc ?? '-'}}</td>
                                    </tr>
                                    <tr>
                                        <th>Address</th>
                                        <td>{{$lawyer_request->address ?? '-'}}</td>
                                        <th>Voter Member</th>
                                        <td>{{$lawyer_request->bar->name ?? '-'}}</td>
                                    </tr>
                                    <tr>
                                        @if ($lawyer_request->embassy_name)
                                        <th>Embassy Name</th>
                                        <td>{{$lawyer_request->embassy_name}}</td>
                                        @endif

                                        @if ($lawyer_request->visit_country)
                                        <th>Visit Country</th>
                                        <td>{{$lawyer_request->visit_country}}</td>
                                        @endif

                                        @if ($lawyer_request->society_name)
                                        <th>Society Name</th>
                                        <td>{{$lawyer_request->society_name}}</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th>Reason</th>
                                        <td colspan="3">{{$lawyer_request->reason ?? '-'}}</td>
                                    </tr>
                                    <tr>
                                        <th>Amount</th>
                                        <td>Rs {{$lawyer_request->amount ?? '-'}}/-</td>
                                    </tr>
                                    <tr>
                                        <th>Created at</th>
                                        <td>{{$lawyer_request->created_at ?? '-'}}</td>
                                        <th>Updated at</th>
                                        <td>{{$lawyer_request->updated_at ?? '-'}}</td>
                                    </tr>
                                </table>

                                <table class="table table-sm table-bordered">
                                    <tr class="text-center bg-success">
                                        <th colspan="4">Payment</th>
                                    </tr>
                                    <tr>
                                        <th>Total Amount:</th>
                                        <td><span class="badge badge-success">{{$lawyer_request->amount}}PKR</span></td>

                                        <th>Payment:</th>
                                        <td>
                                            @include('livewire.admin.lawyer-request.partials.add-payment')
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Bank Name:</th>
                                        <td>{{$payment->bank_name}}</td>

                                        <th>Voucher Amount</th>
                                        <td><span class="badge badge-success">{{$payment->amount}} PKR</span></td>
                                    </tr>
                                    <tr>
                                        <th>Payment Status:</th>
                                        <td>
                                            @if ($payment->payment_status == 1)
                                            <span class="badge badge-success">Paid</span>
                                            @else
                                            <span class="badge badge-danger">Unpaid</span>
                                            @endif

                                            @if ($payment->acct_dept_payment_status == 1)
                                            <span class="badge badge-success">Approved</span>
                                            @elseif($payment->acct_dept_payment_status == 0)
                                            <span class="badge badge-danger">Disapproved</span>
                                            @endif
                                        </td>

                                        <th>Paid Date:</th>
                                        <td>
                                            {{getDateFormat($payment->paid_date)}}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Attached Voucher File:</th>
                                        <td>
                                            @if ($lawyer_request_voucher_file)
                                            <img src="{{ $lawyer_request_voucher_file->temporaryUrl() }}"
                                                class="custom-image-preview mt-2">
                                            @endif

                                            @if (!$lawyer_request_voucher_file)
                                            <span wire:ignore>
                                                <img src="{{ asset('storage/app/public/'.$payment->voucher_file)}}"
                                                    class="custom-image-preview mt-2">
                                            </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Print Bank Voucher:</th>
                                        <td>
                                            <a href="{{route('frontend.lawyer-requests.voucher', $lawyer_request_id)}}"
                                                class="btn btn-success btn-sm text-center" target="_blank">
                                                <i class="fas fa-print mr-1"></i>Bank Voucher</a>
                                        </td>

                                        <th>Voucher No:</th>
                                        <td>{{$payment->voucher_no}}</td>
                                    </tr>
                                    <tr>
                                        <th>Transaction ID:</th>
                                        <td>{{$payment->transaction_id}}</td>

                                        <th>Added by:</th>
                                        <td>
                                            @if ($payment->payment_status == 1 && $payment->online_banking == 1)
                                            Online Banking
                                            @elseif ($payment->payment_status == 1 && $payment->online_banking == 0)
                                            {{getAdminName($payment->admin_id)}}
                                            @else N/A @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Created at:</th>
                                        <td>{{getDateFormat($payment->created_at)}}</td>
                                        <th>Updated at:</th>
                                        <td>{{getDateFormat($payment->updated_at)}}</td>
                                    </tr>

                                    @if ($payment->reset_at)
                                    <tr>
                                        <th>Reset by:</th>
                                        <td>{{getAdminName($payment->reset_by)}}</td>
                                        <th>Reset at:</th>
                                        <td>{{getDateFormat($payment->reset_at)}}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('livewire.loader')
</div>