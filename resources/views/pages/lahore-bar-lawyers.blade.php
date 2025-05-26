@extends('layouts.frontend')

@section('content')
<livewire:frontend.lahore-bar-component :cnic_no="$cnic_no" :phone_no="$phone_no" />
@endsection