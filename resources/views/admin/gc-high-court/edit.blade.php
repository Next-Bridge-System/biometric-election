@extends('layouts.admin')

@section('styles')
<link rel="stylesheet" href="{{URL::asset('public/admin/plugins/summernote/summernote-bs4.css')}}">
@endsection

@section('content')
<section class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>GC High Court - Edit</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ str_contains(url()->previous(),'gc-high-court/show') ? url()->previous() : route('gc-high-court.index')}}" class="btn          btn-dark">Back</a>
                        </li>
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
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">Advocate High Court Report</h3>
                    </div>
                    <div class="card-body">
                        <form action="#" id="update_gc_high_court_form" method="POST"> @csrf
                            <fieldset>
                                @include('admin.gc-high-court.partials.show')
                            </fieldset>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary float-right">Save & Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script src="{{asset('public/js/app.js')}}"></script>
<script>
    jQuery(document).ready(function () {
        App.init();
    });

    $(document).ready(function(){
      $("#update_gc_high_court_form").on("submit", function(event){
          event.preventDefault();
          $('span.text-success').remove();
          $('span.invalid-feedback').remove();
          $('input.is-invalid').removeClass('is-invalid');
          var formData = new FormData(this);
          $.ajax({
            method: "POST",
            data: formData,
            url: '{{route('gc-high-court.update',$application->id)}}',
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function(){
                $(".custom-loader").removeClass('hidden');
            },
            success: function (response) {
                if (response.status == 1) {
                    window.location.href = '{{route('gc-high-court.show',$application->id)}}';
                }
            },
            error : function (errors) {
                errorsGet(errors.responseJSON.errors)
                $(".custom-loader").addClass('hidden');
                $("#error_message").removeClass('hidden');
            }
          });
      });
    });
</script>

<script>
    $('#date_of_birth').datetimepicker({
        size: 'large',
        format: 'DD-MM-YYYY',
    });

    $('#enr_date_hc').datetimepicker({
        size: 'large',
        format: 'DD-MM-YYYY',
    });

    $('#enr_date_lc').datetimepicker({
        size: 'large',
        format: 'DD-MM-YYYY',
    });
</script>
@endsection
