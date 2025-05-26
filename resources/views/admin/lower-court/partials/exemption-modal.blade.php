<div class="modal fade" id="exemption_modal" tabindex="-1" aria-labelledby="exemption_modal_label" aria-hidden="true"
    data-backdrop="static" data-keyboard="false">
    <form action="#" method="POST" class="exemption_form"> @csrf
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exemption_modal_label"><b>Exemption | Degree | BF Plan</h5>
                </div>
                <div class="modal-body">

                    @if ($application->exemption_form == 0 && $application->is_moved_from_intimation == 0)
                    <fieldset class="border p-4 mb-4">
                        <legend class="w-auto">Exemption</legend>

                        <div class="row">
                            <div class="form-group col-sm-12">
                                <input type="radio" name="exemption_reason" value="1" {{ $application->exemption_reason
                                == 1 ? 'checked' : ''}}><label for="">&nbsp;Holders of LL.M/Bar at Law degree having one
                                    year practical experience certificate.</label>
                            </div>
                            <div class="form-group col-sm-12">
                                <input type="radio" name="exemption_reason" value="2" {{ $application->exemption_reason
                                == 2 ? 'checked' : ''}}><label for="">&nbsp;Possessing four years Legal/Judicial
                                    experience.</label>
                            </div>
                            <div class="form-group col-sm-12">
                                <input type="radio" name="exemption_reason" value="0" {{ $application->exemption_reason
                                == 0 ? 'checked' : ''}}><label for="">&nbsp;None of Above.</label>
                            </div>
                        </div>
                    </fieldset>
                    @endif

                    <fieldset class="border p-4 mb-4">
                        <legend class="w-auto">Degree</legend>
                        <div class="row">
                            @if ($application->intimation_degree_fee == 1)
                            <h5 class="text-success">The degree fee has already been paid in the intimation application.</h5>
                            @else
                            <div class="form-group col-sm-4">
                                <select name="degree_place" id="degree_place" class="form-control custom-select"
                                    {{$application->is_academic_record == TRUE ? 'required' : ''}}>
                                    <option value="1" {{ $application->degree_place == 1 ? 'selected' : ''}}>Punjab
                                    </option>
                                    <option value="2" {{ $application->degree_place == 2 ? 'selected' : ''}}>Out of
                                        Punjab
                                    </option>
                                    <option value="3" {{ $application->degree_place == 3 ? 'selected' : ''}}>Out of
                                        Pakistan
                                    </option>
                                </select>
                            </div>
                            @endif
                        </div>
                    </fieldset>

                    <fieldset class="border p-4 mb-4">
                        <legend class="w-auto">BF Plan</legend>
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <select name="bf_plan" id="bf_plan" class="form-control custom-select">
                                    <option value="1" {{ $application->bf_plan == 1 ? 'selected' : ''}}>
                                        3 Lac Plan
                                    </option>
                                    <option value="2" {{ $application->bf_plan == 2 ? 'selected' : ''}}>
                                        6 Lac Plan
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <p>Note: For the 3 lac plan, the BF fee will be charged at the standard rate, while for the 6 lac
                                plan, the BF fee will be doubled.</p>
                        </div>
                    </fieldset>

                </div>
                <div class="modal-footer">
                    <button type="submit" name="is_exemption" class="btn btn-success btn-sm mt-1" value="save">Save &
                        Next</button>
                </div>
            </div>
        </div>
    </form>
</div>