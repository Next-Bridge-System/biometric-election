@extends('layouts.admin')

@section('content')
<livewire:admin.users.index-component :slug="$slug" />
@endsection