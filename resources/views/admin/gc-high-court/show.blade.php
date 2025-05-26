@extends('layouts.admin')

@section('styles')
<link rel="stylesheet" href="{{URL::asset('public/admin/plugins/summernote/summernote-bs4.css')}}">
@endsection

@section('content')
<section class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Manage GC High Court</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">

                    <li class="breadcrumb-item mr-2">
                        <a href="{{route('gc-high-court.edit',$application->id )}}" class="btn btn-dark btn-sm">
                            Edit</a>
                    </li>

                    @include('admin.gc-high-court.partials.notes')

                    <li class="breadcrumb-item"><a href="{{route('gc-high-court.index','all')}}"
                            class="btn btn-secondary btn-sm">High Court List</a></li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Advocate High Court Report</h3>
                    </div>
                    <div class="card-body">
                        <fieldset>
                            @include('admin.gc-high-court.partials.show')
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script src="{{asset('public/js/app.js')}}"></script>
<script src="{{URL::asset('public/admin/plugins/summernote/summernote-bs4.min.js')}}"></script>

<script>
    jQuery(document).ready(function () {

        App.init();

        $('input[type="select"], input[type="text"], textarea, select').prop('disabled', true);
        $('input[type="select"], input[type="text"], textarea, select').prop('readonly', true);

        // Replace with the actual ID of the input you want to enable
        $('input[name="profile_image"]').removeAttr('disabled');
        $('input[name="profile_image"]').removeAttr('readonly');

    });

    $('.textarea').summernote({
            height: 350,
        });

    $("#gchc_notes_form").on("submit", function(event){
            event.preventDefault();
            var gc_high_court_id = '{{$application->id}}';
            var notes = $("#gchc_notes").val();
            $.ajax({
                method: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    'gc_high_court_id': gc_high_court_id,
                    'notes': notes,
                },
                url: '{{route('gc-high-court.notes')}}',
                beforeSend: function(){
                    $(".custom-loader").removeClass('hidden');
                },
                success: function (response) {
                    $('#lcNotesModal').modal('toggle');
                    Swal.fire('Save Successfully!','Notes have been saved successfully.','success').then(function() {
                        $(".custom-loader").removeClass('hidden');
                        window.location.reload();
                    });
                },
                error : function (errors) {
                    $(".custom-loader").addClass('hidden');
                    $("#notes_error").remove();
                    $("#notes").append("<span class='text-danger text-capitalize' id='notes_error'>*"+errors.responseJSON.errors.notes+'</span>');
                }
            });

        });

        $(document).ready(function() {
            // Replace with your actual fieldset selector
            var fieldsetSelector = 'fieldset';

            // Target the specific input you want to enable
            var inputToEnable = $(fieldsetSelector).find('input[name="profile_image"]'); // or .children('#inputId')

            // Enable the input
            inputToEnable.removeAttr('disabled');
});

</script>
@endsection
