@extends('layouts.admin')

@section('content')
<livewire:admin.lawyer-request.show-component :lawyer_request_id="$lawyer_request_id" />
@endsection