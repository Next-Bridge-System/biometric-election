<div>
    <div class="container mt-5 mb-5">
        <h3 class="text-center font-weight-bold">Certificate Verification</h3>
        <div class="card p-4 shadow mt-4">
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="">Letter ID <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" wire:model.defer="letter_id">
                    @error('letter_id') <span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="">Lawyer CNIC <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" wire:model.defer="cnic_no" placeholder="12345-1234567-1">
                    @error('cnic_no') <span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="form-group col-md-12">
                    <button wire:click='search' class="btn btn-success">Search</button>
                </div>
                <div class="col-md-12" wire:loading>
                    <div class="text-center">
                        <h5>Loading ... Please wait</h5>
                    </div>
                </div>
            </div>

            <div class="row">
                <table class="table table-sm">
                    @if ($lawyer_request)
                    <tbody>
                        <tr>
                            <th class="bg-success text-center">Letter ID <span
                                    class="text-lg">#{{$lawyer_request->id}}</span></th>
                        </tr>
                        <tr>
                            <td class="text-center">
                                <b>Lawer Name:</b> {{$lawyer_request->lawyer_name}} <br>
                                <b>CNIC No:</b> {{$lawyer_request->cnic_no}} <br>
                                <b>Father/Husband:</b> {{$lawyer_request->father_name}} <br>

                                <div style="font-size:30px" class="mt-2">
                                    @if ($lawyer_request->approved == 1)
                                    <span class="badge badge-success">Approved</span>
                                    @elseif($lawyer_request->approved == 2)
                                    <span class="badge badge-danger">Disapproved</span>
                                    @elseif($lawyer_request->approved == 3)
                                    <span class="badge badge-warning">Pending</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    @endif

                    @if ($record_found == 2)
                    <tbody>
                        <tr>
                            <th class="bg-danger text-center">
                                The record have not found.
                            </th>
                        </tr>
                    </tbody>
                    @endif
                </table>

            </div>
        </div>
    </div>
</div>