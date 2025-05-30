@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Election Details</h1>
                </div>
            </div>
        </div><!-- /.container -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Election Details</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <p>Election Name: <strong>{{ $election->title_english }}</strong></p>
                                    <p class="mb-0">Total Votes: <strong>{{ $election->votes_count }}</strong></p>
                                    <p class="mb-0">Total Vote Casted: <strong>{{ $election->vote_casted_count }}</strong>
                                    </p>
                                    <p>Total Vote Not Casted: <strong>{{ $election->vote_not_casted_count }}</strong></p>
                                </div>
                            </div>
                            @if ($votes->count() > 0)
                                @foreach ($votes as $seatId => $seatVotes)
                                    <div class="mb-4">
                                        @php
                                            $seat = $seatVotes->first()->seat;
                                            $candidateVotes = $seatVotes
                                                ->whereNotNull('candidate_id')
                                                ->groupBy('candidate_id');
                                        @endphp

                                        <h4>{{ $seat->name_english }} ({{ $seat->name_urdu }}) (Vote Casted:
                                            <strong>{{ $seatVotes->whereNotNull('candidate_id')->count() }}</strong>) (Vote
                                            Not Casted:
                                            <strong>{{ $seatVotes->whereNull('candidate_id')->count() }}</strong>)
                                        </h4>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Candidate</th>
                                                    <th>Vote Count</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($candidateVotes as $candidateId => $votesGroup)
                                                    @php
                                                        $candidateName =
                                                            $votesGroup->first()->candidate->name_english ?? 'Unknown';
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $candidateName }}</td>
                                                        <td><strong>{{ $votesGroup->count() }}</strong></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endforeach
                            @else
                                <p>No votes casted yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
