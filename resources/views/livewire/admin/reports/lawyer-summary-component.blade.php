<div>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="text-capitalize">Reports - Laywer Summary</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <div>
                            {{-- <button wire:click="export" class="btn btn-primary" {{$records->total() > 10000 ?
                                'disabled': ''}}><i class="fa fa-print mr-1"></i>Print</button> --}}


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
                        <div class="card-body">
                            <div class="bg-light p-4 border">
                                <div class="filters d-flex" style=" overflow-y: scroll;">
                                    <div class="form-group mr-1">
                                        <label for="">DIVISIONS</label>
                                        <select wire:model.defer="search_division"
                                            class="form-control custom-select">
                                            <option value="" selected>All Divisions</option>
                                            @foreach ($divisions as $division)
                                            <option value="{{$division->id}}">{{$division->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mr-1">
                                        <label for="">VOTER MEMBER</label>
                                        <select wire:model.defer="search_voter_member"
                                            class="form-control custom-select">
                                            <option value="" selected>All Stations</option>
                                            @foreach ($bars as $bar)
                                            <option value="{{$bar->id}}">{{$bar->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- <div class="form-group mr-1">
                                        <label for="">USER STATUS</label>
                                        <select wire:model.defer="search_user_status"
                                            class="form-control custom-select">
                                            <option value="all">All Users</option>
                                            <option value="verified">Verified</option>
                                            <option value="unverified">Not Verified</option>
                                        </select>
                                    </div> --}}
                                    <div class="form-group mr-1">
                                        <label for="">APP STATUS</label>
                                        <select wire:model.defer="search_app_status" class="form-control custom-select">
                                            <option value="all">All Status</option>
                                            @foreach ($app_statuses as $status)
                                            <option value="{{$status->id}}">{{$status->value}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- <div class="form-group mr-1">
                                        <label for="">GENDER</label>
                                        <select wire:model.defer="search_gender" class="form-control custom-select">
                                            <option value="all">All</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
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
                                        <input type="date" wire:model.defer="search_request_date_from"
                                            class="form-control">
                                    </div>
                                    <div class="form-group mr-1">
                                        <label for="">REQ DATE - TO</label>
                                        <input type="date" wire:model.defer="search_request_date_to"
                                            class="form-control">
                                    </div>
                                    <div class="form-group mr-1">
                                        <label for="">IMAGE</label>
                                        <select wire:model.defer="search_profile_image"
                                            class="form-control custom-select">
                                            <option value="no">No</option>
                                            <option value="yes">Yes</option>
                                        </select>
                                    </div> --}}


                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-success" wire:click="search">
                                            <i class="fa fa-search mr-1"></i>Search</button>
                                        <button class="btn btn-danger" wire:click="clear" {{!$search ? 'disabled' : ''
                                            }}>
                                            <i class="fa fa-refresh mr-1"></i>Reset</button>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <table id="" class="table table-bordered table-sm text-uppercase"
                                    style="font-size: 14px">
                                    <thead class="bg-success text-white">
                                        <tr>
                                            <th>Sr.No.</th>
                                            <th>Divisions</th>
                                            <th>Voting Bar</th>
                                            <th>LC Active No.</th>
                                            <th>HC Active No.</th>
                                            <th>Total Active No.</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($records as $record)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>
                                                {{$record->name}}
                                            </td>
                                            <td>
                                                {{$record->bar_name}}
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <div>{{ getLawyerCounts($filter,$record->bar_id,'LC') }}</div>
                                                    <div><a class="text-dark" href="javascript:void(0)" wire:click="export('{{ $record->bar_id }}','LC')"><i class="fa fa-file-alt"></i></a></div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <div>{{ getLawyerCounts($filter,$record->bar_id,'HC') }}</div>
                                                    <div><a class="text-dark" href="javascript:void(0)" wire:click="export('{{ $record->bar_id }}','HC')"><i class="fa fa-file-alt"></i></a></div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <div>{{  getLawyerCounts($filter,$record->bar_id,'LC') + getLawyerCounts($filter,$record->bar_id,'HC') }}</div>
                                                    <div><a class="text-dark" href="javascript:void(0)" wire:click="export('{{ $record->bar_id }}','all')"><i class="fa fa-file-alt"></i></a></div>
                                                </div>

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

<style>
    .filters .form-control {
        font-size: 12px;
    }

    .filters label {
        font-size: 12px;
    }
</style>
