<div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        @include('livewire.frontend.dashboard.partials.dashboard-header')
                    </div>

                    <div class="card-body">

                        @include('livewire.frontend.dashboard.partials.move-to-hc')
                        @include('livewire.frontend.dashboard.partials.report-print')
                        @include('livewire.frontend.dashboard.partials.requests')

                        @if ($user->gc_status == 'pending')
                        <div class="alert alert-warning text-center">
                            <b>The request for data verification has been submitted successfully. Please wait until the
                                Punjab Bar Council verifies and approves your record.</b>
                        </div>
                        @elseif ($user->gc_status == 'approved')
                        @elseif ($user->gc_status == 'disapproved')
                        <div class="alert alert-danger text-center">
                            <b>You are not verified.</b>
                        </div>
                        @endif


                        @if (!$user->register_as && !$gc_lawyer_type)
                        <section class="col-md-4 offset-md-4">
                            <h3 class="mb-4"><u><b>New Candidates/Lawyers</b></u></h3>
                            <div class="row">
                                <div class="form-group">
                                    <label for="">Application Type<span class="text-danger">*</span>
                                        <small>(only for new candidates)</small></label>
                                    <select wire:model="register_as" wire:change="changeApplicationType"
                                        class="form-control custom-select">
                                        <option value="">--Select Application--</option>
                                        <option value="intimation">
                                            Intimation/1<sup>st</sup> Intimation</option>
                                        <option value="lc">
                                            Lower Court/2<sup>nd</sup> Intimation</option>
                                    </select>
                                </div>
                            </div>
                        </section>
                        @endif

                        @if (in_array($user->register_as,['intimation','lc','hc']))
                        <section class="col-md-6 offset-md-3">
                            <div class="row">
                                @if ($user->register_as == 'intimation')
                                <div class="col">
                                    @if ($user->intimation && $user->intimation->is_accepted == 1)
                                    <a href="{{route('frontend.intimation.show',$user->intimation->id)}}">
                                        <div class="card bg-success mb-3 text-center">
                                            <div class="card-header">Intimation/1<sup>st</sup> Intimation</div>
                                            <div class="card-body">
                                                <h1 class="card-title"><i class="fas fa-copy mr-1 mr-1"></i></h1>
                                            </div>
                                        </div>
                                    </a>
                                    @else
                                    <a href="{{route('frontend.intimation.create')}}">
                                        <div class="card bg-success mb-3 text-center">
                                            <div class="card-header">Intimation/1<sup>st</sup> Intimation</div>
                                            <div class="card-body">
                                                <h1 class="card-title"><i class="fas fa-plus mr-1 mr-1"></i></h1>
                                            </div>
                                        </div>
                                    </a>
                                    @endif
                                </div>
                                @endif

                                @if ($user->register_as == 'lc')
                                <div class="col">
                                    <a href="{{route('frontend.lower-court.create-step-1')}}">
                                        <div class="card bg-success mb-3 text-center">
                                            <div class="card-header">Lower Court/2<sup>nd</sup> Intimation</div>
                                            <div class="card-body">
                                                <h1 class="card-title">
                                                    @if ($user->lc && $user->lc->is_final_submitted)
                                                    <i class="fas fa-copy mr-1 mr-1"></i>
                                                    @else
                                                    <i class="fas fa-plus mr-1 mr-1"></i>
                                                    @endif
                                                </h1>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endif

                                @if ($user->register_as == 'hc')
                                <div class="col">
                                    <a href="{{route('frontend.high-court.create-step-1')}}">
                                        <div class="card bg-success mb-3 text-center">
                                            <div class="card-header">High Court - Application</div>
                                            <div class="card-body">
                                                <h1 class="card-title">
                                                    @if ($user->hc && $user->hc->is_final_submitted)
                                                    <i class="fas fa-copy mr-1 mr-1"></i>
                                                    @else
                                                    <i class="fas fa-plus mr-1 mr-1"></i>
                                                    @endif
                                                </h1>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endif
                            </div>
                        </section>
                        @else

                        @include('livewire.frontend.dashboard.partials.data-verification')

                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('livewire.loader')
</div>