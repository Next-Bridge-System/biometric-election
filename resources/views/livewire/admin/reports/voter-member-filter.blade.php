<button type="button" class="btn btn-primary btn-sm ml-1" data-toggle="modal" data-target="#vm_report_filter"><i
        class="fa fa-list mr-1"></i> Extra Columns </button>

<div wire:ignore.self class="modal fade" id="vm_report_filter" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <form>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id=""></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <fieldset class="border p-2">
                        <legend class="w-auto">Extra Columns List</legend>
                        <div class="row">
                            <div class="form-group col-md-12">
                                {{-- <div>
                                    <input type="checkbox" id="select_cols"> <label for="">SELECT ALL</label>
                                </div> --}}

                                @foreach ($extra_cols_list as $key=>$extra_col)
                                <div>
                                    <input type="checkbox" wire:model.defer="extra_cols" value={{$key}}>
                                    <span for="{{$key}}" class="">{{$extra_col}}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-sm btn-primary" wire:click="setColsForReport">Save
                        Changes</button>
                    <button class="btn btn-primary hidden loading_btn btn-sm" type="button" disabled><span
                            class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                        Loading...</button>
                </div>
            </div>
        </div>
    </form>
</div>