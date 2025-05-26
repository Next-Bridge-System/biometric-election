class Stepper {
    constructor() {
        this.steps = document.querySelectorAll('.form-step');
        this.stepIndicators = document.querySelectorAll('.stepper .step');
        this.currentStep = 1;
        this.initEvents();
        this.updateStepper();
    }

    updateStepper() {
        // Hide all steps
        this.steps.forEach(step => step.classList.add('d-none'));

        // Show only current step
        const currentElement = document.querySelector(`.form-step[data-step="${this.currentStep}"]`);
        if (currentElement) {
            currentElement.classList.remove('d-none');
        } else {
            console.warn(`Step ${this.currentStep} not found in DOM`);
        }

        // Update stepper indicators
        this.stepIndicators.forEach((indicator, index) => {
            indicator.classList.remove('active');
            if (index < this.currentStep) {
                indicator.classList.add('active');
            }
        });

        // Control camera
        if (this.currentStep === 1) {
            if (typeof initCamera === "function") initCamera();
        } else {
            if (typeof stopCamera === "function") stopCamera();
        }
    }

    nextStep() {
        // Validate Step 1: image must be captured
        if (this.currentStep === 1) {

            if (webcamImagePath != null && webcamImagePath != '') {
                document.getElementById('camera-error').classList.add('d-none');
            } else {
                const canvas = document.getElementById('capture-canvas');
                if (canvas.classList.contains('d-none')) {
                    document.getElementById('camera-error').classList.remove('d-none');
                    return;
                } else {
                    document.getElementById('camera-error').classList.add('d-none');
                }
            }
        }

        // Validate Step 2: finger must be selected and verified
        if (this.currentStep === 2) {
            const selected = document.querySelector('.finger.selected');
            if (!selected) {
                document.getElementById('finger-error').classList.remove('d-none');
                return;
            } else {
                document.getElementById('finger-error').classList.add('d-none');
            }

            if (fingerVerification === false) {
                return;
            }
        }

        this.currentStep++;
        this.updateStepper();
    }

    prevStep() {
        if (this.currentStep > 1) {
            this.currentStep--;
            this.updateStepper();
        }
    }

    goToStep(step) {

        if (step == 3) {

            $.ajax({
                method: "POST",
                url: getBiometricCountUrl,
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    'user_id': userID,
                },
                success: function (response) {
                    swal.fire({
                        title: 'Biometric Registered',
                        text: 'Biometric data has been successfully registered.',
                        icon: 'success',
                    });
                },
            });
        }

        const stepNumber = parseInt(step);
        if (!isNaN(stepNumber) && stepNumber >= 1 && stepNumber <= this.steps.length) {
            this.currentStep = stepNumber;
            this.updateStepper();
        } else {
            console.warn(`Invalid step number: ${step}`);
        }
    }

    initEvents() {
        // Next buttons
        document.querySelectorAll('.next-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                this.nextStep();
            });
        });

        // Finger buttons
        document.querySelectorAll('.finger').forEach(btn => {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.finger').forEach(f => f.classList.remove('selected'));
                this.classList.add('selected');

                const fingerName = this.getAttribute('title');
                document.getElementById('selected-finger').textContent = `Selected: ${fingerName}`;
                document.getElementById('finger-error').classList.add('d-none');
            });
        });
    }
}

// Make globally accessible
window.Stepper = Stepper;

// Initialize only after DOM is fully ready
document.addEventListener("DOMContentLoaded", function () {
    window.stepper = new Stepper();

    // Example: force go to step 3 (use only if you want to auto show it)
    // window.stepper.goToStep(3);
});
