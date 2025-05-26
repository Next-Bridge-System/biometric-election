@extends('layouts.app')
@section('content')
@include('frontend.includes.logo')
<section class="text-center mt-4">
    <h2 style="color: red">"Your session has expired. Please log in again to continue."</h2>
    <a href="{{url()->previous()}}" class="btn btn-secondary mt-3">Return Back</a>
</section>
@endsection