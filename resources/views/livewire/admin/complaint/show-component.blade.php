<div>
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Lawyer Complaints</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            @include('livewire.admin.complaint.notice')

                            <a href="{{route('complaint.index')}}" class="btn btn-dark btn-sm mr-1"><i
                                    class="fas fa-list mr-1"></i>Complaints List</a>
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
                            <h3 class="card-title">Lawyer Complaint Detail</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="table-responsive" style="white-space: nowrap;">
                                    <table class="table table-bordered table-sm">
                                        <tr>
                                            <th>Complaint ID</th>
                                            <td>
                                                <span class="badge badge-primary text-uppercase">{{$complaint->id}}</span>
                                                @if (isset($complaint->complaintStatus->name))
                                                <span class="badge badge-{{$complaint->complaintStatus->badge}} text-uppercase">{{$complaint->complaintStatus->name}}</span>
                                                @endif                                                
                                                <span class="badge badge-secondary text-uppercase">{{$complaint->complaintType->committee ?? ""}}</span>
                                                <span class="badge badge-secondary text-uppercase">{{$complaint->complaintType->name ?? ""}}</span>
                                            </td>
                                            <td>
                                                <select wire:model="complaint_status_id" wire:change="changeComplaintStatus" class="form-control form-control-sm">
                                                    @foreach ($complaint_statuses as $status)
                                                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select wire:model="complaint_type_id" wire:change="changeComplaintType" class="form-control form-control-sm">
                                                    <option value="0">Select</option>
                                                    @foreach ($complaint_types->groupBy('committee') as $committee => $types)
                                                        <optgroup label="{{ $committee }}">
                                                            @foreach ($types as $type)
                                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    @endforeach
                                                </select>
                                                
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="bg-secondary" colspan="4">Complainant Section</th>
                                        </tr>
                                        <tr>
                                            <th>Complainant Name</th>
                                            <td>{{$complaint->complainant_name ?? '-'}}</td>
                                            <th>Father/Husband</th>
                                            <td>{{$complaint->complainant_father ?? '-'}}</td>
                                        </tr>
                                        <tr>
                                            <th>CNIC</th>
                                            <td>{{$complaint->complainant_cnic ?? '-'}}</td>
                                            <th>Phone</th>
                                            <td>{{$complaint->complainant_phone ?? '-'}}</td>
                                        </tr>

                                        <tr>
                                            <th class="bg-secondary" colspan="4">Defendant Section</th>
                                        </tr>
                                        <tr>
                                            <th>Defendant Name</th>
                                            <td>{{$complaint->defendant_name ?? '-'}}</td>
                                            <th>Defendant CNIC</th>
                                            <td>{{$complaint->defendant_cnic ?? '-'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Created at</th>
                                            <td>{{$complaint->created_at}}</td>
                                            <th>Updated at</th>
                                            <td>{{$complaint->updated_at}}</td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="table-responsive" style="white-space: nowrap;">
                                    <table class="table table-sm table-bordered">
                                        <tr class="bg-success">
                                            <th colspan="4">Payment</th>
                                        </tr>
                                        <tr>
                                            <th>Total Amount:</th>
                                            <td><span class="badge badge-success">{{$payment->amount}} PKR</span></td>

                                            <th>Payment:</th>
                                            <td>
                                                @include('livewire.admin.complaint.payment')
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
                                                @if ($voucher_file)
                                                <img src="{{ $voucher_file->temporaryUrl() }}"
                                                    class="custom-image-preview mt-2">
                                                @endif

                                                @if (!$voucher_file)
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
                                                <a href="{{route('frontend.complaint-voucher', $payment->id)}}"
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
        </div>
    </section>

    @include('livewire.loader')
</div>