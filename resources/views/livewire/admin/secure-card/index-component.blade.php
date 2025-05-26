<div>
    <section class="content">

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="text-bold">Secure Card - Queue Management</h5>
                            <button data-toggle="modal" data-bs-toggle="tooltip" data-target="#import"
                                class="btn btn-primary btn-sm float-right">Import/Export</button>
                        </div>
                        <div class=" card-body">
                            <div class="">
                                <table id=""
                                    class="table table-bordered table-striped table-sm text-uppercase text-center">
                                    <thead>
                                        <tr>
                                            <th>Sr.no.</th>
                                            <th>Application ID</th>
                                            <th>Application Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($records as $record)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$record->application_id}}</td>
                                            <td>{{$record->application_type}}</td>
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

        <div wire:ignore.self class="modal fade" id="import" tabindex="-1" role="dialog"
            aria-labelledby="roleFormModalTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Import/Export</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form wire:submit.prevent="import">
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="">Application Type</label>
                                    <select wire:model="app_type" class="form-control custom-select">
                                        <option value="">Select</option>
                                        <option value="lower_court">Lower Court</option>
                                        <option value="high_court">High Court</option>
                                    </select>
                                    @error('app_type') <span>{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="">File</label>
                                    <input type="file" wire:model="file" class="form-control">
                                    @error('file') <span>{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary btn-sm float-left m-1"
                                    wire:click="export">Export</button>
                                <button type="button" class="btn btn-primary btn-sm float-left m-1"
                                    wire:click="exportLetters">Export Letters</button>
                                <button type="button" class="btn btn-primary btn-sm float-left m-1"
                                    wire:click="exportEnvelops">Export Envelops</button>
                                <button type="button" class="btn btn-info btn-sm float-left m-1"
                                    wire:click="exportImages">Export Images</button>
                                <button type="submit" class="btn btn-success float-right">Import</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

    </section>


    @include('livewire.loader')
</div>