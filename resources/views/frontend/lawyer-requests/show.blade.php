@extends('layouts.frontend')

@section('content')
<livewire:frontend.lawyer-requests.show-component :lawyer_request_id="$id" />
@endsection