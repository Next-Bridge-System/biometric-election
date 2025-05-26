function validateStep(step) {
  if (step ===0) {
    const cnic = $('#cnic-scan').val().trim();
    const pattern = /^\d{4}-\d{7}-\d{1}$/;
    if (!pattern.test(cnic)) {
      $('#cnic-error').removeClass('d-none');
      return false;
    }
    $('#cnic-error').addClass('d-none');
  }

  // Step 2: Fingerprint validation (dummy check)
  else if (step === 2) {
const isFingerprintValid = true; // change this later to real scanner result

  if (!isFingerprintValid) {
    $('#fingerFailModal').modal('show');
    return false;
  }
    return true;
  }

  else if (step === 3) {
    // if (!$('input[name="vote"]:checked').val()) {
    //   $('#vote-error').removeClass('d-none');
    //   return false;
    // }
    // $('#vote-error').addClass('d-none');
  }

  return true;
}


