<button type="button" class="btn btn-primary float-right btn-sm m-1" data-toggle="modal" data-target="#prints"><i
        class="fas fa-print mr-1"></i>High Court Prints
</button>

<div class="modal fade" id="prints" tabindex="-1" aria-labelledby="printsLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="printsLabel">High Court Prints</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" id="send_messages_form_hc" method="POST"> @csrf
                <div class="modal-body">
                    <table class="table table-bordered table-sm">

                        <body>

                            <tr>
                                <th>Form - HC</th>
                                <td>
                                    <a href="{{route('high-court.prints.form-hc',$application->id)}}" target="_blank"
                                        class="btn btn-primary btn-sm"><i class="fas fa-print mr-1"></i>Print</a>
                                </td>
                            </tr>

                            @if (permission('print-detail-hc'))
                            {{-- <tr>
                                <th>Detail - HC</th>
                                <td>
                                    <a href="{{route('high-court.prints.detail',$application->id)}}" target="_blank"
                                        class="btn btn-primary btn-sm"><i class="fas fa-print mr-1"></i>Print</a>
                                </td>
                            </tr> --}}
                            <tr>
                                <th>Short Detail - HC</th>
                                <td>
                                    <a href="{{ route('high-court.prints.short-detail', ['id' => $application->id, 'type' => 'hc']) }}"
                                        target="_blank" class="btn btn-primary btn-sm">
                                        <i class="fas fa-print mr-1"></i>Print</a>
                                </td>
                            </tr>
                            @endif

                            <tr>
                                <th>Advocate Certificate - HC</th>
                                <td>
                                    @if (getHcAdvocateCertificateStatus($application))
                                    <a href="{{route('high-court.prints.advocate-certificate',$application->id)}}"
                                        target="_blank" class="btn btn-primary btn-sm"><i
                                            class="fas fa-print mr-1"></i>Print</a>
                                    @else No @endif
                                </td>
                            </tr>
                        </body>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>