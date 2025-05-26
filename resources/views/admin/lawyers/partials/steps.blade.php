<div class="timeline-steps d-flex">
    <div class="steps-holder">
        <div class="steps-content {{(
                Route::currentRouteName() == 'lawyers.create-step-1' ||
                Route::currentRouteName() == 'lawyers.create-step-2' ||
                Route::currentRouteName() == 'lawyers.create-step-3' ||
                Route::currentRouteName() == 'lawyers.create-step-4' ||
                Route::currentRouteName() == 'lawyers.create-step-5' ||
                Route::currentRouteName() == 'lawyers.create-step-6' ||
                Route::currentRouteName() == 'lawyers.create-step-7' ||
                Route::currentRouteName() == 'lawyers.create-step-8'
                ) ? 'active' : ''}}">
            <h5>1</h5>
            <h6>Basic</h6>
        </div>
    </div>
    <div class="steps-holder">
        <div class="steps-content {{(
            Route::currentRouteName() == 'lawyers.create-step-2' ||
            Route::currentRouteName() == 'lawyers.create-step-3' ||
            Route::currentRouteName() == 'lawyers.create-step-4' ||
            Route::currentRouteName() == 'lawyers.create-step-5' ||
            Route::currentRouteName() == 'lawyers.create-step-6' ||
            Route::currentRouteName() == 'lawyers.create-step-7' ||
            Route::currentRouteName() == 'lawyers.create-step-8'
            ) ? 'active' : ''}}">
            <h5>2</h5>
            <h6>Personal</h6>
        </div>
    </div>
    <div class="steps-holder">
        <div class="steps-content {{(
            Route::currentRouteName() == 'lawyers.create-step-3' ||
            Route::currentRouteName() == 'lawyers.create-step-4' ||
            Route::currentRouteName() == 'lawyers.create-step-5' ||
            Route::currentRouteName() == 'lawyers.create-step-6' ||
            Route::currentRouteName() == 'lawyers.create-step-7' ||
            Route::currentRouteName() == 'lawyers.create-step-8'
            ) ? 'active' : ''}}">
            <h5>3</h5>
            <h6>Identification</h6>
        </div>
    </div>
    <div class="steps-holder">
        <div class="steps-content {{(
            Route::currentRouteName() == 'lawyers.create-step-4' ||
            Route::currentRouteName() == 'lawyers.create-step-5' ||
            Route::currentRouteName() == 'lawyers.create-step-6' ||
            Route::currentRouteName() == 'lawyers.create-step-7' ||
            Route::currentRouteName() == 'lawyers.create-step-8'
            ) ? 'active' : ''}}">
            <h5>4</h5>
            <h6>Address</h6>
        </div>
    </div>
    <div class="steps-holder">
        <div class="steps-content {{(
            Route::currentRouteName() == 'lawyers.create-step-5' ||
            Route::currentRouteName() == 'lawyers.create-step-6' ||
            Route::currentRouteName() == 'lawyers.create-step-7' ||
            Route::currentRouteName() == 'lawyers.create-step-8'
            ) ? 'active' : ''}}">
            <h5>5</h5>
            <h6>Academic Record</h6>
        </div>
    </div>
    <div class="steps-holder">
        <div class="steps-content {{(
            Route::currentRouteName() == 'lawyers.create-step-6' ||
            Route::currentRouteName() == 'lawyers.create-step-7' ||
            Route::currentRouteName() == 'lawyers.create-step-8'
            ) ? 'active' : ''}}">
            <h5>6</h5>
            <h6>Lawyer</h6>
        </div>
    </div>
    <div class="steps-holder">
        <div class="steps-content {{(
            Route::currentRouteName() == 'lawyers.create-step-7' ||
            Route::currentRouteName() == 'lawyers.create-step-8'
            ) ? 'active' : ''}}">
            <h5>7</h5>
            <h6>Upload Documents</h6>
        </div>
    </div>
    <div class="steps-holder">
        <div class="steps-content {{(Route::currentRouteName() == 'lawyers.create-step-8') ? 'active' : ''}}">
            <h5>8</h5>
            <h6>Final Confirmation</h6>
        </div>
    </div>
</div>
