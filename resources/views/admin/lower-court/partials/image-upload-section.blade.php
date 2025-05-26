<fieldset class="border p-4 mb-4">
    <legend class="w-auto">Identification</legend>

    <div class="row mb-3">
        <b class="text-danger">Important Note: The profile picture must be passport size (600x600 pixels) with
            a white background. The lawyer should wear a uniform with a black tie. If the profile image does not
            meet these conditions, the application will be rejected during processing.</b>
    </div>

    <div class="row">
        @component('components.image-upload-lc')
        @slot('label') Profile Image @endslot
        @slot('name') profile_image @endslot
        @slot('condition') {{$application->uploads->profile_image ?? null}} @endslot
        @endcomponent

        @component('components.image-upload-lc')
        @slot('label') CNIC Front @endslot
        @slot('name') cnic_front @endslot
        @slot('condition') {{$application->uploads->cnic_front ?? null}} @endslot
        @endcomponent

        @component('components.image-upload-lc')
        @slot('label') CNIC Back @endslot
        @slot('name') cnic_back @endslot
        @slot('condition') {{$application->uploads->cnic_back ?? null}} @endslot
        @endcomponent

    </div>
</fieldset>
<fieldset class="border p-4 mb-4">
    <legend class="w-auto">Senior Lawyer</legend>
    <div class="row">

        @component('components.image-upload-lc')
        @slot('label') Srl Cnic Front @endslot
        @slot('name') srl_cnic_front @endslot
        @slot('condition') {{$application->uploads->srl_cnic_front ?? null}} @endslot
        @endcomponent

        @component('components.image-upload-lc')
        @slot('label') Srl Cnic Back @endslot
        @slot('name') srl_cnic_back @endslot
        @slot('condition') {{$application->uploads->srl_cnic_back ?? null}} @endslot
        @endcomponent

        @component('components.image-upload-lc')
        @slot('label') Srl License Front @endslot
        @slot('name') srl_license_front @endslot
        @slot('condition') {{$application->uploads->srl_license_front ?? null}} @endslot
        @endcomponent

        @component('components.image-upload-lc')
        @slot('label') Srl License Back @endslot
        @slot('name') srl_license_back @endslot
        @slot('condition') {{$application->uploads->srl_license_back ?? null}} @endslot
        @endcomponent

    </div>
</fieldset>
<fieldset class="border p-4 mb-4">
    <legend class="w-auto">Lower Court</legend>
    <div class="row">

        @component('components.image-upload-lc')
        @slot('label') Character Certificate From 1st Lawyer @endslot
        @slot('name') certificate_lc @endslot
        @slot('condition') {{$application->uploads->certificate_lc ?? null}} @endslot
        @endcomponent

        @component('components.image-upload-lc')
        @slot('label') Character Certificate From 2nd Lawyer @endslot
        @slot('name') certificate2_lc @endslot
        @slot('condition') {{$application->uploads->certificate2_lc ?? null}} @endslot
        @endcomponent

        @if ($application->is_exemption == 0)
        @component('components.image-upload-lc')
        @slot('label') List of 20 Cases Signed By Senior @endslot
        {{-- @slot('label') Legal Exception/LLB Degree @endslot --}}
        @slot('name') cases_lc @endslot
        @slot('condition') {{$application->uploads->cases_lc ?? null}} @endslot
        @endcomponent
        @endif

        @component('components.image-upload-lc')
        @slot('label') Original Provisional Certificate/Degree @endslot
        @slot('name') org_prov_certificate_lc @endslot
        @slot('condition') {{$application->uploads->org_prov_certificate_lc ?? null}} @endslot
        @endcomponent

        @component('components.image-upload-lc')
        @slot('label') Undertaking LC @endslot
        @slot('name') undertaking_lc @endslot
        @slot('condition') {{$application->uploads->undertaking_lc ?? null}} @endslot
        @endcomponent

        @component('components.image-upload-lc')
        @slot('label') Affidavit LC @endslot
        @slot('name') affidavit_lc @endslot
        @slot('condition') {{$application->uploads->affidavit_lc ?? null}} @endslot
        @endcomponent

        @if($application->is_exemption == 1 && $application->exemption_reason == 2)
        @component('components.image-upload-lc')
        @slot('label') Legal Experience Certificate @endslot
        @slot('name') practice_certificate @endslot
        @slot('condition') {{$application->uploads->practice_certificate ?? null}} @endslot
        @endcomponent
        @endif

        @if($application->is_exemption == 1 && $application->exemption_reason == 3)
        @component('components.image-upload-lc')
        @slot('label') Judge Certificate @endslot
        @slot('name') judge_certificate @endslot
        @slot('condition') {{$application->uploads->judge_certificate ?? null}} @endslot
        @endcomponent
        @endif

        @component('components.image-upload-lc')
        @slot('label') BVC Certificate LC @endslot
        @slot('name') bvc_lc @endslot
        @slot('condition') {{$application->uploads->bvc_lc ?? null}} @endslot
        @endcomponent


    </div>
</fieldset>