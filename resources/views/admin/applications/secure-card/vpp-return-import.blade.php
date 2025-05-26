<button type="button" class="btn btn-primary float-right btn-sm m-1" data-toggle="modal"
    data-target="#vppReturnImport">VPP Return Import</button>
<div class="modal fade" id="vppReturnImport" tabindex="-1" aria-labelledby="vppReturnImportLabel" aria-hidden="true">
    <form action="#" id="vpp_return_import" method="POST"> @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="vppReturnImportLabel">VPP Return - Excel Import</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>
                            <strong>Application Type <span class="text-danger">*</span>:</strong></label>
                        <select class="form-control custom-select" name="excel_import_app_type" required>
                            @if (Route::currentRouteName() == 'secure-card.lower-court')
                            <option value="1" selected>Lower Court</option>
                            @endif
                            @if (Route::currentRouteName() == 'secure-card.higher-court')
                            <option value="2" selected>High Court</option>
                            @endif
                            @if (Route::currentRouteName() == 'secure-card.renewal-higher-court')
                            <option value="3" selected>Renewal High Court</option>
                            @endif
                            @if (Route::currentRouteName() == 'secure-card.renewal-lower-court')
                            <option value="4" selected>Renewal Lower Court</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>Excel File <span class="text-danger">*</span>:</strong></label>
                        <input type="file" class="form-control custom-image-upload" name="excel_import_file" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary save_btn">Save changes</button>
                    <button class="btn btn-primary hidden loading_btn btn-sm" type="button" disabled><span
                            class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                        Loading...</button>
                </div>
            </div>
        </div>
    </form>
</div>
