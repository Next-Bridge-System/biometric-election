;(function() {
  const emailRegex = /^\S+@\S+\.\S+$/;
  const cnicRegex  = /^\d{4}-\d{7}-\d$/;

  // define global validator
  window.validateStep = function(idx) {
    let valid = true;

    // Step 1: email & password
    if (idx === 0) {
      const email    = document.getElementById('email');
      const password = document.getElementById('password');

      // clear previous error states
      [email, password].forEach(i => {
        i.classList.remove('is-invalid');
      });

      if (!emailRegex.test(email.value.trim())) {
        email.classList.add('is-invalid');
        valid = false;
      }
      if (password.value.length < 6) {
        password.classList.add('is-invalid');
        valid = false;
      }
    }

     // --- STEP 1: Scan Your QR Code (CNIC) ---
    if (idx === 1) {
      const cnicInput = document.getElementById('cnic-scan');
      cnicInput.classList.remove('is-invalid');

      if (!cnicRegex.test(cnicInput.value.trim())) {
        cnicInput.classList.add('is-invalid');
        valid = false;
      }
    }

  if (idx === 2) {
     const err = document.getElementById('camera-error');
 err.classList.add('d-none');

        if (!window.cameraImageData) {
          err.classList.remove('d-none');

        
          valid = false;

      }
  }
if (idx === 3) {
    const sel = window.getSelectedFinger();
    console.log(sel)
    if (!sel) {
        console.log("error")
      $('#finger-error').removeClass('d-none');
      valid = false;
    }
   
  }
  
    return valid;
  };
})();
