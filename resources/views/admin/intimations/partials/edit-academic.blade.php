<span type="button" class="badge badge-primary" data-toggle="modal" data-target="#editAcademicModal{{$education->id}}">
    <i class="fas fa-edit mr-1" aria-hidden="true"></i> Edit
</span>

<div class="modal fade" id="editAcademicModal{{$education->id}}" tabindex="-1" aria-labelledby="editAcademicModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form action="#" method="POST" class="update_academic_form"> @csrf
            <input type="hidden" name="education_id" id="education_id" value="{{$education->id}}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAcademicModalLabel">
                        Edit - {{getQualificationName($education->qualification)}}
                        {{$education->sub_qualification ?? ''}} # {{$education->id}}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    @if ($education->qualification == '4' || $education->qualification == '5' ||
                    $education->qualification == '6' || $education->qualification == '7' ||
                    $education->qualification == '8')
                    <div class="form-group">
                        <label for="" class="float-left">University</label>
                        <select name=" university_id" id="university_id"
                            class="form-control custom-select university_id">
                            <option value="" selected>--Select University--</option>
                            <option value="0" @if ($education->university_id == 0)
                                selected @endif>Other Institute (If not in the list)</option>

                            @foreach ($universities as $uni)
                            <option value="{{$uni->id}}" @if ($education->university_id ==
                                $uni->id) selected @endif
                                class="font-weight-bold text-success"> {{$loop->iteration}} - {{$uni->name}}
                            </option>

                            @foreach ($affliatedUniversities->where('aff_university_id', $uni->id) as $affUni)
                            <option value="{{$affUni->id}}" @if ($education->university_id == $affUni->id) selected
                                @endif class="font-weight-normal" title="{{$affUni->name}}">
                                {{numberToRomanRepresentation($loop->iteration)}} - {{$affUni->name}}
                            </option>
                            @endforeach

                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="form-group institute">
                        <label for=""
                            class="float-left institute {{$education->university_id == 0 ? '' : 'hidden'}}">Institute</label>
                        <input type="text" name="institute"
                            class="form-control institute {{$education->university_id == 0 ? '' : 'hidden'}}"
                            value="{{$education->university->name ?? $education->institute}}">
                    </div>

                    <div class="form-group">
                        <label for="" class="float-left">Total Marks</label>
                        <input type="text" name="total_marks" id="total_marks" class="form-control"
                            value="{{$education->total_marks}}">
                    </div>
                    <div class="form-group">
                        <label for="" class="float-left">Obtained Marks</label>
                        <input type=" text" name="obtained_marks" id="obtained_marks" class="form-control"
                            value="{{$education->obtained_marks}}">
                    </div>
                    <div class="form-group">
                        <label for="" class="float-left">Passing Year</label>
                        <input type=" text" name="passing_year" id="passing_year" class="form-control"
                            value="{{$education->passing_year}}">
                    </div>
                    <div class="form-group">
                        <label for="" class="float-left">Roll No</label>
                        <input type=" text" name="roll_no" id="roll_no" class="form-control"
                            value="{{$education->roll_no}}">
                    </div>
                    <div class="form-group">
                        <label for="" class="float-left">Certificate</label>
                        <input type="file" name="certificate_url" id="certificate_url"
                            class="form-control custom-image-upload">
                        <a href="{{asset('storage/app/public/'.$education->certificate)}}" class="float-right"
                            download="certificate">
                            <span class="badge badge-success"><i class="fas fa-file-download mr-1"></i>Download</span>
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-primary">Save & Update</button>
                </div>
        </form>
    </div>
</div>
</div>
