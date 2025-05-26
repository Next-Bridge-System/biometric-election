<fieldset class="border p-4 mb-4">
    <legend class="w-auto">Identification</legend>
    <div class="row mb-3">
        <b class="text-danger">Important Note: The profile picture must be passport size (600x600 pixels) with
            a white background. The lawyer should wear a uniform with a black tie. If the profile image does not
            meet these conditions, the application will be rejected during processing.</b>
    </div>
    <div class="row">

        @if (permission('edit-high-court'))
        @component('components.image-upload-hc')
        @slot('label') Profile Image @endslot
        @slot('name') profile_image @endslot
        @slot('condition') {{$application->uploads->profile_image ?? null}} @endslot
        @endcomponent
        @else
        @if (isset($application->uploads->profile_image))
        <div class="col-md-4">
            <label for="">Profile Image : <span class="text-danger">*</span></label>
            <br>
            <img src="{{asset('storage/app/public/'.$application->uploads->profile_image )}}"
                style="width:100%;height:auto" class="custom-image-preview" alt="No Image Found">
        </div>

        @else
        <div class="col-md-4">
            <label for="">Profile Image : <span class="text-danger">*</span></label>
            <br>
            <img src="{{asset('storage/app/public/' )}}" style="width:150px;height:auto" class="custom-image-preview"
                alt="No Image Found">
        </div>

        @endif
        @endif

        @component('components.image-upload-hc')
        @slot('label') CNIC Front @endslot
        @slot('name') cnic_front @endslot
        @slot('condition') {{$application->uploads->cnic_front ?? null}} @endslot
        @endcomponent

        @component('components.image-upload-hc')
        @slot('label') CNIC Back @endslot
        @slot('name') cnic_back @endslot
        @slot('condition') {{$application->uploads->cnic_back ?? null}} @endslot
        @endcomponent

    </div>
</fieldset>

<fieldset class="border p-4 mb-4">
    <legend class="w-auto">Lower Court</legend>
    <div class="row">

        @component('components.image-upload-hc')
        @slot('label') Lower Court Card Front @endslot
        @slot('name') lc_card_front @endslot
        @slot('condition') {{$application->uploads->lc_card_front ?? null}} @endslot
        @endcomponent

        @component('components.image-upload-hc')
        @slot('label') Lower Court Card Back @endslot
        @slot('name') lc_card_back @endslot
        @slot('condition') {{$application->uploads->lc_card_back ?? null}} @endslot
        @endcomponent

        @component('components.image-upload-hc')
        @slot('label') Laywer 1 Fitness Certificate @endslot
        @slot('name') certificate_hc @endslot
        @slot('condition') {{$application->uploads->certificate_hc ?? null}} @endslot
        @endcomponent

        @component('components.image-upload-hc')
        @slot('label') Laywer 2 Fitness Certificate @endslot
        @slot('name') certificate2_hc @endslot
        @slot('condition') {{$application->uploads->certificate2_hc ?? null}} @endslot
        @endcomponent


        @component('components.image-upload-hc')
        @slot('label') Affidavit HC @endslot
        @slot('name') affidavit_hc @endslot
        @slot('condition') {{$application->uploads->affidavit_hc ?? null}} @endslot
        @endcomponent

        @component('components.image-upload-hc')
        @slot('label') 20 Case List Lower Court @endslot
        @slot('name') cases_lc @endslot
        @slot('condition') {{$application->uploads->cases_lc ?? null}} @endslot
        @endcomponent

    </div>
</fieldset>