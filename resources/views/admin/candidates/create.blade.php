@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Candidates</h1>
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
                            <h3 class="card-title">Add Candidate</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="{{ route('candidates.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <fieldset class="border p-4 mb-4">
                                    <legend class="w-auto">Candidate Information</legend>
                                    <div class="row">
                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                            <label>Election <span class="required-star">*</span></label>
                                            <select class="form-control election_id" name="election_id" required>
                                                <option value="">-- Choose --</option>
                                                @foreach ($elections as $election)
                                                    <option value="{{ $election->id }}">{{ $election->title_english }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                            <label>Seat</label>
                                            <select class="form-control form-select seat_id" name="seat_id"
                                                {{ old('seat_id') != null ? 'data-id="' . old('seat_id') . '"' : 'disabled' }}
                                                required>
                                                <option value="">-- Choose --</option>

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
                                            <img src="{{ old('image') }}" alt="">
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
    <script src="{{ asset('public/js/app.js') }}"></script>
    <script>
        $('.election_id').on('change', function() {
            let election_id = $(this).find(":selected").val();
            $.ajax({
                method: "POST",
                url: '{{ route('seats.getByElection') }}',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    'election_id': election_id
                },
                success: function(response) {
                    appendSelectOption($('.seat_id'), response.seats);
                }
            });
        })

        function appendSelectOption(selector, responseArray) {
            let option = '';
            let id = selector.data('id');
            selector.empty();
            selector.append(' <option value="" selected>-- Choose --</option>');
            responseArray.forEach(function(item, index) {
                let ifSelect = (id != undefined && id == item.id) ? "selected" : "";
                option = "<option value='" + item.id + "' " + ifSelect + ">" + item.name_english + "</option>"
                selector.append(option);
            });
            if (responseArray.length > 0) {
                selector.prop('disabled', false);
            }
        }
    </script>
@endsection
