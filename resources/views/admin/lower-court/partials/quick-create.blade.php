@if (permission('lc_quick_create'))
<style>
    label {
        text-transform: uppercase;
    }
</style>
<button type="button" class="btn btn-primary float-right m-1" data-toggle="modal" data-target="#lcQuickCreateModal">
    <i class="fas fa-plus mr-1" aria-hidden="true"></i>Quick Create
</button>

<div class="modal fade" id="lcQuickCreateModal" tabindex="-1" aria-labelledby="lcQuickCreateModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="#" method="POST" id="lc_quick_create_form" enctype="multipart/form-data"> @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="lcQuickCreateModalLabel">Quick Create Lower Court Application</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="">Sr.No:</label>
                            <input type="number" name="sr_no" id="sr_no" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Name:</label>
                            <input type="text" name="name" id="name" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Father Name:</label>
                            <input type="text" name="father_name" id="father_name" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Gender:</label>
                            <select class="form-control custom-select" name="gender" id="gender" required>
                                <option disabled="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Date of Birth:</label>
                            <input type="date" name="date_of_birth" id="date_of_birth" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">CNIC No:</label>
                            <input type="text" name="cnic_no" id="cnic_no" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Ledger No:</label>
                            <input type="text" name="ledger_no" id="ledger_no" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">License No:</label>
                            <input type="text" name="license_no" id="license_no" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">BF No:</label>
                            <input type="text" name="bf_no" id="bf_no" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Enr.Date:</label>
                            <input type="date" name="enr_date" id="enr_date" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Voter Member:</label>
                            <input type="text" name="voter_member" id="voter_member" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Enr. App SDW:</label>
                            <input type="text" name="enr_app_sdw" id="enr_app_sdw" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Enr. Status Reason:</label>
                            <input type="text" name="enr_status_reason" id="enr_status_reason" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Enr. PLJ Check:</label>
                            <input type="text" name="enr_plj_check" id="enr_plj_check" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Enr. GI Check:</label>
                            <input type="text" name="enr_gi_check" id="enr_gi_check" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Email:</label>
                            <input type="email" name="email" id="email" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Contact No:</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><img
                                            src="{{asset('public/admin/images/pakistan.png')}}" alt=""></span>
                                    <span class="input-group-text">+92</span>
                                </div>
                                <input type="tel" class="form-control" name="phone" id="phone">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Address 1:</label>
                            <input type="text" name="address_1" id="address_1" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Address 2:</label>
                            <input type="text" name="address_2" id="address_2" class="form-control">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="">Profile Image:</label>
                            <input type="file" name="profile_image" id="profile_image"
                                class="form-control custom-image-upload">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save & Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif
