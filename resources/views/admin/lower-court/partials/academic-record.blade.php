@forelse ($application->educations as $education)
<tr>
    <td>{{$loop->iteration}}</td>
    <td>
        {{getQualificationName($education->qualification)}}
        {{$education->sub_qualification ?? ''}}
    </td>
    <td>{{$education->university->name ?? $education->institute}}</td>
    <td>{{$education->total_marks ?? 'N/A'}}</td>
    <td>{{$education->obtained_marks ?? 'N/A'}}</td>
    <td>{{$education->passing_year ?? 'N/A'}}</td>
    <td>{{$education->roll_no ?? 'N/A'}}</td>
    <td>
        <a href="{{asset('storage/app/public/'.$education->certificate)}}" target="_blank">
            <span class="badge badge-primary">Preview</span>
        </a>
        <a href="{{asset('storage/app/public/'.$education->certificate)}}"
            download="education-certificate-{{$application->id}}">
            <span class="badge badge-success">Download</span>
        </a>
        <a href="javascript:void(0)" data-action="{{route('lower-court.destroy.academic-record',$education->id)}}"
            onclick="removeDocument(this)">
            <span class="badge badge-danger">Remove</span>
        </a>
    </td>
</tr>
@empty
<tr class="text-center">
    <td colspan="9"><span>No Record(s) Found</span></td>
</tr>
@endforelse
