@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Elections</h1>
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
                            <h3 class="card-title">Edit Election</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="{{ route('elections.update', $election->id) }}" method="POST"> @csrf
                            <div class="card-body">
                                <fieldset class="border p-4 mb-4">
                                    <legend class="w-auto">Election Information</legend>
                                    <div class="row">
                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                            <label>Title English <span class="required-star">*</span></label>
                                            <input type="text" maxlength="255" class="form-control" name="title_english"
                                                value="{{ $election->title_english }}" required>
                                        </div>
                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                            <label>Title Urdu</label>
                                            <input type="text" maxlength="255" class="form-control" name="title_urdu"
                                                value="{{ $election->title_urdu }}">
                                        </div>
                                        <div class="form-group col-12">
                                            <label>Status <span class="required-star">*</span></label>
                                            <select class="form-control" name="status" required>
                                                <option value="">Select Status</option>
                                                <option value="1" {{ $election->status == 1 ? 'selected' : '' }}>Active
                                                </option>
                                                <option value="0" {{ $election->status == 0 ? 'selected' : '' }}>
                                                    Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success float-right">Update</button>
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
