<form class="steps-form" data-action="{{route('frontend.high-court.create-step-6',$application->id)}}" action="#"
    method="POST" id="create_step_7_form" enctype="multipart/form-data"> @csrf
    <div class="card-body">
        @include('admin.high-court.partials.application-section')
    </div>

    <div class="card-footer">

        <button type="button" class="btn btn-success float-right" data-toggle="modal"
            data-target="#exampleModalFinal">Save & Submit
        </button>

        <a href="javascript:void(0)"
            onclick="goToStep('{{route('frontend.high-court.create-step-5', $application->id)}}',5)"
            class="btn btn-secondary float-right mr-1">Back</a>

        <div class="modal fade" id="exampleModalFinal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Verify Application</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h6>Are you sure you want to submit this application. Kindly verify your all changes & uploads.
                            Thankyou.</h6>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success float-right submit_btn" value="save">Yes, I Accept
                            It.
                        </button>
                        <button class="btn btn-success hidden loading_btn" type="button" disabled><span
                                class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>Loading...
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>