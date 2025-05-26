<div class="col-md-12" style="margin-top: 75px">
    <h5 class="text-bold"><u>Match & Confirm Record with Serial Number</u></h5>
    <div class="form-group col-md-8">
        <input type="text" class="form-control" wire:model.defer='serial_number'>
        <button class="btn btn-primary mt-2" wire:click='searchBySerialNumber'>Search</button>
    </div>

    @if ($serial_number_record)
    <div>
        <div class="card p-3">
            <span><b>Lawyer Name:</b> {{$serial_number_record->lawyer_name}}</span>
            <span><b>Father Name:</b> {{$serial_number_record->father_name}}</span>
            <span><b>CNIC No:</b> {{$serial_number_record->cnic_no}}</span>

            @if ($user->register_as == 'gc_lc')
            <span><b>Serial No:</b> {{$serial_number_record->sr_no_lc}}</span>
            <span><b>License No:</b> {{$serial_number_record->license_no_lc}}</span>
            <span><b>Legder No:</b> {{$serial_number_record->reg_no_lc}}</span>
            @endif

            @if ($user->register_as == 'gc_hc')
            <span><b>Serial No:</b> {{$serial_number_record->sr_no_hc}}</span>
            <span><b>License No:</b> {{$serial_number_record->license_no_hc}}</span>
            <span><b>HCR No:</b> {{$serial_number_record->hcr_no_hc}}</span>
            @endif
        </div>
    </div>

    <div>
        <button class="btn btn-warning btn-sm" wire:click='replaceRecord'><b>Replace Record</b></button>
    </div>
    @endif
</div>