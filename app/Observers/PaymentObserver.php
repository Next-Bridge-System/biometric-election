<?php

namespace App\Observers;

use App\Payment;
use Illuminate\Support\Facades\Log;

class PaymentObserver
{
    /**
     * Handle the payment "created" event.
     *
     * @param  \App\Payment  $payment
     * @return void
     */
    public function created(Payment $payment)
    {
        try {
            if($payment->lower_court_id != null){
            \Log::info('created');
            $log = new \App\ActivityLog();
            $changes = $payment;
            $log->is_created = true;
            $log->log = json_encode($changes);
            $log->user_id = $payment->lowerCourtApplication->user_id ?? null;
            $log->type_id = $payment->lower_court_id;
            $log->type = 'LC-Payment';
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
     * Handle the payment "updated" event.
     *
     * @param  \App\Payment  $payment
     * @return void
     */
    public function updated(Payment $payment)
    {
        try {
            if($payment->lower_court_id != null){
            \Log::info('updated');
            $log = new \App\ActivityLog();
            if (!$payment->wasRecentlyCreated) {
                $changes = $payment->getChanges();
            } else {
                $changes = $payment->getOriginal();
                $log->is_created = true;
            }
            unset($changes['updated_at']);
            if (empty($changes)) {
                return true;
            }
            $log->log = json_encode($changes);
            $log->user_id = $payment->lowerCourtApplication->user_id ?? null;
            $log->type_id = $payment->lower_court_id;
            $log->type = 'LC-Payment';
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
     * Handle the payment "deleted" event.
     *
     * @param  \App\Payment  $payment
     * @return void
     */
    public function deleted(Payment $payment)
    {
        //
    }

    /**
     * Handle the payment "restored" event.
     *
     * @param  \App\Payment  $payment
     * @return void
     */
    public function restored(Payment $payment)
    {
        //
    }

    /**
     * Handle the payment "force deleted" event.
     *
     * @param  \App\Payment  $payment
     * @return void
     */
    public function forceDeleted(Payment $payment)
    {
        //
    }
}
