<button type="button" class="btn btn-{{$application->plj_br == 1 ? 'success': 'primary'}} btn-sm float-right m-1"
    data-toggle="modal" data-target="#plj_br_modal">
    <i class="fas fa-{{$application->plj_br == 1 ? 'check': 'users'}} mr-1"></i>PLJ Blood Relation
</button>

<div class="modal fade" id="plj_br_modal" tabindex="-1" aria-labelledby="plj_br_modal_label" aria-hidden="true">
    <form action="#" id="plj_br_form" method="POST"> @csrf
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="plj_br_modal_label">PLJ Blood Relation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="form-group">
                            <label for="">Blood Relation Type <span class="text-danger">*</span></label> <small>e.g
                                (Son,
                                Daughter, Husband, Wife
                                etc.)</small>
                            <input type="text" name="plj_br_type" id="plj_br_type" class="form-control"
                                value="{{$application->plj_br_type}}" required>
                        </div>
                        <div class="form-group">
                            <label for="">Blood Relation CNIC <span class="text-danger">*</span></label>
                            @if (isset($application->uploads->lc_plj_br_cnic))
                            <img src="{{asset('storage/app/public/'.$application->uploads->lc_plj_br_cnic)}}" alt=""
                                class="w-100">
                            <a href="{{route('lower-court.destroy.file','lc_plj_br_cnic')}}"
                                class="btn btn-danger btn-sm float-right col-md-12 mt-2"
                                onclick="return confirm('Are you sure you want to delete it. This action cannot be revert.')">Remove</a>

                            @else
                            <input type="file" id="lc_plj_br_cnic" name="lc_plj_br_cnic"
                                accept="image/jpg,image/jpeg,image/png">
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="">Blood Relation License <span class="text-danger">*</span></label>
                            @if (isset($application->uploads->lc_plj_br_license))
                            <img src="{{asset('storage/app/public/'.$application->uploads->lc_plj_br_license)}}" alt=""
                                class="w-100">
                            <a href="{{route('lower-court.destroy.file','lc_plj_br_license')}}"
                                class="btn btn-danger btn-sm float-right col-md-12 mt-2"
                                onclick="return confirm('Are you sure you want to delete it. This action cannot be revert.')">Remove</a>

                            @else
                            <input type="file" id="lc_plj_br_license" name="lc_plj_br_license"
                                accept="image/jpg,image/jpeg,image/png">
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    @if ($application->plj_br == 1)
                    <button type="button" class="btn btn-danger btn-sm" id="remove_plj_br">Remove Blood
                        Relation</button>
                    @endif
                    <button type="submit" class="btn btn-primary btn-sm">Save & Update</button>
                </div>
            </div>
        </div>
    </form>
</div>
