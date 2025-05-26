<div>
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card p-3">
                        <div class="card-header">
                            <h4 class="text-center text-bold">Lawyer Complaints</h4>

                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 form-group">
                                    <label for="">Applicant CNIC</label>
                                    <input wire:model="filters.applicant_cnic" type="search" class="form-control"
                                        placeholder="xxxxx-xxxxxxx-x">
                                </div>
                            </div>
                            <div class="">
                                <table id="" class="table table-bordered table-striped table-sm text-capitalize">
                                    <thead>
                                        <tr>
                                            <th>Complaint ID</th>
                                            <th>Applicant Name</th>
                                            <th>Applicant CNIC</th>
                                            <th>Lawyer Name</th>
                                            <th>Complaint Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($records as $record)
                                        <tr>
                                            <td>{{$record->id}}</td>
                                            <td>{{$record->applicant_name}} S/O {{$record->applicant_father}}</td>
                                            <td>{{$record->applicant_cnic}}</td>
                                            <td>{{$record->lawyer_name}}</td>
                                            <td>{{getdateFormat($record->created_at)}}</td>
                                            <td>
                                                @if ($record->status == 'open')
                                                <span class="badge badge-primary">Open</span>
                                                @elseif($record->status == 'hearing')
                                                <span class="badge badge-warning">Hearing</span>
                                                @elseif($record->status == 'close')
                                                <span class="badge badge-success">Close</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="10" class="text-center">No Record Found.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
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