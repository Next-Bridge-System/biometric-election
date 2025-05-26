@extends('layouts.pdf')
@section('title') Lower Court Detail - {{$application->id}} @endsection
@section('styles')
<style>
    table,
    th,
    td {
        padding: 3px !important;
        font-size: 14px !important;
    }

    #lc-table td {
        border: 1px solid rgb(194, 189, 189) !important;
        text-align: left !important;
    }

    #lc-table th {
        border: 1px solid rgb(194, 189, 189) !important;
        text-align: left !important;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    fieldset legend {
        color: green;
        font-weight: 600;
    }

    fieldset.border {
        border: 2px solid #17af23 !important;
        border-radius: 5px !important;
    }

    .mb-4 {
        margin-bottom: 20px
    }

    .custom-image-preview {
        width: 100px;
        height: 100px
    }
</style>
@endsection
@section('content')
@include('pdf.partials.navbar')
@include('admin.lower-court.partials.application-section')
@include('admin.lower-court.partials.payment-section')
@endsection
