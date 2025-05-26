@extends('layouts.frontend')

@section('content')
<section class="content">
    <div class="container">

        <div class="row mb-2">
            <div class="col-md-12 mb-4">
                <a href="{{route('frontend.high-court.create-step-1' , $application->id)}}"
                    class="btn btn-primary">Edit</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">High Court Application</h3>
                    </div>
                    <div class="card-body">
                        @include('admin.high-court.partials.application-section')
                        @include('admin.high-court.partials.payment-section')
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script src="{{asset('public/js/app.js')}}"></script>
<script>
    jQuery(document).ready(function () {
        App.init();
    });
</script>
@endsection