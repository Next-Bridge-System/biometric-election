@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Activity Logs</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">

                    </li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            Activity Log
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="application-tab" data-toggle="tab" href="#application"
                                    role="tab" aria-controls="application" aria-selected="true">Application</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="payment-tab" data-toggle="tab" href="#payment" role="tab"
                                    aria-controls="payment" aria-selected="false">Payment</a>
                            </li>

                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="application" role="tabpanel"
                                aria-labelledby="application-tab">
                                <div class="container">
                                    @foreach ($dates as $item)
                                    @if ($applicationActivities->where('activity_at',$item)->count())
                                    <fieldset class="border p-4 mb-4 col-12 mt-2">
                                        <legend class="w-auto">{{ $item }}</legend>
                                        @forelse($applicationActivities->where('activity_at',$item)->sortByDesc('created_at')
                                        as $activity)

                                        <div class="d-block p-2">
                                            Admins <strong>{{ getAdminName($activity->admin_id) }}</strong> {{
                                            $activity->is_created ? 'added' : 'made changes' }} in <br>
                                            @if ($activity->is_media)
                                            @foreach( json_decode($activity->log) as $key => $log)
                                            @if ($log != NULL)
                                            <strong>{{ getKeyTitle($key) }}</strong> = <a
                                                class="badge badge-btn badge-info"
                                                href="{{ asset('storage/app/public/'.$log) }}" target="_blank">View</a>
                                            <br>
                                            @else
                                            <strong>{{ getKeyTitle($key) }}</strong> = <div class="badge badge-danger">
                                                Deleted</div> <br>
                                            @endif
                                            @endforeach
                                            @else
                                            @foreach( json_decode($activity->log) as $key => $log)
                                            <strong>{{ getKeyTitle($key) }}</strong> = {!!
                                            getLogData($key,$log,$activity->application_id) !!} <br>
                                            @endforeach
                                            @endif
                                            at <strong>{{ date('h:i:s a',strtotime($activity->created_at)) }}</strong>
                                        </div>
                                        @empty
                                        <div class="container">
                                            No record Found
                                        </div>
                                        @endforelse
                                    </fieldset>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                            <div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-tab">
                                <div class="container">
                                    @foreach ($dates as $item)
                                    @if ($paymentActivities->where('activity_at',$item)->count())
                                    <fieldset class="border p-4 mb-4 col-12 mt-2">
                                        <legend class="w-auto">{{ $item }}</legend>
                                        @forelse($paymentActivities->where('activity_at',$item)->sortByDesc('created_at')
                                        as $activity)

                                        <div class="d-block p-2">
                                            Admins <strong>{{ getAdminName($activity->admin_id) }}</strong> {{
                                            $activity->is_created ? 'added' : 'made changes' }} in <br>
                                            @foreach( json_decode($activity->log) as $key => $log)
                                            <strong>{{ getKeyTitle($key) }}</strong> = {{
                                            getLogData($key,$log,$activity->application_id) }} <br>
                                            @endforeach at <strong>{{ date('h:i:s a',strtotime($activity->created_at))
                                                }}</strong>
                                        </div>
                                        @empty
                                        <div class="container">
                                            No record Found
                                        </div>
                                        @endforelse
                                    </fieldset>
                                    @endif
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->

@endsection

@section('scripts')
<script>

</script>
@endsection
