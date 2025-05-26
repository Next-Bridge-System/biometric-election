<div>
    <div class="container mt-5 mb-5">
        <h3 class="text-center font-weight-bold">Lahore Bar Lawyers</h3>
        <div class="card p-4 shadow mt-4">
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="">CNIC NO:</label>
                    <input type="text" class="form-control" wire:model.defer="cnic_no" placeholder="xxxxx-xxxxxxx-x">
                    @error('cnic_no') <span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="">PHONE NO:</label>
                    <input type="text" class="form-control" wire:model.defer="phone_no" placeholder="xxxx-xxxxxxx">
                    @error('phone_no') <span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="form-group col-md-12">
                    <button wire:click='search' class="btn btn-success">Search</button>
                </div>
                <div class="col-md-12" wire:loading>
                    <div class="text-center">
                        <h5>Loading ... Please wait</h5>
                    </div>
                </div>
            </div>

            <div class="row">
                @if ($lawyer)

                <div class="col-md-4 text-uppercase">
                    <b>Lawer Name:</b> {{$lawyer->lawyer}} <br>
                    <b>CNIC No:</b> {{$lawyer->cnic}} <br>
                    <b>Father/Husband:</b> {{$lawyer->father_husband}} <br>
                    <b>App Type:</b> {{$lawyer->app_type}} <br>
                    <b>App Status:</b> {{$lawyer->app_status}} <br>
                    <b>Lawyer Type:</b> {{$lawyer->lawyer_type}} <br>
                </div>

                <div class="col-md-4">
                    @if (in_array($type, ['gc_lc','gc_hc']))
                    <span>
                        @if (isset($user) && $user->getFirstMedia('gc_profile_image'))
                        <img src="{{asset('storage/app/public/'.$user->getFirstMedia('gc_profile_image')->id.'/'.$user->getFirstMedia('gc_profile_image')->file_name)}}"
                            class="custom-image-preview" style="height:120px !important">
                        @endif
                    </span>
                    @endif

                    @if (in_array($type, ['lc','hc']))
                    <span>
                        <img src="{{asset('storage/app/public/'.$lawyer->profile_image)}}" class="custom-image-preview"
                            style="height:120px !important">
                    </span>
                    @endif
                </div>

                {{-- <div class="col-md-4">
                    <div>PAYMENT VOUCHER FOR DUPLICATE CARD</div>
                    <a href="{{route('frontend.lahore-bar-lawyers-voucher', $lawyer->cnic)}}"
                        class="btn btn-success btn-sm" target="_blank">
                        <i class="fas fa-print mr-1"></i>Print Voucher</a>
                </div> --}}
                @endif

                @if ($record_found == 2)
                <div class="col-md-12 bg-danger text-center p-1">
                    Record not found. Please contact the Punjab Bar Council for further assistance.
                </div>
                @endif

            </div>
        </div>

        <div>
            <a href="https://lahorebar.com" class="btn btn-primary btn-sm">BACK TO HOME</a>
        </div>
    </div>
</div>