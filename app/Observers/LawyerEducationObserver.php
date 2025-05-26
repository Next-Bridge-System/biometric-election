<?php

namespace App\Observers;

use App\LawyerEducation;
use Illuminate\Support\Facades\Log;

class LawyerEducationObserver
{
    /**
     * Handle the lawyer education "created" event.
     *
     * @param \App\LawyerEducation $lawyerEducation
     * @return void
     */
    public function created(LawyerEducation $lawyerEducation)
    {
        try {
            \Log::info(request()->route()->uri);
            if (str_contains(request()->route()->uri, 'lower-court')) {
                if ($lawyerEducation->lower_court_id != null) {
                    \Log::info('created');
                    $log = new \App\ActivityLog();
                    $changes = $lawyerEducation;
                    $log->is_created = true;
                    $log->log = json_encode($changes);
                    $log->user_id = $lawyerEducation->lowerCourt->user_id;
                    $log->type_id = $lawyerEducation->lower_court_id;
                    $log->type = 'LowerCourt';
                    $log->admin_id = \Auth::guard('admin')->user()->id ?? NULL;
                    $log->activity_at = \Carbon\Carbon::now()->format('Y-m-d');
                    $log->save();
                    return true;

                }
            }
        } catch (\Throwable $e) {
            Log::info($e);
        }
    }

    /**
     * Handle the lawyer education "updated" event.
     *
     * @param \App\LawyerEducation $lawyerEducation
     * @return void
     */
    public function updated(LawyerEducation $lawyerEducation)
    {
        try {
            \Log::info(request()->route()->uri);
            if (str_contains(request()->route()->uri, 'lower-court')) {
                if ($lawyerEducation->lower_court_id != null) {
                    \Log::info('updated');
                    $log = new \App\ActivityLog();
                    if (!$lawyerEducation->wasRecentlyCreated) {
                        $changes = $lawyerEducation->getChanges();
                    } else {
                        $changes = $lawyerEducation->getOriginal();
                        $log->is_created = true;
                    }
                    unset($changes['updated_at']);
                    if (empty($changes)) {
                        return true;
                    }
                    $log->log = json_encode($changes);
                    $log->user_id = $lawyerEducation->lowerCourt->user_id;
                    $log->type_id = $lawyerEducation->lower_court_id;
                    $log->type = 'LowerCourt';
                    $log->admin_id = \Auth::guard('admin')->user()->id ?? NULL;
                    $log->activity_at = \Carbon\Carbon::now()->format('Y-m-d');
                    $log->save();
                    return true;
                }
            }
        } catch (\Throwable $e) {
            Log::info($e);
        }
    }

    /**
     * Handle the lawyer education "deleted" event.
     *
     * @param \App\LawyerEducation $lawyerEducation
     * @return void
     */
    public function deleted(LawyerEducation $lawyerEducation)
    {
        //
    }

    /**
     * Handle the lawyer education "restored" event.
     *
     * @param \App\LawyerEducation $lawyerEducation
     * @return void
     */
    public function restored(LawyerEducation $lawyerEducation)
    {
        //
    }

    /**
     * Handle the lawyer education "force deleted" event.
     *
     * @param \App\LawyerEducation $lawyerEducation
     * @return void
     */
    public function forceDeleted(LawyerEducation $lawyerEducation)
    {
        //
    }
}
