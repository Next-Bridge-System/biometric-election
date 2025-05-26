@extends('layouts.admin')

@section('content')
<livewire:admin.members.index-component />
@endsection

@section('scripts')
<script>
    var table;
    $(document).ready(function () {
        table = $('#datatable').DataTable({
            "responsive": true,
            "paginate": false,
            "info": false,
            "searching": false,
        });
    });
</script>
@endsection