@extends('layouts.app')
@section('content')
@include('frontend.includes.logo')
<section class="text-center mt-4">
    <h2 style="color: red">The request could not be satisfied.</h2>
    <h5>You don't have permission to access this.</h5>
    <h4 style="margin-top:20px">{!!$exception->getMessage() !!}</h4>
    <a href="{{url()->previous()}}" class="btn btn-secondary mt-3">Return Back</a>
</section>
@endsection