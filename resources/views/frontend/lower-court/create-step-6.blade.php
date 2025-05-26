<form class="steps-form" data-action="{{ route('frontend.lower-court.create-step-6',$application->id) }}" action="#"
    method="POST" id="create_step_5_form" enctype="multipart/form-data"> @csrf
    <div class="card-body">
        @include('admin.lower-court.partials.image-upload-section')
    </div>

    <div class="card-footer">
        <button type="submit" class="btn btn-success float-right" value="save">Save & Next</button>
        <a href="javascript:void(0)"
            onclick="goToStep('{{route('frontend.lower-court.create-step-5', $application->id)}}',5)"
            class="btn btn-secondary float-right mr-1">Back</a>
    </div>
</form>
