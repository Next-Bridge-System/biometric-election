<?php

use App\Http\Controllers\Frontend\ElectionController;
use Illuminate\Support\Facades\Route;

route::get('/election', [ElectionController::class, 'index'])->name('frontend.election.index');
route::post('/submitVote', [ElectionController::class, 'submitVote'])->name('frontend.election.submitVote');
