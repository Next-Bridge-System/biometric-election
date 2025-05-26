<div class="text-center">
    <div class="col-md-6 offset-md-3">
        @if (Auth::guard('frontend')->check() && Auth::guard('frontend')->user()->register_as == 'intimation')
        <a href="{{route('frontend.intimation.create')}}"
            class="btn btn-success my-2 {{(Route::currentRouteName() == 'frontend.intimation.create-step-1' || Route::currentRouteName() == 'frontend.intimation.create-step-2' || Route::currentRouteName() == 'frontend.intimation.create-step-3' || Route::currentRouteName() == 'frontend.intimation.create-step-4' || Route::currentRouteName() == 'frontend.intimation.create-step-5' || Route::currentRouteName() == 'frontend.intimation.create-step-6' || Route::currentRouteName() == 'frontend.intimation.create-step-7') ? 'active' : ''}}">
            Intimation Application
        </a>
        @endif

        @if (Auth::guard('frontend')->check() && Auth::guard('frontend')->user()->register_as == 'lc')
        <a href="{{route('frontend.lower-court.create-step-1')}}" class="btn btn-success my-2">
            Lower Court Application
        </a>
        @endif

        @if (!Auth::guard('frontend')->check())
        <a href="{{route('frontend.register','intimation')}}" class="btn btn-success my-2">
            Intimation Register
        </a>
        <a href="{{route('frontend.register','lc')}}" class="btn btn-success my-2">
            Lower Court Register
        </a>
        @endif

        <a href="{{route('frontend.search-application')}}"
            class="btn btn-success my-2 {{(Route::currentRouteName() == 'frontend.search-application') ? 'active' : ''}}">Search
            Applications</a>

        {{-- <a href="{{route('frontend.renewal-lower-court')}}"
        class="btn btn-success my-2
        {{(Route::currentRouteName() == 'frontend.renewal-lower-court' || Route::currentRouteName() == 'frontend.view-renewal-lower-court') ? 'active' : ''}}">Renewal
        Lower Court</a> --}}
        {{-- <a href="{{route('frontend.renewal-high-court')}}"
        class="btn btn-success my-2
        {{(Route::currentRouteName() == 'frontend.renewal-high-court' || Route::currentRouteName() == 'frontend.view-renewal-high-court') ? 'active' : ''}}">Renewal
        High Court</a> --}}
        {{-- <a href="{{route('frontend.complaints')}}"
        class="btn btn-success my-2 {{(Route::currentRouteName() == 'frontend.complaints') ? 'active' : ''}}">Complaint
        Management</a> --}}
        {{-- <a href="{{route('frontend.vouchers.create')}}"
        class="btn btn-success my-2
        {{(Route::currentRouteName() == 'frontend.vouchers.create') ? 'active' : ''}}">Vouchers</a>
        --}}

        {{-- <a href="{{route('frontend.certificate.create-visa')}}"
        class="btn btn-success my-2
        {{(Route::currentRouteName() == 'frontend.certificate.create-visa') ? 'active' : ''}}">Visa
        Certificates</a> --}}

        {{-- <a href="{{route('frontend.certificate.create-character')}}"
        class="btn btn-success my-2
        {{(Route::currentRouteName() == 'frontend.certificate.create-character') ? 'active' : ''}}">Characters
        Certificates</a> --}}
    </div>
    <div class="col-md-4 offset-md-4">
        <a href="{{route('verifications.create')}}" class="btn btn-primary btn-block
                                {{(Route::currentRouteName() == 'frontend.certificate.create') ? 'active' : ''}}">Data
            Verification</a>
    </div>
    {{-- <div>
        <a href="{{route('frontend.certificate.create')}}" class="btn btn-success
    {{(Route::currentRouteName() == 'frontend.certificate.create') ? 'active' : ''}}">Lawyer
    Certificates</a>

    <a href="{{route('frontend.certificate.search')}}" class="btn btn-success
                    {{(Route::currentRouteName() == 'frontend.certificate.create') ? 'active' : ''}}">Search
        Certificates</a>
</div> --}}


</div>

{{-- <div class="text-center">
    <iframe width="560" height="315" src="https://www.youtube.com/embed/qC3Wvjd6cic" title="YouTube video player"
        frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
        allowfullscreen></iframe>
</div> --}}