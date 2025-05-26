<form class="steps-form" data-action="{{ route('frontend.high-court.create-step-5',$application->id) }}" action="#"
    method="POST" id="create_step_5_form" enctype="multipart/form-data"> @csrf
    <div class="card-body">
        @include('admin.high-court.partials.image-upload-section')
    </div>

    <div class="card-footer">
        <button type="submit" class="btn btn-success float-right" value="save">Save & Next</button>
        <a href="javascript:void(0)"
            onclick="goToStep('{{route('frontend.high-court.create-step-4', $application->id)}}',4)"
            class="btn btn-secondary float-right mr-1">Back</a>
    </div>
</form>