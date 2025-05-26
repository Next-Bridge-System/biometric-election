<a href="{{route('frontend.vouchers.print',$voucher->id)}}" target="_blank"
    class="btn btn-success btn-sm float-right mb-4 mt-2 ml-2"><i class="fas fa-print mr-1"></i>DOWNLOAD ALL</a>

@if (Auth::guard('admin')->check())

<button type="button" class="btn btn-primary btn-sm float-right mb-4 mt-2 ml-2" data-toggle="modal"
    data-target="#exampleModal"><i class="fas fa-print mr-1"></i>Payment Status</button>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{route('vouchers.payment')}}" method="POST"> @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Payment Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="voucher_id" id="voucher_id" value="{{$voucher->id}}">
                    <div class="col-md-12 form-group">
                        <label for="">Voucher Payment Status</label>
                        <select name="payment_status" id="payment_status" class="form-control custom-select">
                            <option value="">--Select Payment Status--</option>
                            <option value="PAID" {{$voucher->payment_status == "PAID" ? 'selected' :
                                ''}}>PAID</option>
                            <option value="UNPAID" {{$voucher->payment_status == "UNPAID" ? 'selected' :
                                ''}}>UNPAID</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endif

<table class="table table-striped table-bordered">
    <tr>
        <th>Lawyer Type:</th>
        <td>
            @if ($voucher->application_type == 1) LOWER COURT
            @elseif ($voucher->application_type == 2) HIGH COURT
            @else N/A
            @endif
        </td>

        <th>Payment Status:</th>
        <td>
            <span
                class="badge badge-{{$voucher->payment_status == 'PAID' ? 'success' : 'danger'}} text-md">{{$voucher->payment_status}}</span>
        </td>
    </tr>
    <tr>
        <th>Name:</th>
        <td>{{$voucher->name}}</td>

        <th>Father Name:</th>
        <td>{{$voucher->father_name}}</td>
    </tr>
    <tr>
        <th>Date of Birth:</th>
        <td>{{$voucher->date_of_birth}}</td>

        <th>Station:</th>
        <td>{{$voucher->bar->name}}</td>
    </tr>
    <tr>
        <th>Nationality:</th>
        <td>{{$voucher->nationality ?? '--'}}</td>

        <th>CNIC:</th>
        <td>{{$voucher->cnic_no}}</td>
    </tr>
    <tr>
        <th>Home Address:</th>
        <td>{{$voucher->home_address ?? '--'}}</td>

        <th>Postal Address:</th>
        <td>{{$voucher->postal_address ?? '--'}}</td>
    </tr>
    <tr>
        <th>Email Address:</th>
        <td>{{$voucher->email ?? '--'}}</td>

        <th>Contact:</th>
        <td>+92{{$voucher->contact}}</td>
    </tr>
    <tr>
        <th>Degree Type:</th>
        <td>{{$voucher->degree_type}}</td>
    </tr>
</table>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>S/N</th>
            <th>Voucher No.</th>
            <th>Voucher Name</th>
            <th>Voucher Type</th>
            <th>Voucher Amount</th>
            <th>Payment Status</th>
        </tr>
    </thead>
    @foreach ($voucher->payments as $payment)
    <tr>
        <td>{{$loop->iteration}}</td>
        <td>{{$payment->vch_no}}</td>
        <td>{{$payment->vch_name}}</td>
        <td>{{$payment->vch_type}}</td>
        <td>RS {{$payment->vch_amount}}/-</td>
        <td>{{$payment->vch_payment_status == 1 ? 'PAID' : 'UNPAID'}}</td>
    </tr>
    @endforeach
</table>
