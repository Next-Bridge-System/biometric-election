<span class="text-uppercase">Dashboard - {{$user->register_as}} #{{$user->id}}</span>


@if (resetDashboard($user->id,$register_as) == 1)
<section>
    @if (!$user->register_as ||
    ($user->register_as == 'lc' && !$user->lc || $user->register_as == 'intimation' &&
    !$user->intimation) || ($user->partialLc || $user->partialIntimation))
    <button
        onclick="confirmation('reset-dashboard','{{$user->id}}',null, 'By resetting your dashboard, all data that you have previously entered will be deleted and your dashboard will be restored to its default settings.')"
        class="btn btn-danger btn-sm float-right">Reset Dashboard</button>
    @endif
</section>
@endif