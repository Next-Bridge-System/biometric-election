<form class="steps-form" action="#" data-action="{{route('lower-court.create-step-4', $application->id)}}" method="POST"
    id="admin_lc_create_step_4_form" enctype="multipart/form-data"> @csrf
    <div class="card-body">
        <fieldset class="border p-4 mb-4">
            <legend class="w-auto">Academic Record List</legend>
            <div class="row">
                <table class="table table-sm text-center" id="lawyer_academic_table">
                    <thead>
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
                    </thead>
                    <tbody id="academicRecord">
                        @include('admin.lower-court.partials.academic-record')
                    </tbody>
                </table>
            </div>
        </fieldset>

        <fieldset class="border p-4 mb-4">
            <legend class="w-auto">Academic Record</legend>
            <div class="row">
                <div class="d-flex">
                    <div class="form-group mr-1" style="width:22%">
                        <select name="qualification" id="qualification" class="form-control custom-select">
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

                            @if (in_array('10', $academicRecord) == false)
                            <option value="10">{{getQualificationName(10)}}</option>
                            @endif

                            @endif
                        </select>
                    </div>
                    <div class="form-group mr-1" style="width:25%">
                        <select name="sub_qualification" id="sub_qualification" class="form-control custom-select"
                            disabled>
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
                    <div class="form-group mr-1 hidden" style="width:18%">
                        <input type="text" name="gat_pass" id="gat_pass" class="form-control"
                            placeholder="GAT portal Password">
                    </div>
                    <div class="form-group mr-1" style="width:18%">
                        <select name="university_id" id="university_id" class="form-control custom-select">
                            <option value="" selected>--Institute--</option>

                            @foreach ($universities as $uni)
                            <option value="{{$uni->id}}" class="font-weight-bold" style="font-size: 14px">
                                {{$uni->name}}
                            </option>

                            @foreach ($affliatedUniversities
                            ->where('aff_university_id', $uni->id) as $affUni)
                            <option value="{{$affUni->id}}" class="font-weight-normal" title="{{$affUni->name}}"
                                style="font-size: 14px">
                                &nbsp;&nbsp;{{$affUni->name}}
                            </option>
                            @endforeach

                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mr-1 hidden" style="width:18%">
                        <input type="text" name="institute" id="institute" class="form-control" placeholder="Institute">
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
                        <input type="text" class="form-control" name="roll_no" placeholder="Roll No">
                    </div>
                    <div class="form-group mr-1" style="width:14%">
                        <select name="passing_year" id="passing_year" class="form-control custom-select">
                            <option value="" selected>--Passing Year--</option>
                        </select>
                    </div>
                    <div class="form-group mr-1" style="width:15.50%">
                        <input type="file" class="form-control custom-image-upload" name="certificate_url">
                    </div>
                    <div class="form-group mr-1">
                        <button type="submit" name="submit" value="add" class="btn btn-success btn-sm mt-1">Add
                        </button>
                    </div>
                </div>
            </div>
        </fieldset>

    </div>
    <div class="card-footer">
        @include('admin.lower-court.partials.academic-group-modal')
        <a href="javascript:void(0)" onclick="goToStep('{{route('lower-court.create-step-3', $application->id)}}',3)"
            class="btn btn-secondary float-right mr-1">Back</a>
    </div>
</form>

{{-- @include('admin.lower-court.partials.exemption-modal') --}}
