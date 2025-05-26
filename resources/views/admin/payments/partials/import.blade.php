<button type="button" class="btn btn-success float-right m-1" data-toggle="modal" data-target="#excelImportModal"><i
        class="fas fa-file-import mr-2"></i>Excel Import </button>
<div class="modal fade" id="excelImportModal" tabindex="-1" aria-labelledby="excelImportModalLabel" aria-hidden="true">
    <form action="#" id="payment_excel_import_form" method="POST"> @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="excelImportModalLabel">Payments - Excel Import</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>
                            <strong>Excel File <span class="text-danger">*</span>:</strong></label>
                        <input type="file" class="form-control custom-image-upload" name="payment_excel_import_file">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary save_btn">Save changes</button>
                    <button class="btn btn-primary hidden loading_btn btn-sm" type="button" disabled><span
                            class="spinner-grow spinner-grow-sm" role="status"
                            aria-hidden="true"></span>Loading...</button>
                </div>
            </div>
        </div>
    </form>
</div>
