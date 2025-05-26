<?php

namespace App\Observers;

use App\LawyerUpload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LawyerUploadObserver
{
    /**
     * Handle the lawyer upload "created" event.
     *
     * @param \App\LawyerUpload $lawyerUpload
     * @return void
     */
    public function created(LawyerUpload $lawyerUpload)
    {

        try {
            if ($lawyerUpload->lower_court_id != null && str_contains(request()->route()->uri, 'lower-court')) {
                \Log::info('created');
                $log = new \App\ActivityLog();
                $changes = $lawyerUpload;
                $log->is_created = true;
                $log->log = json_encode($changes);
                $log->user_id = $lawyerUpload->user_id;
                $log->type_id = $lawyerUpload->lower_court_id;
                $log->type = 'LowerCourt';
                $log->admin_id = \Auth::guard('admin')->user()->id ?? NULL;
                $log->activity_at = \Carbon\Carbon::now()->format('Y-m-d');
                $log->is_media = true;
                $log->save();
                return true;
            }
        } catch (\Throwable $e) {
            Log::info($e);
        }
        //
    }

    /**
     * Handle the lawyer upload "updated" event.
     *
     * @param \App\LawyerUpload $lawyerUpload
     * @return void
     */
    public function updated(LawyerUpload $lawyerUpload)
    {
        try {
            if ($lawyerUpload->lower_court_id != null && str_contains(request()->route()->uri, 'lower-court')) {
                \Log::info('updated');
                $log = new \App\ActivityLog();
                if (!$lawyerUpload->wasRecentlyCreated) {
                    $changes = $lawyerUpload->getChanges();
                } else {
                    $changes = $lawyerUpload->getOriginal();
                    $log->is_created = true;
                }
                unset($changes['updated_at']);
                if (empty($changes)) {
                    return true;
                }
                $log->log = json_encode($changes);
                $log->user_id = $lawyerUpload->user_id;
                $log->type_id = $lawyerUpload->lower_court_id;
                $log->type = 'LowerCourt';
                $log->admin_id = \Auth::guard('admin')->user()->id ?? NULL;
                $log->activity_at = \Carbon\Carbon::now()->format('Y-m-d');
                $log->is_media = true;
                $log->save();
                return true;
            }
        } catch (\Throwable $e) {
            Log::info($e);
        }
        //
        //
    }

    /**
     * Handle the lawyer upload "deleted" event.
     *
     * @param \App\LawyerUpload $lawyerUpload
     * @return void
     */
    public function deleted(LawyerUpload $lawyerUpload)
    {
        try {
            if (str_contains(request()->route()->uri, 'lower-court')) {
                $log = new \App\ActivityLog();
                $record = array('Record' => $lawyerUpload);
                $log->log = json_encode($record);
                $log->user_id = $lawyerUpload->user_id ?? null;
                $log->type_id = $lawyerUpload->lower_court_id ?? null;
                $log->type = "LowerCourt";
                $log->admin_id = Auth::guard('admin')->user()->id ?? NULL;
                $log->activity_at = \Carbon\Carbon::now()->format('Y-m-d');
                $log->save();
                return true;
            }
        }catch(\Throwable $e){
            Log::info($e);
        }
    }

    /**
     * Handle the lawyer upload "restored" event.
     *
     * @param \App\LawyerUpload $lawyerUpload
     * @return void
     */
    public function restored(LawyerUpload $lawyerUpload)
    {
        //
    }

    /**
     * Handle the lawyer upload "force deleted" event.
     *
     * @param \App\LawyerUpload $lawyerUpload
     * @return void
     */
    public function forceDeleted(LawyerUpload $lawyerUpload)
    {
        //
    }
}
