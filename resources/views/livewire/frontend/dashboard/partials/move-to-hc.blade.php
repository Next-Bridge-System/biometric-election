@if (getLowerCourtDuration($user->id) >= 2)
<section>
    <div class="jumbotron">
        <h1 class="display-6">Eligible to High Court</h1>
        <p class="lead">The two-year duration for the lower court application has been completed, indicating readiness
            to proceed with the high court application. Please click the button below to initiate the process.</p>
        <hr class="my-4">

        @if (validateLowerCourt($user->id) == [])
        <a class="btn btn-success" href="{{route('frontend.high-court.move-to-hc',$user->id)}}" role="button">Move To
            High Court</a>
        @else

        <p>
            Please address all the reasons and objections in order to proceed with the process of transitioning the
            lower court application to a high court application. In case of any confusion, contact the Punjab Bar
            Council for further clarification.
        </p>

        <ol>
            @foreach (validateLowerCourt($user->id) as $item)
            <li>{{$item}}</li>
            @endforeach
        </ol>

        <button type="button" class="btn btn-primary" disabled>Move To High Court</button>

        @endif
    </div>
</section>
@endif