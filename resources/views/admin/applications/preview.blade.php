@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{__('Secure Card Data')}}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('applications.index')}}" class="btn btn-dark">Back</a>
                    </li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- jquery validation -->

                <form action="#" id="final_submission_form" method="POST">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Preview Application</h3>
                            <button type="submit" class="btn btn-success btn-sm float-right" value="save">Final
                                Submission</button>
                            <a href="{{route('applications.print',$application->id)}}"
                                class="btn btn-primary btn-sm float-right mr-1" target="_blank"><i
                                    class="fas fa-print mr-1"></i>Print</a>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @include('admin.applications._application-detail')
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success btn-sm float-right" value="save">Final
                                Submission</button>
                            <a href="{{route('applications.print',$application->id)}}"
                                class="btn btn-primary btn-sm float-right mr-1" target="_blank"><i
                                    class="fas fa-print mr-1"></i>Print</a>
                        </div>
                    </div>
                </form>
                <!-- /.card -->
            </div>
            <!--/.col (left) -->
            <!-- right column -->
            <div class="col-md-6">

            </div>
            <!--/.col (right) -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

@endsection


@section('scripts')
<script>
    $(document).ready(function(){
      $("#final_submission_form").on("submit", function(event){
          event.preventDefault();
          $.ajax({
            method: "POST",
            url: '{{route('applications.final-submission')}}',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                'application_id': "{{$application->id}}",
            },
            beforeSend: function(){
                $(".custom-loader").removeClass('hidden');
            },
            success: function (response) {
                if (response.status == 1) {
                    window.location.href = '{{ route("applications.index") }}';
                }
            },
            error : function (errors) {
                alert('error')
            }
          });
      });
    });
</script>

@endsection
