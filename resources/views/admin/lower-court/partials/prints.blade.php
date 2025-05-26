<button type="button" class="btn btn-primary float-right btn-sm m-1" data-toggle="modal" data-target="#prints"><i
        class="fas fa-print mr-1"></i>Lower Court Prints
</button>

<div class="modal fade" id="prints" tabindex="-1" aria-labelledby="printsLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="printsLabel">Lower Court Prints</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" id="send_messages_form_lc" method="POST"> @csrf
                <div class="modal-body">
                    <table class="table table-bordered table-sm">

                        <body>
                            <tr>
                                <th>Application Token</th>
                                <td>
                                    <a href="{{route('lower-court.prints.token',['download'=>'pdf','application' => $application])}}"
                                        class="btn btn-primary btn-sm" target="_blank"><i class="fas fa-print mr-1"
                                            aria-hidden="true"></i>Print</a>
                                </td>
                            </tr>

                            @if ($application->is_exemption == 0)
                            <tr>
                                <th>Form B - TC</th>
                                <td>
                                    <a href="{{route('lower-court.prints.form-b', $application->id)}}"
                                        class="btn btn-primary btn-sm" target="_blank"><i class="fas fa-print mr-1"
                                            aria-hidden="true"></i>Print</a>
                                </td>
                            </tr>
                            @endif

                            <tr>
                                <th>Form A - LC</th>
                                <td>
                                    <a href="{{route('lower-court.prints.form-lc',['download'=>'pdf','application' => $application])}}"
                                        target="_blank" class="btn btn-primary btn-sm"><i
                                            class="fas fa-print mr-1"></i>Print</a>
                                </td>
                            </tr>

                            @if (permission('print-detail-lc'))
                            <tr>
                                <th>Detail - LC</th>
                                <td>
                                    <a href="{{route('lower-court.prints.detail',$application->id)}}" target="_blank"
                                        class="btn btn-primary btn-sm"><i class="fas fa-print mr-1"></i>Print</a>
                                </td>
                            </tr>
                            <tr>
                                <th>Short Detail - LC</th>
                                <td>
                                    <a href="{{route('lower-court.prints.short-detail',['id' => $application->id, 'type' => 'lc'])}}"
                                        target="_blank" class="btn btn-primary btn-sm">
                                        <i class="fas fa-print mr-1"></i>Print</a>
                                </td>
                            </tr>
                            @endif

                            <tr>
                                <th>Nomination Form - GBS</th>
                                <td>
                                    <a href="{{route('lower-court.prints.form-g',$application->id)}}" target="_blank"
                                        class="btn btn-primary btn-sm"><i class="fas fa-print mr-1"></i>Print</a>
                                </td>
                            </tr>
                            <tr>
                                <th>Form A - BF</th>
                                <td>
                                    <a href="{{route('lower-court.prints.form-e',$application->id)}}" target="_blank"
                                        class="btn btn-primary btn-sm"><i class="fas fa-print mr-1"></i>Print</a>
                                </td>
                            </tr>
                            <tr>
                                <th>Affidavit Form</th>
                                <td>
                                    <a href="{{route('lower-court.prints.form-affidavit',$application->id)}}"
                                        target="_blank" class="btn btn-primary btn-sm"><i
                                            class="fas fa-print mr-1"></i>Print</a>
                                </td>
                            </tr>
                            <tr>
                                <th>Undertaking Form</th>
                                <td>
                                    <a href="{{route('lower-court.prints.form-undertaking',$application->id)}}"
                                        target="_blank" class="btn btn-primary btn-sm"><i
                                            class="fas fa-print mr-1"></i>Print</a>
                                </td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>
                                    @include('admin.lower-court.partials.address')
                                </td>
                            </tr>

                            @if (getAdvocateCertificateStatus($application))
                            <tr>
                                <th>Advocate Certificate</th>
                                <td>
                                    <a href="{{route('lower-court.prints.advocate-certificate',$application->id)}}"
                                        target="_blank" class="btn btn-primary btn-sm"><i
                                            class="fas fa-print mr-1"></i>Print</a>
                                </td>
                            </tr>
                            @endif

                        </body>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>