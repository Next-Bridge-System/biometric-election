@extends('layouts.frontend')

@section('content')
@include('frontend.includes.slider')
@include('frontend.includes.buttons')

<div class="d-flex justify-content-center">
    <h4 class="text-center">Search Applications <br> <small>By Application No. / CNIC No. <small>(
                xxxx-xxxxxxx-x)</small></small> <br> </h4>
</div>
<div class="d-flex justify-content-center">
    <form action="{{route('frontend.search-application')}}" id="search_application_form" method="POST">
        @csrf
        <div class="row">
            <input type="text" name="search_application" id="search_application" class="form-control">
            <button type="submit" class="btn btn-success btn-sm mt-2">Search</button>
        </div>
    </form>
</div>

<div class="col-md-12">
    @if (isset($application))
    <div class="mt-4 mb-5">
        {{-- <div class="text-center">
            @if (isset($application->profile_image_url))
            <img src="{{asset('storage/app/public/'.$application->profile_image_url)}}" class="custom-image-preview"
                alt="">
            @endif
        </div> --}}
        <div class="text-center">
            <b>Application No :</b> <span>{{$application->application_token_no}}</span> <br>
            <b>Advocate Name:</b> <span>{{getLawyerName($application->id)}}</span> <br>
            <b>S/o, D/o, W/o:</b> <span>{{$application->so_of}}</span> <br>
            {{-- <b>Cnic no:</b> <span>{{$application->cnic_no}}</span> <br> --}}
            <b>Application Type:</b> <span>{{getApplicationType($application->id)}}</span> <br>
            <b>Application Status:</b> <span>{{getApplicationStatus($application->id)}}</span> <br>
            @if ($application->application_type != 6)
            <b>Card Status:</b> <span>{{getCardStatus($application->id)}}</span> <br>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection
