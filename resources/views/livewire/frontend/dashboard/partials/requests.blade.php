@if (($user->lc && $user->lc->license_no_lc && getLcPaymentStatus($user->lc->id)['name'] == 'Paid') ||
$user->gc_status == 'approved')
<div class="col-md-6 offset-md-3">
    <div class="row">
        <div class="col">
            <div class="card card-success">
                <div class="card-header">
                    <span class="text-bold">Lawyer Requests</span>
                </div>
                <div class="card-body">
                    <a href="{{route('frontend.lawyer-requests.create')}}" class="btn btn-outline-dark">
                        <i class="fas fa-plus mr-1"></i>New Request</a>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card card-primary">
                <div class="card-header">
                    <span class="text-bold">My Requests</span>
                </div>
                <div class="card-body">
                    <a href="{{route('frontend.lawyer-requests.index')}}" class="btn btn-outline-dark">
                        <i class="fas fa-copy mr-1"></i>
                        @php
                        echo App\LawyerRequest::where('user_id',Auth::guard('frontend')->id())->count();
                        @endphp
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif