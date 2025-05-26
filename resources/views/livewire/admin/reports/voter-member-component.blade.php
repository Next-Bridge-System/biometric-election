<div>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5 class="text-capitalize">Reports - Voter Member</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <div>
                            {{-- <button wire:click="export" class="btn btn-primary" {{$records->total() > 10000 ?
                                'disabled': ''}}><i class="fa fa-print mr-1"></i>Print</button> --}}

                            @if (10000 > $records->total())
                            <a href="{{ route('reports.voter-member.export', ['filter' => $filter]) }}"
                                class="btn btn-primary btn-sm" target="_blank"><i class="fa fa-print mr-1"></i>Print</a>
                            @endif

                            @include('livewire.admin.reports.voter-member-filter')
                        </div>
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
                            <div>
                                <h3 class="card-title">
                                    Total Records: {{$records->total()}}
                                </h3>
                            </div>
                        </div>
                        <div class="card-header">
                            <div class="filters d-flex mb-1" style="overflow-x: auto; max-width: 100%;">
                                <div class="form-group mr-1">
                                    <label for="">TYPE</label>
                                    <select wire:model.defer="search_user_type" class="form-control custom-select">
                                        <option value="lc">Lower Court</option>
                                        <option value="hc">High Court</option>
                                    </select>
                                </div>
                                <div class="form-group mr-1">
                                    <label for="">USER STATUS</label>
                                    <select wire:model.defer="search_user_status" class="form-control custom-select">
                                        <option value="all">All Users</option>
                                        <option value="verified">Verified</option>
                                        <option value="unverified">Not Verified</option>
                                    </select>
                                </div>
                                <div class="form-group mr-1">
                                    <label for="">APP STATUS</label>
                                    <select wire:model.defer="search_app_status" class="form-control custom-select">
                                        <option value="all">All Status</option>
                                        @foreach ($app_statuses as $status)
                                        <option value="{{$status->id}}">{{$status->value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mr-1">
                                    <label for="">GENDER</label>
                                    <select wire:model.defer="search_gender" class="form-control custom-select">
                                        <option value="all">All</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                                <div class="form-group mr-1">
                                    <label for="">VOTER MEMBER</label>
                                    <select wire:model.defer="search_voter_member" class="form-control custom-select">
                                        <option value="" selected>All Stations</option>
                                        @foreach ($bars as $bar)
                                        <option value="{{$bar->id}}">{{$bar->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mr-1">
                                    <label for="">ENR DATE - FROM</label>
                                    <input type="date" wire:model.defer="search_start_date" class="form-control">
                                </div>
                                <div class="form-group mr-1">
                                    <label for="">ENR DATE - TO</label>
                                    <input type="date" wire:model.defer="search_end_date" class="form-control">
                                </div>
                                <div class="form-group mr-1">
                                    <label for="">REQ DATE - FROM</label>
                                    <input type="date" wire:model.defer="search_request_date_from" class="form-control">
                                </div>
                                <div class="form-group mr-1">
                                    <label for="">REQ DATE - TO</label>
                                    <input type="date" wire:model.defer="search_request_date_to" class="form-control">
                                </div>

                                {{-- <div class="form-group mr-1">
                                    <label for="">EXTRA COLS</label>
                                    <select id="extra_cols" wire:model.defer="extra_cols" multiple="true"
                                        class="form-control custom-select">
                                        <option value="image">IMAGE</option>
                                        <option selected value="dob">DOB</option>
                                        <option value="lc_ledger">LC Ledger</option>
                                        <option value="phone">Phone</option>
                                    </select>
                                </div> --}}
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-success btn-sm" wire:click="search">
                                        <i class="fa fa-search mr-1"></i>Search</button>
                                    <button class="btn btn-danger btn-sm" wire:click="clear" {{!$search ? 'disabled'
                                        : '' }}>
                                        <i class="fa fa-refresh mr-1"></i>Reset</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm text-uppercase" style="font-size: 14px; white-space: nowrap;">
                                    <thead class="bg-success text-white">
                                        <tr>
                                            <th>Sr #</th>
                                            <th>User ID</th>
                                            <th>Lawyer Name</th>
                                            <th>Father/Husband</th>

                                            @if (in_array('dob', $extra_cols))
                                            <th>DOB</th>
                                            @endif

                                            @if (in_array('cnic_no', $extra_cols))
                                            <th>CNIC</th>
                                            @endif

                                            <th>LC Date</th>

                                            @if (in_array('lc_ledger', $extra_cols))
                                            <th>LC Ledger</th>
                                            @endif

                                            @if ($search_user_type == 'lc' && in_array('lc_license', $extra_cols))
                                            <th>LC License #</th>
                                            @endif

                                            @if ($search_user_type == 'hc')
                                                <th>HC Date</th>

                                                @if (in_array('hc_hcr', $extra_cols))
                                                <th>HCR #</th>
                                                @endif
                                                
                                                @if (in_array('hc_license', $extra_cols))
                                                <th>HC License #</th>
                                                @endif

                                            @endif

                                            <th>Address</th>

                                            @if (in_array('phone', $extra_cols))
                                            <th>Phone</th>
                                            @endif

                                            <th>App Status</th>

                                            @if (in_array('image', $extra_cols))
                                            <th>Image</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($records as $record)
                                        <tr class="{{$record->status == 'approved' ? 'bg-light-green': ''}}">
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$record->user_id}}</td>
                                            <td>{{$record->lawyer}}</td>
                                            <td>{{$record->father}}</td>

                                            @if (in_array('dob', $extra_cols))
                                            <td>{{$record->dob}}</td>
                                            @endif

                                            @if (in_array('cnic_no', $extra_cols))
                                            <td>{{$record->cnic_no}}</td>
                                            @endif

                                            <td>{{$record->enr_date_lc}}</td>

                                            @if (in_array('lc_ledger', $extra_cols))
                                            <td>{{$record->lc_ledger}}</td>
                                            @endif

                                            @if ($search_user_type == 'lc' && in_array('lc_license', $extra_cols))
                                            <td>{{$record->lc_license}}</td>
                                            @endif

                                            @if ($search_user_type == 'hc')
                                                <td>{{$record->enr_date_hc}}</td>

                                                @if (in_array('hc_hcr', $extra_cols))
                                                <td>{{$record->hc_hcr}}</td>
                                                @endif
                                                
                                                @if (in_array('hc_license', $extra_cols))
                                                <td>{{$record->hc_license}}</td>
                                                @endif
                                            @endif

                                            <td>{{$record->address}}</td>

                                            @if (in_array('phone', $extra_cols))
                                            <td>{{$record->phone}}</td>
                                            @endif

                                            <td>{{$record->app_status}}</td>

                                            @if (in_array('image', $extra_cols))
                                            <td>
                                                <img style="width:50px"
                                                    src="{{asset('storage/app/public/'.$record->profile_image)}}"
                                                    alt="image">
                                            </td>
                                            @endif
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
                        <div class="card-footer">
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
    </section>

    @include('livewire.loader')
</div>

<style>
    .filters .form-control {
        font-size: 12px;
    }

    .filters label {
        font-size: 12px;
    }

    .filters .form-control {
        font-size: 12px;
        width: 100%;
        min-width: 125px;
    }
</style>


{{-- <script>
    document.addEventListener('livewire:load', function (event) {
        $('#filter_by').select2({
            maximumSelectionLength: 2
        });

        setTimeout(function (){
            $('#filter_by').trigger('change');
        },500);

        console.log(event);

        window.livewire.hook('message.processed', (event) => {
            console.log(event);
            $('#filter_by').select2({
                maximumSelectionLength: 2
            });
        });
    });
</script> --}}