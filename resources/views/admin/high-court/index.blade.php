@extends('layouts.admin')

@section('content')
<livewire:admin.high-court.index-component :slug="$slug" />
@endsection