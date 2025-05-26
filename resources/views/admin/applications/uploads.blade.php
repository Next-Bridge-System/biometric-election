@extends('layouts.admin')

@section('content')

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
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Application Uploads</h3>
                    </div>
                    <form action="#" method="POST" id="upload_images" enctype="multipart/form-data"> @csrf
                        <div class="card-body">
                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto">Upload Images</legend>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label>Profile Images:</label>
                                        <input type="file" id="profile_images" name="profile_images"
                                            accept="image/jpg,image/jpeg,image/png">
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success float-right" value="save">Save &
                                Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
    var profileImages = FilePond.create(document.querySelector('input[id="profile_images"]'), {
        acceptedFileTypes: ['image/png' ,'image/jpeg','image/jpg'],
        required: false,
        allowMultiple: true,
        allowFileSizeValidation: true,
        maxFileSize:'5MB',
        allowRevert: true,
        fileValidateTypeDetectType: (source, type) => new Promise((resolve, reject) => {
            resolve(type);
        })
    });
    profileImages.setOptions({
      server: {
          url: '{{route('applications.upload-profile-images')}}',
          headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
      }
    });
</script>

@endsection
