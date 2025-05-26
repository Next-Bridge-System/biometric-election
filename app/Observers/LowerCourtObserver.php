<?php

namespace App\Observers;

use App\LowerCourt;
use Illuminate\Support\Facades\Log;

class LowerCourtObserver
{
    /**
     * Handle the lower court "created" event.
     *
     * @param \App\LowerCourt $lowerCourt
     * @return void
     */
    public function created(LowerCourt $lowerCourt)
    {
        try {
            \Log::info('created');
            $log = new \App\ActivityLog();
            $changes = $lowerCourt;
            $log->is_created = true;
            $log->log = json_encode($changes);
            $log->user_id = $lowerCourt->user_id;
            $log->type_id = $lowerCourt->id;
            $log->type = 'LowerCourt';
            $log->admin_id = \Auth::guard('admin')->user()->id ?? NULL;
            $log->activity_at = \Carbon\Carbon::now()->format('Y-m-d');
            $log->save();
            return true;
        } catch (\Throwable $e) {
            Log::info($e);
        }
    }

    /**
     * Handle the lower court "updated" event.
     *
     * @param \App\LowerCourt $lowerCourt
     * @return void
     */
    public function updated(LowerCourt $lowerCourt)
    {
        try {
            \Log::info('updated');
            $log = new \App\ActivityLog();
            if (!$lowerCourt->wasRecentlyCreated) {
                $changes = $lowerCourt->getChanges();
            } else {
                $changes = $lowerCourt->getOriginal();
                $log->is_created = true;
            }
            unset($changes['updated_at']);
            if (empty($changes)) {
                return true;
            }
            $log->log = json_encode($changes);
            $log->user_id = $lowerCourt->user_id;
            $log->type_id = $lowerCourt->id;
            $log->type = 'LowerCourt';
            $log->admin_id = \Auth::guard('admin')->user()->id ?? NULL;
            $log->activity_at = \Carbon\Carbon::now()->format('Y-m-d');
            $log->save();
            return true;
        } catch (\Throwable $e) {
            Log::info($e);
        }
        //
    }

    /**
     * Handle the lower court "deleted" event.
     *
     * @param \App\LowerCourt $lowerCourt
     * @return void
     */
    public function deleted(LowerCourt $lowerCourt)
    {
        //
    }

    /**
     * Handle the lower court "restored" event.
     *
     * @param \App\LowerCourt $lowerCourt
     * @return void
     */
    public function restored(LowerCourt $lowerCourt)
    {
        //
    }

    /**
     * Handle the lower court "force deleted" event.
     *
     * @param \App\LowerCourt $lowerCourt
     * @return void
     */
    public function forceDeleted(LowerCourt $lowerCourt)
    {
        //
    }
}
