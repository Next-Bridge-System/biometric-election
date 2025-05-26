<div>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="text-capitalize">Manage Members</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <a href="{{route('members.create')}}" class="btn btn-success btn-sm m-1">
                            <i class="fas fa-plus mr-1"></i>Add Member</a>
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
                                Total Members: {{$records->total()}}
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2 form-group">
                                    <label for="">Member Name</label>
                                    <input wire:model="search_member_name" type="search" class="form-control">
                                </div>
                                <div class="col-md-2 form-group">
                                    <label for="">Division</label>
                                    <select wire:model="search_division_id" class="form-control custom-select">
                                        <option value="">All Divisions</option>
                                        @foreach ($divisions as $division)
                                        <option value="{{$division->id}}">{{$division->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 form-group">
                                    <label for="">Bar</label>
                                    <select wire:model="search_bar_id" class="form-control custom-select">
                                        <option value="">All Bars</option>
                                        @foreach ($bars as $bar)
                                        <option value="{{$bar->id}}">{{$bar->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 form-group">
                                    <label for="">Group</label>
                                    <select wire:model="search_group_id" class="form-control custom-select">
                                        <option value="">All Groups</option>
                                        @foreach ($groups as $group)
                                        <option value="{{$group->id}}">{{$group->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-9">
                                    <table id="" class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Name</th>
                                                <th>Designation</th>
                                                <th>Mobile No</th>
                                                <th>Division</th>
                                                <th>District</th>
                                                <th>Tehsil</th>
                                                <th>Bar</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($records as $record)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>
                                                    {{$record->name}} <br>
                                                    @if ($record->group)
                                                    <span class="badge badge-info">{{$record->group->name}}</span>
                                                    @endif
                                                </td>
                                                <td>{{$record->designation}}</td>
                                                <td>{{'0'.$record->mobile_no}}</td>
                                                <td>{{getDivisionName($record->division_id)}}</td>
                                                <td>{{getDistrictName($record->district_id)}}</td>
                                                <td>{{getTehsilName($record->tehsil_id)}}</td>
                                                <td>{{getBarName($record->bar_id)}}</td>
                                                <td>
                                                    @if ($record->status == 1)
                                                    <span class="badge badge-success">Active</span>
                                                    @else
                                                    <span class="badge badge-danger">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (permission('edit-members'))
                                                    <a href="{{route('members.edit', $record->id)}}"><span
                                                            class="btn btn-primary btn-xs mr-1"><i
                                                                class="fas fa-edit mr-1"
                                                                aria-hidden="true"></i>Edit</span></a>
                                                    @endif

                                                    <a href="{{route('members.show', $record->id)}}"><span
                                                            class="btn btn-primary btn-xs mr-1"><i
                                                                class="fas fa-eye mr-1"
                                                                aria-hidden="true"></i>View</span></a>

                                                    {{-- @if (!$record->member_group_id) --}}
                                                    <section>
                                                        @if(in_array($record->id,$selected_members))
                                                        <span class="badge badge-success">
                                                            <i class="fas fa-check mr-1"></i> Added</span>
                                                        @else
                                                        <button @if (count($selected_members) !=2)
                                                            wire:click="addToGroupCart('{{$record->id}}')" @else
                                                            disabled @endif class="btn btn-success btn-xs">
                                                            <i class="fas fa-plus mr-1"></i>Group</button>
                                                        @endif
                                                    </section>
                                                    {{-- @endif --}}
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="10" class="text-center">No Record Found.</td>
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
                                <div class="col-md-3">
                                    <h5 class="text-center"><b><u>Member Groups</u></b></h5>
                                    <table class="table table-bordered table-striped table-sm">
                                        <tr>
                                            <th>Member</th>
                                            <th>Division</th>
                                        </tr>
                                        @forelse ($selected_members_list as $cart_item)
                                        <tr>
                                            <td>{{$cart_item['name']}}</td>
                                            <td>{{$cart_item['division']}}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="2">There are no members add in a group cart.</td>
                                        </tr>
                                        @endforelse
                                    </table>

                                    @if (count($selected_members_list) == 2)
                                    <button wire:click='saveGroup'
                                        class="btn btn-success btn-sm mr-1 float-right">Group</button>
                                    <button wire:click='resetGroup'
                                        class="btn btn-danger btn-sm mr-1 float-right">Reset</button>
                                    @endif
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
