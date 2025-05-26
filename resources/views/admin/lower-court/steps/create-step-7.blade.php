<form class="steps-form" data-action="{{route('lower-court.create-step-7',$application->id)}}" action="#" method="POST"
    id="create_step_7_form" enctype="multipart/form-data"> @csrf
    <div class="card-body">

        @include('admin.lower-court.partials.application-section')
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        @if($application->is_final_submitted)
        <a href="{{route('lower-court.show',$application->id)}}" class="btn btn-success float-right">Save &
            Update</a>
        @else
        {{--<a href="{{route('lower-court.show',$application->id)}}" class="btn btn-success float-right">Save &
            Submit</a>--}}
        <button type="button" class="btn btn-success float-right" data-toggle="modal"
            data-target="#exampleModalFinal">Save & Submit
        </button>
        @endif

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

                        {{--<h6 class="mt-4">Bank Payment Voucher</h6>
                        <div class="row">
                            <div class="col-12 form-group-inline">
                                <input type="radio" value="Habib Bank Limited" name="bank_name" id="HBL" checked>
                                <label for="HBL">HBL (Online/Branch)</label>
                            </div>
                        </div>--}}

                        {{--<h5 class="mt-2">Select Option.</h5>--}}
                        <div class="form-group">
                            <input type="radio" name="final_submission" value="1" checked hidden required> {{--Print
                            Voucher--}}
                            <br>
                            @if (Auth::guard('admin')->user()->hasPermission('add-payments'))
                            <input type="radio" name="final_submission" value="2" hidden> {{--Submit Payment--}}
                            @endif
                        </div>

                    </div>
                    <div class="modal-footer">
                        {{--<a href="{{route('intimations.token',['download'=>'pdf','application' => $application])}}"
                            target="_blank" class="btn btn-primary">Print Token</a>--}}
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

        <a href="javascript:void(0)" onclick="goToStep('{{route('lower-court.create-step-6', $application->id)}}',6)"
            class="btn btn-secondary float-right mr-1">Back</a>
    </div>
</form>
