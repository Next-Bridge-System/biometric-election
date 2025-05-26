<?php

namespace App\Observers;

use App\LawyerAddress;
use Illuminate\Support\Facades\Log;

class LawyerAddressObserver
{
    /**
     * Handle the lawyer address "created" event.
     *
     * @param \App\LawyerAddress $lawyerAddress
     * @return void
     */
    public function created(LawyerAddress $lawyerAddress)
    {
        //
        try{
            if ($lawyerAddress->lower_court_id != null && str_contains(request()->route()->uri, 'lower-court')) {
            \Log::info('created');
            $log = new \App\ActivityLog();
            $changes = $lawyerAddress;
            $log->is_created = true;
            $log->log = json_encode($changes);
            $log->user_id = $lawyerAddress->lowerCourt->user_id;
            $log->type_id = $lawyerAddress->lower_court_id;
            $log->type = 'LowerCourt';
            $log->admin_id = \Auth::guard('admin')->user()->id ?? NULL;
            $log->activity_at = \Carbon\Carbon::now()->format('Y-m-d');
            $log->save();
            return true;
        }
        }catch(\Throwable $e){
            Log::info($e);
        }
    }

    /**
     * Handle the lawyer address "updated" event.
     *
     * @param \App\LawyerAddress $lawyerAddress
     * @return void
     */
    public function updated(LawyerAddress $lawyerAddress)
    {
        //
        try{
            if ($lawyerAddress->lower_court_id != null && str_contains(request()->route()->uri, 'lower-court')) {
            \Log::info('updated');
            $log = new \App\ActivityLog();
            if (!$lawyerAddress->wasRecentlyCreated) {
                $changes = $lawyerAddress->getChanges();
            } else {
                $changes = $lawyerAddress->getOriginal();
                $log->is_created = true;
            }
            unset($changes['updated_at']);
            if (empty($changes)) {
                return true;
            }
            $log->log = json_encode($changes);
            $log->user_id = $lawyerAddress->lowerCourt->user_id;
            $log->type_id = $lawyerAddress->lower_court_id;
            $log->type = 'LowerCourt';
            $log->admin_id = \Auth::guard('admin')->user()->id ?? NULL;
            $log->activity_at = \Carbon\Carbon::now()->format('Y-m-d');
            $log->save();
            return true;
        }
        }catch(\Throwable $e){
            Log::info($e);
        }
    }

    /**
     * Handle the lawyer address "deleted" event.
     *
     * @param \App\LawyerAddress $lawyerAddress
     * @return void
     */
    public function deleted(LawyerAddress $lawyerAddress)
    {
        //
    }

    /**
     * Handle the lawyer address "restored" event.
     *
     * @param \App\LawyerAddress $lawyerAddress
     * @return void
     */
    public function restored(LawyerAddress $lawyerAddress)
    {
        //
    }

    /**
     * Handle the lawyer address "force deleted" event.
     *
     * @param \App\LawyerAddress $lawyerAddress
     * @return void
     */
    public function forceDeleted(LawyerAddress $lawyerAddress)
    {
        //
    }
}
