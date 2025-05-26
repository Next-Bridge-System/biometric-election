@if (permission('hc_objections'))
<button type="button" class="btn btn-primary float-right btn-sm m-1" data-toggle="modal" data-target="#objections">
    <i class="far fa-comments mr-1"></i>Objections
    <span class="badge badge-warning ml-1">{{isset($application->objections) ?
        count(json_decode($application->objections, TRUE)) : 0}}</span>
</button>

<div class="modal fade" id="objections" tabindex="-1" aria-labelledby="objectionsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="objectionsLabel">High Court Objections</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" id="hc_objections" method="POST"> @csrf
                <div class="form-group col-md-12 mt-2" style="overflow-y:scroll; height: 400px">
                    <table class="table table-striped table-bordered table-sm">
                        <tbody>
                            @for ($i = 1; $i <= 15; $i++) <tr>
                                <td>
                                    <input type="checkbox" name="objections[]" id="objection" value="{{$i}}" @php
                                        $objections=json_decode($application->objections, TRUE)
                                    @endphp

                                    @isset($objections)
                                    $obj_count = 0;
                                    @foreach ($objections as $obj)
                                    @if ($obj == $i)
                                    checked

                                    $obj_count ++
                                    @endif
                                    @endforeach
                                    @endisset

                                    > {{$i}} - {{getLcObjections($i)}}
                                </td>
                                </tr>
                                @endfor
                        </tbody>
                    </table>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm float-right">Save & Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif