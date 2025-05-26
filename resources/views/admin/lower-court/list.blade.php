@extends('layouts.admin')

@section('content')
<livewire:admin.lower-court.index-component :slug="$slug" />
@endsection