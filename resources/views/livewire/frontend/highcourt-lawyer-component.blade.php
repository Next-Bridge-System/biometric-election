<div>
    <div class="container mt-5 mb-5">
        <h3 class="text-center font-weight-bold">High Court Lawyers</h3>
        <div class="card p-4 shadow mt-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-center">
                        <img src="{{asset('public/admin/images/rfid-card-scan.png')}}" alt="" style="width: 150px;">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    <label for="">CNIC NO:</label>
                    <input type="text" class="form-control form-control-lg" wire:model="cnic_no"
                        placeholder="xxxxx-xxxxxxx-x" autofocus autocomplete="off">
                    @error('cnic_no') <span class="text-danger">{{ $message }}</span>@enderror
                </div>
                {{-- <div class="form-group col-md-12">
                    <button wire:click='search' class="btn btn-success">Search</button>
                </div> --}}
                <div class="col-md-12" wire:loading>
                    <div class="text-center">
                        <h5>Loading ... Please wait</h5>
                    </div>
                </div>
            </div>

            <div class="row" style="border: 1px solid #a1a1a1; padding: 20px; border-radius: 7px;">
                @if (isset($lawyer->cnic))

                <div class="col-md-12">
                    <div class="text-center">
                        @if (in_array($type, ['gc_hc']))
                        <span>
                            @if (isset($user) && $user->getFirstMedia('gc_profile_image'))
                            <img src="{{asset('storage/app/public/'.$user->getFirstMedia('gc_profile_image')->id.'/'.$user->getFirstMedia('gc_profile_image')->file_name)}}"
                                class="custom-image-preview" style="height:120px !important">
                            @endif
                        </span>
                        @endif

                        @if (in_array($type, ['hc']))
                        <span>
                            <img src="{{asset('storage/app/public/'.$lawyer->profile_image)}}"
                                class="custom-image-preview" style="height:120px !important">
                        </span>
                        @endif
                    </div>

                    <div class="text-center text-uppercase">
                        <b>Lawer Name:</b> {{$lawyer->lawyer}} <br>
                        <b>CNIC No:</b> {{$lawyer->cnic}} <br>
                        <b>Father/Husband:</b> {{$lawyer->father_husband}} <br>
                        <b>App Type:</b> {{$lawyer->app_type}} <br>
                        <b>App Status:</b> {{$lawyer->app_status}} <br>
                        <b>Lawyer Type:</b> {{$lawyer->lawyer_type}} <br>
                    </div>
                </div>

                @endif

                @if ($record_found == 2)
                <div class="col-md-12 bg-danger text-center p-1">
                    Record not found. Please contact the Punjab Bar Council for further assistance.
                </div>
                @endif

            </div>
        </div>

    </div>
</div>