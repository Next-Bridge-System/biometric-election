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
                            <h3 class="card-title">Edit Seat</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="{{ route('seats.update', $seat->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <fieldset class="border p-4 mb-4">
                                    <legend class="w-auto">Seat Information</legend>
                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label>Election <span class="required-star">*</span></label>
                                            <select class="form-control" name="election_id" required>
                                                <option value="">Select Election</option>
                                                @foreach ($elections as $election)
                                                    <option value="{{ $election->id }}"
                                                        {{ $seat->election_id == $election->id ? 'selected' : '' }}>
                                                        {{ $election->title_english }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-12 col-md-6">
                                            <label>Name English <span class="required-star">*</span></label>
                                            <input type="text" maxlength="255" class="form-control" name="name_english"
                                                value="{{ $seat->name_english }}" required>
                                        </div>
                                        <div class="form-group col-12 col-md-6">
                                            <label>Name Urdu</label>
                                            <input type="text" maxlength="255" class="form-control" name="name_urdu"
                                                value="{{ $seat->name_urdu }}">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>Image:</label>
                                            <input type="file" id="image" name="image" class="form-control"
                                                accept="image/jpg,image/jpeg,image/png">
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
