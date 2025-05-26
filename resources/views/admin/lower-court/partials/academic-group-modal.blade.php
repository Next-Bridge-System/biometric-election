<a id="saveAndNext" href="javascript:void(0)" @if ($application->is_academic_record == false)
    data-target="#academic_group_modal" @endif class="btn btn-success float-right">Save & Next</a>

<div class="modal fade" id="academic_group_modal" tabindex="-1" aria-labelledby="academic_group_modal_label"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="academic_group_modal_label"><b>Note: </b>Enter Records
                    of Group A or Group B</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h4 class="text-center"><u>Group: A</u></h4>
                        <h6>-Matric/SSC</h6>
                        <h6>-Intermediate/HSSC</h6>
                        <h6>-BA/BA HONS</h6>
                        <h6>-LLB PART-1</h6>
                        <h6>-LLB PART-2</h6>
                        <h6>-LLB PART-3</h6>
                        <h6>-MA/MSC/LLM <small>(Optional)</small></h6>
                        <h6>-GAT <small>(Optional only if exemption of *Possessing four years Legal/Judicial
                                experience*)</small></h6>
                        <h6>-C-Law <small>(Required if Foreign Degree)</small></h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="text-center"><u>Group: B</u></h4>
                        <h6>-Matric/SSC</h6>
                        <h6>-Intermediate/HSSC</h6>
                        <h6>-LLB HONS/BAR At Law</h6>
                        <h6>-MA/MSC/LLM <small>(Optional)</small></h6>
                        <h6>-GAT <small>(Optional only if exemption of *Possessing four years Legal/Judicial
                                experience*)</small></h6>
                        <h6>-C-Law <small>(Required if Foreign Degree)</small></h6>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                </button>
            </div>
        </div>
    </div>
</div>
