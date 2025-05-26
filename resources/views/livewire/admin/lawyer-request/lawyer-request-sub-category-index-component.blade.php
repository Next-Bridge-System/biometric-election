<div>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Lawyer Request Categories</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">

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
                                Total Categories : <span id="countTotal">{{$records->total()}}</span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <input wire:model="search" type="search" class="form-control my-1" id="search"
                                        placeholder="Search">
                                </div>
                            </div>
                            <div class="">
                                <table id="datatable" class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($records as $record)
                                        <tr>
                                            <td>{{$record->id}}</td>
                                            <td>{{$record->name}}</td>
                                            <td>Rs {{$record->amount}}/-</td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    @if ($record->status)
                                                    <input wire:click="changeStatus({{$record->id}}, 0)"
                                                        class="form-check-input" type="checkbox" checked>
                                                    <span class="badge badge-success">Active</span>
                                                    @else
                                                    <input wire:click="changeStatus({{$record->id}}, 1)"
                                                        class="form-check-input" type="checkbox">
                                                    <span class="badge badge-danger">Inactive</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
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