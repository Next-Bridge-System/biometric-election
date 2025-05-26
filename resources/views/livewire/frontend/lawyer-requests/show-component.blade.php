<div>
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Lawyer Request Detail</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('frontend.lawyer-requests.index')}}"
                                class="btn btn-dark">Back</a>
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
                            <div class="row">
                                <table class="table table-bordered table-sm">
                                    <tr>
                                        <td colspan="3">
                                            @if (!$payment->voucher_file && $payment->payment_status == 0)
                                            <div class="col-md-6 form-group border pt-3 pb-3 pl-4">
                                                <label>Bank Voucher File<span class="required-star">*</span></label>
                                                <input type="file" wire:model="voucher_file">
                                                @if ($voucher_file)
                                                <img src="{{ $voucher_file->temporaryUrl() }}"
                                                    class="custom-image-preview mt-2">
                                                @endif
                                                @error('voucher_file') <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label>
                                                    Voucher No/Transaction ID <span class="required-star">*</span>
                                                </label>
                                                <input type="text" wire:model="transaction_id" class="form-control">
                                                @error('transaction_id') <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label>
                                                    Paid Date <span class="required-star">*</span>
                                                </label>
                                                <input type="date" wire:model="paid_date" class="form-control">
                                                @error('paid_date') <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <button type="button" class="btn btn-success btn-sm"
                                                    wire:click="upload_voucher()">Upload</button>
                                            </div>
                                            @else
                                            <span class="badge badge-primary">
                                                <i class="fas fa-check mr-1"></i>Voucher Attached</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($lawyer_request->approved == 1)
                                            <span class="badge badge-success text-sm">
                                                <i class="fas fa-check mr-1"></i>Approved</span>

                                            <a href="{{route('lawyer-requests.generate', $lawyer_request->id)}}"
                                                target="_blank" class="btn btn-warning btn-sm mr-1">
                                                <i class="fas fa-print mr-1"></i>Print Certificate</a>
                                            @else
                                            <span class="badge badge-primary text-sm">
                                                <i class="fas fa-copy mr-1"></i>Pending</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Lawyer Request ID</th>
                                        <td>{{$lawyer_request->id ?? '-'}}</td>
                                        <th>Category</th>
                                        <td>{{$lawyer_request->lawyer_request_category->name ?? '-'}} :
                                            {{$lawyer_request->lawyer_request_sub_category->name ?? '-'}}
                                        </td>

                                    </tr>
                                    <tr>
                                        <th>Lawyer Type</th>
                                        <td>{{$lawyer_request->lawyer_type ?? '-'}}</td>
                                        <th>Lawyer Name</th>
                                        <td>{{$lawyer_request->lawyer_name ?? '-'}}</td>
                                    </tr>
                                    <tr>
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
                                        <th>Embassy Name</th>
                                        <td>{{$lawyer_request->embassy_name ?? '-'}}</td>
                                        <th>Member of</th>
                                        <td>{{$lawyer_request->member_of ?? '-'}}</td>
                                    </tr>
                                    <tr>
                                        <th>Visit Country</th>
                                        <td>{{$lawyer_request->visit_country ?? '-'}}</td>
                                        <th>City</th>
                                        <td>{{$lawyer_request->city ?? '-'}}</td>
                                    </tr>
                                    <tr>
                                        <th>Amount</th>
                                        <td>Rs {{$lawyer_request->amount ?? '-'}}/-</td>
                                        <th>Status</th>
                                        <td>{{$lawyer_request->status ?? '-'}}</td>
                                    </tr>
                                    <tr>
                                        <th>Approved</th>
                                        <td>{{$lawyer_request->approved ?? '-'}}</td>
                                    </tr>
                                    <tr>
                                        <th>Created at</th>
                                        <td>{{$lawyer_request->created_at ?? '-'}}</td>
                                        <th>Updated at</th>
                                        <td>{{$lawyer_request->updated_at ?? '-'}}</td>
                                    </tr>
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