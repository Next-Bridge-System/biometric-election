@extends('layouts.admin')

@section('styles')
<link rel="stylesheet" href="{{URL::asset('public/admin/plugins/summernote/summernote-bs4.css')}}">
<script src="{{ asset('public/admin/plugins/imask/imask.js') }}"></script>
@endsection

@section('content')
<livewire:admin.complaint.show-component :complaint_id="$complaint_id" />
@endsection

@section('scripts')
<script src="{{URL::asset('public/admin/plugins/summernote/summernote-bs4.min.js')}}"></script>
@endsection