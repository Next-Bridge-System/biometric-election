@extends('layouts.frontend')

@section('content')

<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Add Intimation Application</h3>
                    </div>

                    @include('frontend.intimation.partials.steps')

                    <form action="#" method="POST" id="create_step_4_form" enctype="multipart/form-data"> @csrf
                        <div class="card-body">
                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto">Academic Record List</legend>
                                <div class="row table-responsive">
                                    <table class="table table-sm text-center" id="lawyer_academic_table">
                                        <tbody>
                                            <tr class="bg-light">
                                                <th>#</th>
                                                <th>Qualification</th>
                                                <th>Sub Qualification</th>
                                                <th>Institute</th>
                                                <th>Total Marks</th>
                                                <th>Obtained Marks</th>
                                                <th>Passing Year</th>
                                                <th>Roll No</th>
                                                <th>Upload Certificate</th>
                                                <th></th>
                                            </tr>
                                            @forelse ($application->educations as $education)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>
                                                    {{getQualificationName($education->qualification)}}
                                                </td>
                                                <td>{{$education->sub_qualification ?? 'N/A'}}</td>
                                                <td>{{$education->university->name ?? $education->institute}}</td>
                                                <td>{{$education->total_marks ?? 'N/A'}}</td>
                                                <td>{{$education->obtained_marks ?? 'N/A'}}</td>
                                                <td>{{$education->passing_year ?? 'N/A'}}</td>
                                                <td>{{$education->roll_no ?? 'N/A'}}</td>
                                                <td>
                                                    <a href="{{asset('storage/app/public/'.$education->certificate)}}"
                                                        download="education-certificate-{{$application->application_token_no}}">
                                                        <span class="badge badge-success">Download</span>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="{{asset('storage/app/public/'.$education->certificate)}}"
                                                        target="_blank"><span class="badge badge-primary"><i
                                                                class="fas fa-print mr-1"></i>Preview</span>
                                                    </a>
                                                    <a href="{{route('frontend.intimation.destroy.academic-record',$education->id)}}"
                                                        onclick="return confirm ('Are you sure you want to delete it. This action cannot be revert.')">
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
                                    <div class="academic_record">
                                        <div class="form-group mr-1" style="width:22%">
                                            <select name="qualification" id="qualification"
                                                class="form-control custom-select">
                                                <option value="" selected>--Qualification--</option>

                                                @if (in_array('1', $academicRecord) == false || $academicRecord == [])
                                                <option value="1">{{getQualificationName(1)}}</option>
                                                @endif

                                                @if (in_array('2', $academicRecord) == false && in_array('1',
                                                $academicRecord) == true )
                                                <option value="2">{{getQualificationName(2)}}</option>
                                                @endif

                                                @if (in_array('2', $academicRecord) == true)

                                                @if (in_array('3', $academicRecord) == false)
                                                <option value="3">{{getQualificationName(3)}}</option>
                                                @endif

                                                @if (in_array('4', $academicRecord) == false)
                                                <option value="4">{{getQualificationName(4)}}</option>
                                                @endif

                                                @if (in_array('5', $academicRecord) == false)
                                                <option value="5">{{getQualificationName(5)}}</option>
                                                @endif

                                                @if (in_array('6', $academicRecord) == false)
                                                <option value="6">{{getQualificationName(6)}}</option>
                                                @endif

                                                @if (in_array('7', $academicRecord) == false)
                                                <option value="7">{{getQualificationName(7)}}</option>
                                                @endif

                                                @if (in_array('8', $academicRecord) == false)
                                                <option value="8">{{getQualificationName(8)}} </option>
                                                @endif

                                                @if (in_array('9', $academicRecord) == false)
                                                <option value="9">{{getQualificationName(9)}}</option>
                                                @endif

                                                @endif
                                            </select>
                                        </div>
                                        <div class="form-group mr-1" style="width:25%">
                                            <select name="sub_qualification" id="sub_qualification"
                                                class="form-control custom-select" disabled>
                                                <option value="" selected>--Sub Qualification--</option>
                                                <option value="O-Level" class="matric">O-Level</option>
                                                <option value="Secondary School" class="matric">Secondary School
                                                </option>
                                                <option value="A-Level" class="inter">A-Level</option>
                                                <option value="FSC" class="inter">FSC</option>
                                                <option value="ICS" class="inter">ICS</option>
                                                <option value="FA" class="inter">FA</option>
                                                <option value="I.COM" class="inter">I.COM</option>
                                            </select>
                                        </div>
                                        <div class="form-group mr-1" style="width:18%">
                                            <select name="university_id" id="university_id"
                                                class="form-control custom-select">
                                                <option value="" selected>--Institute--</option>
                                                <option value="0">Other <small>(Select other if not in the List)</small>
                                                </option>

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
                                            <input type="number" step="0.01" class="form-control" name="total_marks"
                                                placeholder="Total Marks">
                                        </div>
                                        <div class="form-group mr-1" style="width:15%">
                                            <input type="number" step="0.01" class="form-control" name="obtained_marks"
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
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                        </div>
                        <div class="card-footer">

                            @if ($application->is_academic_record == TRUE)
                            <a href="{{route('frontend.intimation.create-step-5', $application->id)}}"
                                class="btn btn-success float-right">Save & Next</a>
                            @else
                            <button type="button" class="btn btn-success float-right" data-toggle="modal"
                                data-target="#exampleModal">Save & Next </button>
                            @endif

                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel"> <b>Note: </b>Enter Records
                                                of Group A or Group B</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h5>Group: A</h5>
                                                    <h6>-Matric/SSC</h6>
                                                    <h6>-Intermediate/HSSC</h6>
                                                    <h6>-BA/BA HONS</h6>
                                                    <h6>-LLB PART-1</h6>
                                                    <h6>-LLB PART-2</h6>
                                                    <h6>-LLB PART-3</h6>
                                                    <h6>-MA/MSC/LLM <small>(Optional)</small></h6>
                                                </div>
                                                <div class="col-md-6">
                                                    <h5>Group: B</h5>
                                                    <h6>-Matric/SSC</h6>
                                                    <h6>-Intermediate/HSSC</h6>
                                                    <h6>-LLB HONS/BAR At Law</h6>
                                                    <h6>-MA/MSC/LLM <small>(Optional)</small></h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <a href="{{route('frontend.intimation.create-step-3', $application->id)}}"
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
      $("#create_step_4_form").on("submit", function(event){
          event.preventDefault();
          $('span.text-success').remove();
          $('span.invalid-feedback').remove();
          $('input.is-invalid').removeClass('is-invalid');
          var formData = new FormData(this);
          $.ajax({
              method: "POST",
              data: formData,
              url: '{{route('frontend.intimation.create-step-4', $application->id)}}',
              processData: false,
              contentType: false,
              cache: false,
              beforeSend: function(){
                $(".custom-loader").removeClass('hidden');
              },
              success: function (response) {
                if (response.status == 1) {
                    location.reload();
                    // window.location.href = '{{route('frontend.intimation.create-step-6', $application->id)}}';
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
        if (qualification == 1 ||
            qualification == 2 ||
            qualification == 3 ||
            qualification == 9) {
                $("#university_id").parent().addClass('hidden');
                $("#institute").parent().removeClass('hidden');
        } else {
            $("#university_id").parent().removeClass('hidden');
            $("#institute").parent().addClass('hidden');
        }

        if (qualification == 1 || qualification == 2) {
            $("#sub_qualification").attr('disabled', false);
        } else {
            $("#sub_qualification").attr('disabled', true);
        }

        if (qualification == 1) {
            $(".matric").removeClass('hidden');
        } else {
            $(".matric").addClass('hidden');
        }

        if (qualification == 2) {
                $(".inter").removeClass('hidden');
        } else {
            $(".inter").addClass('hidden');
        }

    });

    $("#university_id").change(function (e) {
        e.preventDefault();
        var value = $(this).val();
        if (value == '0') {
            $("#institute").parent().removeClass('hidden');
        } else {
            $("#institute").parent().addClass('hidden');
        }
    });

    $(function () {
        $("#lawyer_academic_table").DataTable({
            "responsive": true,
            "autoWidth": false,
        });
    });
</script>
@endsection
