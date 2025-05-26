document.addEventListener('DOMContentLoaded', () => {
  const steps       = Array.from(document.querySelectorAll('.form-step'));
  const stepCircles = Array.from(document.querySelectorAll('.stepper .step'));
  const state       = {};
  let current = 0;
// document.getElementById('email').value     = 'dev@example.com';
// document.getElementById('password').value     = '12312312';

// document.getElementById('cnic-scan').value = '1234-1234567-1';

  function showStep(idx) {
     const $all    = $('.form-step');
  const $curr   = $all.filter(':visible');
  const $target = $all.eq(idx);
     // hide all steps
    steps.forEach(s => s.classList.add('d-none'));
    // show current
    steps[idx].classList.remove('d-none');
 
      $target
        .css({ position: 'relative', left: '550px', opacity: 0 })
        .show()
        .animate({ left: 0, opacity: 1 }, 1000);

 
    
    // update stepper bullets
    stepCircles.forEach((c, i) => {
      c.classList.toggle('active', i <= idx);
    });
    stepCircles.forEach((c, i) => {
     // active background for all <= current
     c.classList.toggle('active', i <= idx);
     // swap number for a check icon for completed steps
     if (i < idx) {
       c.innerHTML = '&#10003;';    // ✓
     } else {
       c.innerHTML = (i + 1).toString();
     }
   });
 if (idx === 2) {
    window.initCamera && window.initCamera();
  } else {
    // any other step: shut it down
    window.stopCamera && window.stopCamera();
  }
   if (idx === steps.length -1) {
   document.getElementById('out-email').textContent = state.email || '';
   document.getElementById('out-cnic').textContent  = state.qr  || '';
}
  }

  // Next buttons
  document.querySelectorAll('.next-btn').forEach(btn =>
    btn.addEventListener('click', e => {
      e.preventDefault();
      // validate current step
      if (!window.validateStep(current)) return;

      // collect data for this step
      if (current === 0) {
        state.email    = document.getElementById('email').value.trim();
        state.password = document.getElementById('password').value;
      }
      if (current === 1) {
        state.qr = document.getElementById('cnic-scan').value.trim();
      }
      if (current === 2) {
       state.photo = window.cameraImageData || '';
      }
if (current === 3) {
     state.finger = window.getSelectedFinger();
   }
   if (current === 4) {
     state.finger = window.getSelectedFinger();
   }
      // move on
      if (current < steps.length - 1) {
        current++;
        showStep(current);
      }
    })
  );

  // Back buttons
  document.querySelectorAll('.prev-btn').forEach(btn =>
    btn.addEventListener('click', e => {
      e.preventDefault();
      if (current > 0) {
        current--;
        showStep(current);
      }
    })
  );
  
  
    // Finish button
  document.getElementById('finish-btn').addEventListener('click', e => {
    if (window.validateStep(3)) {
    e.preventDefault();
    alert('All data submitted:\n' + JSON.stringify(state, null, 2));
      }
  });

  

  // expose for validation script
  window.showStep = showStep;

  // kick it off
  showStep(0);
});
