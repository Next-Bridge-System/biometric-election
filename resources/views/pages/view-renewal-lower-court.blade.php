@extends('layouts.frontend')

@section('content')
@include('frontend.includes.slider')
@include('frontend.includes.buttons')

<div class="d-flex justify-content-center">
    <h4 class="text-center">Renewal Lower Court</h4>
</div>

<div class="container mt-2 mb-4">

    <div class="d-flex justify-content-center">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Thankyou {{$application->advocates_name}}!</strong> Your application has been submitted
            successfully.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>

    @include('admin.applications._application-detail')

</div>
@endsection
