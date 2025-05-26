@extends('layouts.admin')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Manage Existing Lawyers</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('lawyers.index')}}" class="btn btn-dark">Back</a>
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
                        <h3 class="card-title">Add Existing Lawyer</h3>
                    </div>

                    @include('admin.lawyers.partials.steps')

                    <form action="#" method="POST" id="create_step_5_form" enctype="multipart/form-data"> @csrf
                        <div class="card-body">
                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto">Academic Record List</legend>
                                <div class="row">
                                    <table class="table table-sm text-center" id="lawyer_academic_table">
                                        <tbody>
                                            <tr class="bg-light">
                                                <th>#</th>
                                                <th>Qualification</th>
                                                <th>Institute</th>
                                                <th>Total Marks</th>
                                                <th>Obtained Marks</th>
                                                <th>Passing Year</th>
                                                <th>Roll No</th>
                                                <th>Upload Certificate</th>
                                                <th></th>
                                            </tr>
                                            @forelse ($lawyer->educations as $education)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>
                                                    {{$education->qualification ?? 'N/A'}} /
                                                    {{$education->sub_qualification ?? 'N/A'}}
                                                </td>
                                                <td>{{$education->university->name ?? $education->institute}}</td>
                                                <td>{{$education->total_marks ?? 'N/A'}}</td>
                                                <td>{{$education->obtained_marks ?? 'N/A'}}</td>
                                                <td>{{$education->passing_year ?? 'N/A'}}</td>
                                                <td>{{$education->roll_no ?? 'N/A'}}</td>
                                                <td>
                                                    <a href="#">
                                                        <span class="badge badge-success">Download</span>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="#">
                                                        <span class="badge badge-danger">Remove</span>
                                                    </a>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr class="text-center">
                                                <td colspan="9"> <span>No Record(s) Found</span> </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </fieldset>

                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto">Academic Record</legend>
                                <div class="row">
                                    <div class="d-flex">
                                        <div class="form-group mr-1" style="width:22%">
                                            <select name="qualification" id="qualification"
                                                class="form-control custom-select">
                                                <option value="" selected>--Qualification--</option>
                                                <option value="Matric/SSC">Matric/SSC</option>
                                                <option value="Intermediate/HSSC">Intermediate/HSSC</option>
                                                <option value="BA/BA HONS">BA/BA HONS </option>
                                                <option value="LLB PART-1">LLB PART-1</option>
                                                <option value="LLB PART-2">LLB PART-2</option>
                                                <option value="LLB PART-3">LLB PART-3</option>
                                                <option value="LLB HONS/BAR At Law">LLB HONS/BAR At Law </option>
                                                <option value="MA/MSC/LLM ">MA/MSC/LLM </option>
                                                <option value="GAT">GAT</option>
                                            </select>
                                        </div>
                                        <div class="form-group mr-1" style="width:25%">
                                            <select name="sub_qualification" id="sub_qualification"
                                                class="form-control custom-select" disabled>
                                                <option value="" selected>--Sub Qualification--</option>
                                                <option value="O-Level" class="matric">O-Level</option>
                                                <option value="A-Level" class="inter">A-Level</option>
                                                <option value="FSC" class="inter">FA/FSC</option>
                                            </select>
                                        </div>
                                        <div class="form-group mr-1" style="width:18%">
                                            <select name="university_id" id="university_id"
                                                class="form-control custom-select">
                                                <option value="" selected>--Institute--</option>

                                                @foreach ($universities as $uni)
                                                <option value="{{$uni->id}}" class="font-weight-bold"
                                                    style="font-size: 14px">
                                                    {{$uni->name}}
                                                </option>

                                                @foreach ($affliatedUniversities
                                                ->where('aff_university_id', $uni->id) as $affUni)
                                                <option value="{{$affUni->id}}" class="font-weight-normal"
                                                    title="{{$affUni->name}}" style="font-size: 14px">
                                                    &nbsp;&nbsp;{{$affUni->name}}
                                                </option>
                                                @endforeach

                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group mr-1 hidden" style="width:18%">
                                            <input type="text" name="institute" id="institute" class="form-control"
                                                placeholder="Institute">
                                        </div>
                                        <div class="form-group mr-1" style="width:16%">
                                            <input type="number" class="form-control" name="total_marks"
                                                placeholder="Total Marks">
                                        </div>
                                        <div class="form-group mr-1" style="width:15%">
                                            <input type="number" class="form-control" name="obtained_marks"
                                                placeholder="Obtained">
                                        </div>
                                        <div class="form-group mr-1" style="width:15%">
                                            <input type="text" class="form-control" name="roll_no"
                                                placeholder="Roll No">
                                        </div>
                                        <div class="form-group mr-1" style="width:14%">
                                            <select name="passing_year" id="passing_year"
                                                class="form-control custom-select">
                                                <option value="" selected>--Passing Year--</option>
                                            </select>
                                        </div>
                                        <div class="form-group mr-1" style="width:15.50%">
                                            <input type="file" class="form-control custom-image-upload"
                                                name="certificate_url">
                                        </div>
                                        <div class="form-group mr-1">
                                            <button type="submit" class="btn btn-success btn-sm mt-1">Add
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            {{-- <button type="submit" class="btn btn-success float-right" value="save">Save &
                                Next</button> --}}
                            <a href="{{route('lawyers.create-step-6', $lawyer->id)}}"
                                class="btn btn-success float-right">Save & Next</a>
                            <a href="{{route('lawyers.create-step-4', $lawyer->id)}}"
                                class="btn btn-secondary float-right mr-1">Back</a>
                        </div>
                    </form>
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
      $("#create_step_5_form").on("submit", function(event){
          event.preventDefault();
          $('span.text-success').remove();
          $('span.invalid-feedback').remove();
          $('input.is-invalid').removeClass('is-invalid');
          var formData = new FormData(this);
          $.ajax({
              method: "POST",
              data: formData,
              url: '{{route('lawyers.create-step-5', $lawyer->id)}}',
              processData: false,
              contentType: false,
              cache: false,
              beforeSend: function(){
                $(".custom-loader").removeClass('hidden');
              },
              success: function (response) {
                if (response.status == 1) {
                    location.reload();
                    // window.location.href = '{{route('lawyers.create-step-6', $lawyer->id)}}';
                }
              },
              error : function (errors) {
                errorsGet(errors.responseJSON.errors)
                $(".custom-loader").addClass('hidden');
              }
          });
      });
    });
</script>

<script type="text/javascript">
    let startYear = 1900;
    let endYear = new Date().getFullYear();
    for (i = endYear; i > startYear; i--)
    {
      $('#passing_year').append($('<option />').val(i).html(i));
    }
</script>

<script>
    $("#qualification").change(function (e) {
        e.preventDefault();
        var qualification = $(this).val();
        if (qualification == 'Matric/SSC' ||
            qualification == 'Intermediate/HSSC' ||
            qualification == 'BA/BA HONS' ||
            qualification == 'GAT') {
                $("#university_id").parent().addClass('hidden');
                $("#institute").parent().removeClass('hidden');
        } else {
            $("#university_id").parent().removeClass('hidden');
            $("#institute").parent().addClass('hidden');
        }

        if (qualification == 'Matric/SSC' || qualification == 'Intermediate/HSSC') {
            $("#sub_qualification").attr('disabled', false);
        } else {
            $("#sub_qualification").attr('disabled', true);
        }

        if (qualification == 'Matric/SSC') {
            $(".matric").removeClass('hidden');
        } else {
            $(".matric").addClass('hidden');
        }

        if (qualification == 'Intermediate/HSSC') {
                $(".inter").removeClass('hidden');
        } else {
            $(".inter").addClass('hidden');
        }

    });
</script>
@endsection
