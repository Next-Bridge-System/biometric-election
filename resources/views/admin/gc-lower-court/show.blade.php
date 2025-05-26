@extends('layouts.admin')

@section('styles')
<link rel="stylesheet" href="{{URL::asset('public/admin/plugins/summernote/summernote-bs4.css')}}">
@endsection

@section('content')
<section class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Manage GC Lower Court</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">

                    @if (permission('move_gclc_to_gchc'))
                    <li class="breadcrumb-item mr-2">
                        <a href="javascript:void(0)" data-application_id="{{ $application->id }}" data-href="{{route('gc-lower-court.move-to-hc')}}" class="btn btn-secondary btn-sm" onclick="moveToHC(this)">
                            Move To HC</a>
                    </li>
                    @endif

                    <li class="breadcrumb-item mr-2">
                        <a href="{{route('gc-lower-court.edit',$application->id )}}" class="btn btn-dark btn-sm">
                            Edit</a>
                    </li>

                    @include('admin.gc-lower-court.partials.notes')

                    <li class="breadcrumb-item">
                        <a href="{{route('gc-lower-court.index')}}" class="btn btn-secondary btn-sm">
                            GC Lower Court List</a>
                    </li>
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
                        <h3 class="card-title">Advocate Lower Court Report</h3>
                    </div>
                    <div class="card-body">
                        <fieldset >
                            @include('admin.gc-lower-court.partials.show')
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
        $('input[type="select"], input[type="text"], textarea, select').prop('disabled', true);
        $('input[type="select"], input[type="text"], textarea, select').prop('readonly', true);

        // Replace with the actual ID of the input you want to enable
        $('input[name="profile_image"]').removeAttr('disabled');
        $('input[name="profile_image"]').removeAttr('readonly');


        App.init();
    });

    $('.textarea').summernote({
            height: 350,
        });

    $("#gclc_notes_form").on("submit", function(event){
            event.preventDefault();
            var gc_lower_court_id = '{{$application->id}}';
            var notes = $("#gclc_notes").val();
            $.ajax({
                method: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    'gc_lower_court_id': gc_lower_court_id,
                    'notes': notes,
                },
                url: '{{route('gc-lower-court.notes')}}',
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

    function moveToHC(ev){
        let application_id = ev.dataset.application_id;
        let href = ev.dataset.href;

        console.log(application_id,href);

        $.ajax({
            method: "POST",
            url: href,
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                'application_id': application_id,
            },
            beforeSend: function(){
                $(".custom-loader").removeClass('hidden');
            },
            success: function (response) {
                if (response.status == 1) {
                    alert(response.message);
                    window.location.reload();
                }else{
                    $(".custom-loader").addClass('hidden');
                    alert(response.message)
                }
            },
            error : function (errors) {
                alert('error')
                $(".custom-loader").removeClass('hidden');
            }
        });


    }

</script>
@endsection
