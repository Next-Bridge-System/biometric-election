@extends('layouts.app')
@section('content')

@include('frontend.includes.logo')
<section class="text-center mt-4">
    <h2 style="color: red">The request could not be satisfied.</h2>
    <h5>You don't have permission to access this. <br> Try again later, or contact the support.</h5>
    <a href="{{url()->previous()}}" class="btn btn-dark mt-2">Return Back</a>
</section>
@endsection