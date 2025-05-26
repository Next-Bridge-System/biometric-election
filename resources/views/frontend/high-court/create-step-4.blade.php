<form class="steps-form" data-action="{{ route('frontend.high-court.create-step-4',$application->id) }}" action="#"
    method="POST" id="create_step_5_form"> @csrf
    <div class="card-body">
        <fieldset class="border p-4 mb-4">
            <legend class="w-auto">Questions</legend>
            <div class="row">
                <div class="col-sm-12 form-group">
                    <label for="">Wether the applicant has paid upto date renewal fees and arrears:If Any?</label>
                    <br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="is_engaged_in_business1" value="Yes"
                            name="paid_upto_date_renewal" {{ $application->paid_upto_date_renewal == "Yes" ? "checked" :
                        "" }}>
                        <label class="form-check-label" for="is_engaged_in_business1">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="is_engaged_in_business2" value="No"
                            name="paid_upto_date_renewal" {{ $application->paid_upto_date_renewal == "No" ? "checked" :
                        "" }}>
                        <label class="form-check-label" for="is_engaged_in_business2">No</label>
                    </div>
                </div>
                <div class="col-sm-12 form-group">
                    <label for="">Whether the applicant proposes to practice generally within the jurisdiction of the
                        Punjab Bar Council?</label>
                    <br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="is_practice_in_pbc1" value="Yes"
                            name="is_practice_in_pbc" {{ $application->is_practice_in_pbc == "Yes" ? "checked" : "" }}>
                        <label class="form-check-label" for="is_practice_in_pbc1">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="is_practice_in_pbc2" value="No"
                            name="is_practice_in_pbc" {{ $application->is_practice_in_pbc == "No" ? "checked" : "" }}>
                        <label class="form-check-label" for="is_practice_in_pbc2">No</label>
                    </div>

                </div>
                <div class="col-sm-12 form-group">
                    <label for="">Wether He/She is engaged in any business, Profession or vocation in Pakistan?If so,
                        the nature of thereof and the place which it is carried on?</label>
                    <br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="is_declared_insolvent1" value="Yes"
                            name="is_engaged_in_business" {{ $application->is_engaged_in_business == "Yes" ? "checked" :
                        "" }}>
                        <label class="form-check-label" for="is_declared_insolvent1">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="is_declared_insolvent2" value="No"
                            name="is_engaged_in_business" {{ $application->is_engaged_in_business == "No" ? "checked" : ""
                        }}>
                        <label class="form-check-label" for="is_declared_insolvent2">No</label>
                    </div>
                </div>
                <div class="col-sm-12 form-group">
                    <label for="">Wether the applicant has been declared insolvent?</label>
                    <br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="is_dismissed_from_gov1" value="Yes"
                            name="is_declared_insolvent" {{ $application->is_declared_insolvent == "Yes" ? "checked" :
                        "" }}>
                        <label class="form-check-label" for="is_dismissed_from_gov1">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="is_dismissed_from_gov2" value="No"
                            name="is_declared_insolvent" {{ $application->is_declared_insolvent == "No" ? "checked" : ""
                        }}>
                        <label class="form-check-label" for="is_dismissed_from_gov2">No</label>
                    </div>

                </div>
                <div class="col-sm-12 form-group">
                    <label for="">Weather the applicant has been dismissed/removed from Govt Service or of a public
                        Statutory Corporation?If so furnish copy of charge sheet, statement of charges, reply thereto
                        and final decision. Wether the applicant has been convicted of any offence if so, furnish facts
                        and particulars thereof?</label>
                    <br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="is_enrolled_as_adv1" value="Yes"
                            name="is_dismissed_from_public_service"
                            {{ $application->is_dismissed_from_public_service == "Yes" ? "checked" : "" }}>
                        <label class="form-check-label" for="is_enrolled_as_adv1">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="is_enrolled_as_adv2" value="No"
                            name="is_dismissed_from_public_service"
                            {{ $application->is_dismissed_from_public_service == "No" ? "checked" : "" }}>
                        <label class="form-check-label" for="is_enrolled_as_adv2">No</label>
                    </div>
                </div>
                <div class="col-sm-12 form-group">
                    <label for="">Wether the applicant is enrolled as an Advocate on the roll of any other Provincial
                        Bar Council?</label>
                    <br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="is_offensed1" value="Yes"
                            name="is_enrolled_as_advocate" {{
                            $application->is_enrolled_as_advocate == "Yes" ? "checked" : "" }}>
                        <label class="form-check-label" for="is_offensed1">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="is_offensed2" value="No"
                            name="is_enrolled_as_advocate" {{
                            $application->is_enrolled_as_advocate == "No" ? "checked" : "" }}>
                        <label class="form-check-label" for="is_offensed2">No</label>
                    </div>

                </div>
                <div class="col-sm-12 form-group">
                    <label for="">Wether any other Bar Council has previously rejected the application of the applicant
                        for enrollment?
                    </label>
                    <br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="is_prev_rejected1" value="Yes"
                            name="is_prev_rejected" {{$application->is_prev_rejected == 'Yes' ? 'checked' : ''}}>
                        <label class="form-check-label" for="is_prev_rejected1">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="is_prev_rejected2" value="No"
                            name="is_prev_rejected" {{$application->is_prev_rejected == 'No' ? 'checked' : ''}}>
                        <label class="form-check-label" for="is_prev_rejected2">No</label>
                    </div>
                </div>

                <div class="col-sm-12 form-group">
                    <label for="">Wether the applicant is involved in any professional misconduct proceeding?
                    </label>
                    <br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="is_any_misconduct1" value="Yes"
                            name="is_any_misconduct" {{$application->is_any_misconduct == 'Yes' ? 'checked' : ''}}>
                        <label class="form-check-label" for="is_any_misconduct1">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="is_any_misconduct2" value="No"
                            name="is_any_misconduct" {{$application->is_any_misconduct == 'No' ? 'checked' : ''}}>
                        <label class="form-check-label" for="is_any_misconduct2">No</label>
                    </div>
                </div>

            </div>
        </fieldset>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-success float-right" value="save">Save & Next</button>
        <a href="javascript:void(0)"
            onclick="goToStep('{{route('frontend.high-court.create-step-3', $application->id)}}',3)"
            class="btn btn-secondary float-right mr-1">Back</a>
    </div>
</form>