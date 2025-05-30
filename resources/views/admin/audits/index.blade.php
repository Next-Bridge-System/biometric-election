@extends('layouts.admin')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{'Audit Report'}}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{url()->previous()}}" class="btn btn-dark">Back</a>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">{{'Audit Report'}}</h3>
                    </div>
                    <div class="card-body">
                        <!-- Single Date Filter Form -->
                        <form method="GET" action="{{ route('admins.audit', ['id' => request()->id ?? '']) }}">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="date">Select Date</label>
                                    <input type="date" name="date" id="date" class="form-control"
                                        value="{{ request('date') }}">
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </div>
                            </div>
                        </form>


                        <div class="table-responsive">
                            <table class="table table-bordered table-sm text-uppercase text-sm">
                                <thead>
                                    <tr class="bg-dark">
                                        <th>Sr #</th>
                                        <th>Date</th>
                                        <th>Admins</th>
                                        <th>Type</th>
                                        <th>Old Values</th>
                                        <th>New Values</th>
                                        <th>Event</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($audits as $audit)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$audit->created_at->format('F d, Y | h:i A')}}</td>
                                        <td>{{$audit->user->name ?? '-'}}</td>
                                        <td>
                                            {{$audit->auditable_type ?? '-'}} <br>
                                            {{$audit->auditable_id ?? '-'}} <br>
                                        </td>
                                        <td>
                                            <table class="table table-sm table-bordered table-striped">
                                                @foreach ($audit->old_values as $key => $item)
                                                <tr>
                                                    <th>{{clean($key)}}</th>
                                                    <td>{{$item ?? "-"}}</td>
                                                </tr>
                                                @endforeach
                                            </table>
                                        </td>
                                        <td>
                                            <table class="table table-sm table-bordered table-striped">
                                                @foreach ($audit->new_values as $key => $item)
                                                <tr>
                                                    <th>{{clean($key)}}</th>
                                                    <td>{{$item}}</td>
                                                </tr>
                                                @endforeach
                                            </table>
                                        </td>
                                        <td>{{$audit->event}}</td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-xs" data-toggle="modal"
                                                data-target="#audit_modal_{{$audit->id}}">Detail</button>

                                            <div class="modal fade" id="audit_modal_{{$audit->id}}" tabindex="-1"
                                                aria-labelledby="audit_modal_{{$audit->id}}Label" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="audit_modal_{{$audit->id}}Label">Audit Code:
                                                                #{{$audit->id}}
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <table class="table table-bordered table-striped">
                                                                <tr>
                                                                    <th class="w-25">URL</th>
                                                                    <td>{{$audit->url}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Agent</th>
                                                                    <td>{{$audit->user_agent}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>IP Address</th>
                                                                    <td>{{$audit->ip_address}}</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close</button>
                                                            <button type="button" class="btn btn-primary">Save
                                                                changes</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <th colspan="7">No Record Found.</th>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <div>
                                <div class="float-right">
                                    {{ $audits->appends(request()->query())->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection