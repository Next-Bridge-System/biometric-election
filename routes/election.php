<?php

use App\Http\Controllers\Frontend\ElectionController;
use App\Http\Controllers\Frontend\VoteController;
use Illuminate\Support\Facades\Route;

route::get('/bes/elections', [ElectionController::class, 'index'])->name('frontend.election.index');
route::post('/submitVote', [VoteController::class, 'store'])->name('frontend.election.submitVote');
route::post('/fetchSavedFingerTemplates', [ElectionController::class, 'fetchSavedFingerTemplates'])->name('frontend.election.fetchSavedFingerTemplates');
route::post('/fetchVerifiedVoterData', [ElectionController::class, 'fetchVerifiedVoterData'])->name('frontend.election.fetchVerifiedVoterData');
