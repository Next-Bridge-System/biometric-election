<button type="button" class="btn btn-info btn-lg mr-1" data-toggle="modal" data-target="#fingerModal">
    Choose Finger
</button>

<div class="modal fade" id="fingerModal" tabindex="-1" aria-labelledby="fingerModalLabel" aria-hidden="true"
    data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fingerModalLabel">Select Finger</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    {{-- <span aria-hidden="true">&times;</span> --}}
                </button>
            </div>
            <div class="modal-body">
                <select name="finger" id="finger" class="custom-select form-control">
                    <option value="" selected>--SELECT FINGER--</option>
                    @for ($i = 1; $i <= 10; $i++) <option value="{{$i}}">{{$i}} - {{getFingerName($i)}}</option>@endfor
                </select>
            </div>
            <div class="modal-footer">
                {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> --}}
                <button type="button" class="btn btn-primary" data-dismiss="modal">Choose Finger</button>
            </div>
        </div>
    </div>
</div>
