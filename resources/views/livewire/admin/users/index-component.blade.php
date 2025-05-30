<div>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="text-capitalize">Manage Users {{$slug == 'gc' ? '- Data Verification': ''}}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a class="btn btn-primary" href="{{route('users.create')}}">Add User</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                Total Users: {{$records->total()}}
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="bg-light p-4 border">
                                <div class="row">
                                    <div class="form-group col-md-2">
                                        <label for="">USER ID:</label>
                                        <input wire:model="search_user_id" type="search" class="form-control">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="">LAWYER NAME:</label>
                                        <input wire:model="search_name" type="search" class="form-control">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="">CNIC NO:</label>
                                        <input wire:model="search_cnic_no" type="search" class="form-control">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="">PHONE NO:</label>
                                        <input wire:model="search_phone_no" type="search" class="form-control">
                                    </div>

                                    @if ($slug == 'gc')
                                    <div class="form-group col-md-2">
                                        <label for="">VERIFICATION:</label>
                                        <select wire:model="search_gc_status" class="form-control custom-select">
                                            <option value="" selected>Select Status</option>
                                            <option value="pending">Pending</option>
                                            <option value="approved">Approved</option>
                                            <option value="disapproved">Disapproved</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="">Request From:</label>
                                        <input type="date" wire:model="search_request_from" class="form-control">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="">Request To:</label>
                                        <input type="date" wire:model="search_request_to" class="form-control">
                                    </div>
                                    @endif

                                    <div class="form-group col-md-2">
                                        <label for="">USER TYPE:</label>
                                        <select wire:model="search_user_type" class="form-control custom-select">
                                            <option value="" selected>All</option>
                                            <option value="intimation">Intimation</option>
                                            <option value="lc">Lower Court</option>
                                            <option value="hc">High Court</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label for="">APP STATUS:</label>
                                        <select wire:model="search_app_status" class="form-control custom-select">
                                            <option value="" selected>All</option>
                                            @foreach ($app_statuses as $status)
                                            <option value="{{$status->key}}">{{ucfirst($status->value)}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    @if ($slug == 'all')
                                    <div class="form-group col-md-2">
                                        <label for="">Register Date:</label>
                                        <input type="date" wire:model="search_register_date" class="form-control">
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="">
                                <table id="" class="table table-bordered table-sm text-uppercase table-hover">
                                    <thead class="bg-success">
                                        <tr>
                                            <th>Sr.No.</th>
                                            <th>User ID</th>
                                            <th>Name</th>
                                            <th>Cnic</th>
                                            <th>Phone</th>

                                            @if ($slug == 'gc')
                                            <th>Status</th>
                                            @endif

                                            <th>Type</th>

                                            @if ($slug == 'gc')
                                            <th>Request Date</th>
                                            @endif

                                            @if ($slug == 'all')
                                            <th>Otp</th>
                                            <th>Register Date</th>
                                            @endif




                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($records as $record)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$record->id}}</td>
                                            <td>{{$record->name}}</td>
                                            <td>{{$record->cnic_no}}</td>
                                            <td>{{$record->formatted_phone}}</td>
                                            <td>
                                                <span class="badge badge-primary text-uppercase">
                                                    {{$record->register_as}}</span>
                                            </td>
                                            @if ($slug == 'gc')
                                            <td>
                                                <span
                                                    class="badge badge-{{user_gc_status($record->gc_status)['badge']}} text-uppercase">
                                                    {{user_gc_status($record->gc_status)['name']}}</span>
                                            </td>
                                            @endif

                                            @if ($slug == 'gc')
                                            <td>{{getdateFormat($record->gc_requested_at)}}</td>
                                            @endif

                                            @if ($slug == 'all')
                                            <td>{{$record->otp}}</td>
                                            <td>{{getdateFormat($record->created_at)}}</td>
                                            @endif

                                            <td>

                                                @if ($slug == 'gc')
                                                <a href="{{route('gc-users.show', $record->id)}}"
                                                    class="btn btn-primary btn-xs mr-1">
                                                    <i class="fas fa-list mr-1"></i>Detail</a>
                                                @endif

                                                @if (permission('edit-users') && $slug == 'all')
                                                <a href="{{route('users.edit', ['id' => $record->id])}}"
                                                    class="edit btn btn-primary btn-xs mr-1">
                                                    <i class="fas fa-edit mr-1"></i>Edit</a>
                                                @endif

                                                <a href="{{route('users.show', ['id' => $record->id])}}"
                                                    class="edit btn btn-primary btn-xs mr-1">
                                                    <i class="fas fa-list mr-1"></i>Detail</a>

                                                <a href="{{route('users.audit', $record->id)}}"
                                                    class="edit btn btn-primary btn-xs mr-1">
                                                    <i class="fas fa-folder mr-1"></i>Audit</a>

                                                @if (permission('users-direct-login'))
                                                <a href="{{route('frontend.dashboard', ['id' => $record->id, 'direct_login' => TRUE])}}"
                                                    class="edit btn btn-success btn-xs mr-1" target="_blank">
                                                    <i class="fas fa-sign-in-alt mr-1"></i>Login</a>
                                                @endif


                                                @if (permission('manage-biometric-verification'))
                                                    @if (in_array($record->register_as, ['lc', 'gc_lc', 'hc', 'gc_hc']))
                                                        @if ($record->biometric_status == 1)
                                                            <span class="badge badge-success" title="Biometric Verified">
                                                                <i class="fas fa-check-circle"></i> Verified
                                                            </span>
                                                        @else
                                                            <a href="{{ route('biometrics.registration', $record->id) }}"
                                                                class="edit btn btn-warning btn-xs mr-1" target="_blank">
                                                                <i class="fas fa-fingerprint mr-1"></i>Biometric
                                                            </a>
                                                        @endif
                                                    @endif
                                                @endif

                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No Record Found.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="row">
                                    <div class="col-lg-6">
                                        Showing {{ $records->firstItem() }} to {{ $records->lastItem() }} of total
                                        {{ $records->total() }} entries
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="d-flex justify-content-end px-2 mx-2 my-2">
                                            {{ $records->links() }}
                                        </div>
                                    </div>
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