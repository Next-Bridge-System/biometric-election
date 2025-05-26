<?php

namespace App\Providers;

use App\LawyerAddress;
use App\LawyerEducation;
use App\LawyerUpload;
use App\LowerCourt;
use App\Observers\LawyerAddressObserver;
use App\Observers\LawyerEducationObserver;
use App\Observers\LawyerUploadObserver;
use App\Observers\LowerCourtObserver;
use App\Observers\PaymentObserver;
use App\Payment;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        LowerCourt::observe(LowerCourtObserver::class);
        LawyerAddress::observe(LawyerAddressObserver::class);
        LawyerEducation::observe(LawyerEducationObserver::class);
        LawyerUpload::observe(LawyerUploadObserver::class);
        Payment::observe(PaymentObserver::class);
    }
}
