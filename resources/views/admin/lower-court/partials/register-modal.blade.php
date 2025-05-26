<button type="button" class="btn btn-success float-right m-1 hidden lower-court" data-toggle="modal"
    data-target="#lower_court_modal"><i class="fas fa-plus mr-1" aria-hidden="true"></i>New Application
</button>
<div class="modal fade" id="lower_court_modal" tabindex="-1" aria-labelledby="lower_court_modal" aria-hidden="true">
    <form action="javascript:void(0)" id="userRegistrationForm" data-target="{{ route('lower-court.registerUser') }}"
        enctype="multipart/form-data">
        @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="#">Lower Court Application</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-sm-6 col-md-6">
                            <label>First Name:</label>
                            <input type="text" class="form-control" name="fname" value="">
                        </div>
                        <div class="form-group col-sm-6 col-md-6">
                            <label>Last Name:</label>
                            <input type="text" class="form-control" name="lname" value="">
                        </div>
                        <div class="form-group col-sm-12 col-md-12">
                            <label>Email:</label>
                            <input type="email" class="form-control" name="email" value="">
                        </div>
                        <div class="form-group col-sm-12 col-md-12">
                            <label>Active Mobile No:</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><img
                                            src="{{asset('public/admin/images/pakistan.png')}}" alt=""></span>
                                    <span class="input-group-text">+92</span>
                                </div>
                                <input type="tel" class="form-control" name="phone">
                            </div>
                        </div>
                        <div class="form-group col-sm-12 col-md-12">
                            <label>C.N.I.C. No <span class="text-danger">*</span>:</label>
                            <input type="text" class="form-control" name="cnic_no">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-success save_btn">Save & Next</button>
                    <button type="button" disabled class="btn btn-sm btn-success save_btn_loader"
                        style="display: none"><span class="spinner-grow spinner-grow-sm" role="status"
                            aria-hidden="true"></span>
                        Loading...</button>
                </div>
            </div>
        </div>
    </form>
</div>
