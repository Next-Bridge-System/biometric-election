(function () {
  const video = document.getElementById('camera-stream');
  const canvas = document.getElementById('capture-canvas');
  const captureBtn = document.getElementById('capture-btn');
  const retakeBtn = document.getElementById('retake-btn');
  const errorLabel = document.getElementById('camera-error');
  let stream;

  function initCamera() {
    if (stream) return; // already running

    navigator.mediaDevices.getUserMedia({ video: true })
      .then(s => {
        stream = s;
        video.srcObject = s;
      })
      .catch(() => {
        errorLabel.textContent = 'Could not access camera.';
        errorLabel.classList.remove('d-none');
        captureBtn.disabled = true;
      });
  }
  // called to turn off the webcam when leaving step 3
  function stopCamera() {
    if (stream) {
      stream.getTracks().forEach(t => t.stop());
      stream = null;
    }
  }

  captureBtn.addEventListener('click', () => {
    // Draw the image on canvas
    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
    const imageData = canvas.toDataURL('image/png');

    // Get lawyerCNIC value (assumes an input with id 'lawyer-cnic' exists)
    const lawyerCNIC = document.getElementById('lawyer-cnic')?.value || '';

    // Switch views
    window.cameraImageData = imageData;
    video.classList.add('d-none');
    canvas.classList.remove('d-none');
    captureBtn.classList.add('d-none');
    retakeBtn.classList.remove('d-none');
    errorLabel.classList.add('d-none');

    // Send to Laravel
    fetch(cameraImageUploadURL, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({ user_id: userID, image: imageData })
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          //
        } else {
          swal.fire({
            title: 'Image Upload Failed',
            text: 'There was a problem uploading your image. Please try again or contact support if the issue persists.',
            icon: 'error',
          });
        }
      })
      .catch(err => {
        swal.fire({
          title: 'Image Upload Failed',
          text: 'There was a problem uploading your image. Please try again or contact support if the issue persists.',
          icon: 'error',
        });
      });
  });

  retakeBtn.addEventListener('click', () => {
    window.cameraImageData = null;
    video.classList.remove('d-none');
    canvas.classList.add('d-none');
    captureBtn.classList.remove('d-none');
    retakeBtn.classList.add('d-none');
    errorLabel.classList.add('d-none');
  });

  // export
  window.initCamera = initCamera;
  window.stopCamera = stopCamera;
})();
