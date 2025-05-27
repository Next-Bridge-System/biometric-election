@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Seats</h1>
                </div>
            </div>
        </div><!-- /.container -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Add Seat</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="{{ route('seats.store') }}" method="POST" enctype="multipart/form-data"> @csrf
                            <div class="card-body">
                                <fieldset class="border p-4 mb-4">
                                    <legend class="w-auto">Seat Information</legend>
                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label>Election <span class="required-star">*</span></label>
                                            <select class="form-control" name="election_id" required>
                                                <option value="">Select Election</option>
                                                @foreach ($elections as $election)
                                                    <option value="{{ $election->id }}">{{ $election->title_english }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-12 col-md-6">
                                            <label>Name English <span class="required-star">*</span></label>
                                            <input type="text" maxlength="255" class="form-control" name="name_english"
                                                value="{{ old('name_english') }}" required>
                                        </div>
                                        <div class="form-group col-12 col-md-6">
                                            <label>Name Urdu</label>
                                            <input type="text" maxlength="255" class="form-control" name="name_urdu"
                                                value="{{ old('name_urdu') }}">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>Image:</label>
                                            <input type="file" id="image" name="image" class="form-control"
                                                accept="image/jpg,image/jpeg,image/png">
                                            <img id="imagePreview" width="150" class="rounded mt-2 img-thumbnail"
                                                style="display: none;">
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success float-right">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
@endsection

@section('scripts')
    <script>
        function readURL(event, id) {
            let input = event.target;
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $(id).attr('src', e.target.result).show();
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        $(document).ready(function() {
            $('#image').on('change', function(event) {
                readURL(event, '#imagePreview');
            });
        });
    </script>
@endsection
