<div>
    <section class="content">
        <div class="container-fluid">
            <div class="row"></div>
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-success">
                        <div class="card-header" wire:ignore>
                            <h3 class="card-title float-left">Existing Lawyer Data Verification</h3>
                        </div>
                        <div class="card-body">
                            @if (permission('delete-users'))
                            <div class="row">
                                @if ($user->gc_status== 'approved')
                                <div class="col">
                                    <div class="alert alert-success text-center">
                                        <i class="fas fa-check mr-2"></i>The record have been approved &
                                        verified.
                                    </div>
                                </div>
                                @endif

                                <div class="col">
                                    <a href="{{route('users.index','gc')}}"
                                        class="btn btn-dark btn-sm float-right">Back</a>
                                    <button onclick="confirmation('delete-user','{{$user->id}}')"
                                        class="btn btn-danger btn-sm mr-1 float-right">
                                        <i class="fa fa-remove mr-1"></i>Permanently Delete User</button>
                                </div>
                            </div>
                            @endif
                            <fieldset class="border p-4 mb-4" {{$user->gc_status == 'pending' ? '' : 'disabled'}}>
                                <legend class="w-auto">Requested Data</legend>
                                <div class="row">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td style="width:50%">

                                                    @if ($user->cnic_no != $application->cnic_no)
                                                    <span class="badge badge-danger"><i
                                                            class="fas fa-exclamation-circle mr-1 mr-1"></i>The
                                                        requested
                                                        cnic no is not match
                                                        with the existing data.</span>
                                                    @endif

                                                    @if ($user->phone != $application->contact_no)
                                                    <span class="badge badge-danger"><i
                                                            class="fas fa-exclamation-circle mr-1 mr-1"></i>The
                                                        requested
                                                        phone no is not match
                                                        with the existing data.</span>
                                                    @endif

                                                    <span class="badge badge-primary mb-2"><i
                                                            class="fas fa-exclamation-circle mr-1 mr-1"></i>You
                                                        will not
                                                        be able to
                                                        change
                                                        the record once its
                                                        Approved/Disapproved.</span>

                                                    <div class="form-group">
                                                        <label>Verification Status <span
                                                                class="text-danger">*</span></label>
                                                        <select wire:model="gc_status"
                                                            class="form-control custom-select">
                                                            <option value="pending">Pending</option>
                                                            <option value="approved">Approved</option>
                                                            <option value="disapproved">Disapproved</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Update CNIC <span class="text-danger">*</span></label>
                                                        <small>Note: Enter CNIC with dashes e.g
                                                            (12345-1234567-1)</small>
                                                        <input type="text" wire:model.defer="cnic_no"
                                                            class="form-control"
                                                            {{$gc_status == 'approved' ? '' :'disabled'}}>

                                                        @error('cnic_no') <span
                                                            class="text-danger">{{ $message }}</span>@enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Update Mobile <span class="text-danger">*</span></label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend" wire:ignore>
                                                                <span class="input-group-text"><img
                                                                        src="{{asset('public/admin/images/pakistan.png')}}"
                                                                        alt=""></span>
                                                                <span class="input-group-text">+92</span>
                                                            </div>
                                                            <input type="number" wire:model.defer="phone"
                                                                class="form-control"
                                                                {{$gc_status == 'approved' ? '' :'disabled'}}>
                                                        </div>

                                                        @error('phone') <span
                                                            class="text-danger">{{ $message }}</span>@enderror
                                                    </div>

                                                    @if ($user->gc_status == 'pending')
                                                    <div class="form-group">
                                                        <button type="button" wire:click="updateGcRequestData"
                                                            class="btn btn-success btn-sm float-right">Yes,
                                                            Confirm</button>

                                                    </div>

                                                    @include('livewire.admin.users.partials.search-serial-number')
                                                    @endif
                                                </td>
                                                <td style="width:50%">
                                                    <div class="row" wire:ignore>
                                                        @if ($user->getFirstMedia('gc_cnic_front'))
                                                        <div class="col-md-6 form-group">
                                                            <label for="">CNIC Front:</label>
                                                            <div class="">
                                                                <img src="{{asset('storage/app/public/'.$user->getFirstMedia('gc_cnic_front')->id.'/'.$user->getFirstMedia('gc_cnic_front')->file_name)}}"
                                                                    class="custom-image-preview"
                                                                    style="height:120px !important">
                                                            </div>
                                                        </div>
                                                        @endif

                                                        @if ($user->getFirstMedia('gc_cnic_back'))
                                                        <div class="col-md-6 form-group">
                                                            <label for="">CNIC Back:</label>
                                                            <div>
                                                                <img src="{{asset('storage/app/public/'.$user->getFirstMedia('gc_cnic_back')->id.'/'.$user->getFirstMedia('gc_cnic_back')->file_name)}}"
                                                                    class="custom-image-preview"
                                                                    style="height:120px !important">
                                                            </div>
                                                        </div>
                                                        @endif

                                                        @if ($user->getFirstMedia('gc_license_front'))
                                                        <div class="col-md-6 form-group">
                                                            <label for="">License Front:</label>
                                                            <div>
                                                                <img src="{{asset('storage/app/public/'.$user->getFirstMedia('gc_license_front')->id.'/'.$user->getFirstMedia('gc_license_front')->file_name)}}"
                                                                    class="custom-image-preview"
                                                                    style="height:120px !important">
                                                            </div>
                                                        </div>
                                                        @endif

                                                        @if ($user->getFirstMedia('gc_license_back'))
                                                        <div class="col-md-6 form-group">
                                                            <label for="">License Back:</label>
                                                            <div>
                                                                <img src="{{asset('storage/app/public/'.$user->getFirstMedia('gc_license_back')->id.'/'.$user->getFirstMedia('gc_license_back')->file_name)}}"
                                                                    class="custom-image-preview"
                                                                    style="height:120px !important">
                                                            </div>
                                                        </div>
                                                        @endif

                                                        @if ($user->getFirstMedia('gc_profile_image'))
                                                        <div class="col-md-6 form-group">
                                                            <label for="">Profile Image:</label>
                                                            <div class="">
                                                                <img src="{{asset('storage/app/public/'.$user->getFirstMedia('gc_profile_image')->id.'/'.$user->getFirstMedia('gc_profile_image')->file_name)}}"
                                                                    class="custom-image-preview"
                                                                    style="height:120px !important">
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">Data Tracking</h3>
                        </div>
                        <div class="card-body" style="overflow: scroll; height:500px">
                            @foreach ($admin_audits as $audit)
                            @if ($audit)
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm">
                                    <tr>
                                        <td>
                                            @foreach ($audit->new_values as $key => $value)
                                            @if (!in_array($key,['password','access_token','id']))
                                            <span class="text-capitalize text-bold">{{clean($key)}}</span>:
                                            <span class="text-capitalize">{{$value ?? 'N-a'}}</span>
                                            <br>
                                            @endif
                                            @endforeach
                                            <hr>
                                            Admins: {{isset($audit->user->name) ? $audit->user->name : 'N-A'}} <br>
                                            Date: {{$audit->created_at->format('F d, Y | h:i A')}}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            @if ($user->register_as == 'gc_lc')
                            <fieldset id="f-set-disabled">
                                @include('admin.gc-lower-court.partials.show')
                            </fieldset>
                            @endif

                            @if ($user->register_as == 'gc_hc')
                            <fieldset id="f-set-disabled">
                                @include('admin.gc-high-court.partials.show')
                            </fieldset>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('livewire.loader')
</div>
