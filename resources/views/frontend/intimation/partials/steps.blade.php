<div class="timeline-steps d-flex">
    <div class="steps-holder">
        <div class="steps-content {{(
                Route::currentRouteName() == 'frontend.intimation.create-step-1' ||
                Route::currentRouteName() == 'frontend.intimation.create-step-2' ||
                Route::currentRouteName() == 'frontend.intimation.create-step-3' ||
                Route::currentRouteName() == 'frontend.intimation.create-step-4' ||
                Route::currentRouteName() == 'frontend.intimation.create-step-5' ||
                Route::currentRouteName() == 'frontend.intimation.create-step-6' ||
                Route::currentRouteName() == 'frontend.intimation.create-step-7'
                ) ? 'active' : ''}}">
            <h5>1</h5>
            <h6>Personal</h6>
        </div>
    </div>
    <div class="steps-holder">
        <div class="steps-content {{(
            Route::currentRouteName() == 'frontend.intimation.create-step-2' ||
            Route::currentRouteName() == 'frontend.intimation.create-step-3' ||
            Route::currentRouteName() == 'frontend.intimation.create-step-4' ||
            Route::currentRouteName() == 'frontend.intimation.create-step-5' ||
            Route::currentRouteName() == 'frontend.intimation.create-step-6' ||
            Route::currentRouteName() == 'frontend.intimation.create-step-7'
            ) ? 'active' : ''}}">
            <h5>2</h5>
            <h6>Identification</h6>
        </div>
    </div>
    <div class="steps-holder">
        <div class="steps-content {{(
            Route::currentRouteName() == 'frontend.intimation.create-step-3' ||
            Route::currentRouteName() == 'frontend.intimation.create-step-4' ||
            Route::currentRouteName() == 'frontend.intimation.create-step-5' ||
            Route::currentRouteName() == 'frontend.intimation.create-step-6' ||
            Route::currentRouteName() == 'frontend.intimation.create-step-7'
            ) ? 'active' : ''}}">
            <h5>3</h5>
            <h6>Address</h6>
        </div>
    </div>
    <div class="steps-holder">
        <div class="steps-content {{(
            Route::currentRouteName() == 'frontend.intimation.create-step-4' ||
            Route::currentRouteName() == 'frontend.intimation.create-step-5' ||
            Route::currentRouteName() == 'frontend.intimation.create-step-6' ||
            Route::currentRouteName() == 'frontend.intimation.create-step-7'
            ) ? 'active' : ''}}">
            <h5>4</h5>
            <h6>Academic Record</h6>
        </div>
    </div>
    <div class="steps-holder">
        <div class="steps-content {{(
            Route::currentRouteName() == 'frontend.intimation.create-step-5' ||
            Route::currentRouteName() == 'frontend.intimation.create-step-6' ||
            Route::currentRouteName() == 'frontend.intimation.create-step-7'
            ) ? 'active' : ''}}">
            <h5>5</h5>
            <h6>Senior Lawyer</h6>
        </div>
    </div>
    <div class="steps-holder">
        <div class="steps-content {{(
            Route::currentRouteName() == 'frontend.intimation.create-step-6' ||
            Route::currentRouteName() == 'frontend.intimation.create-step-7'
            ) ? 'active' : ''}}">
            <h5>6</h5>
            <h6>Final Confirmation</h6>
        </div>
    </div>
    <div class="steps-holder">
        <div class="steps-content {{(
            Route::currentRouteName() == 'frontend.intimation.create-step-7'
            ) ? 'active' : ''}}">
            <h5>7</h5>
            <h6>Payment</h6>
        </div>
    </div>
</div>
