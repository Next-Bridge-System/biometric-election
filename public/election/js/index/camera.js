/**
 * camera.js
 * Streams webcam into <video> only when initCamera() is called.
 */

(function() {
  const video      = document.getElementById('camera-stream');
  const canvas     = document.getElementById('capture-canvas');
  const captureBtn = document.getElementById('capture-btn');
  const retakeBtn  = document.getElementById('retake-btn');
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

  // capture / retake logic remains unchanged
  captureBtn.addEventListener('click', () => {
    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
    window.cameraImageData = canvas.toDataURL('image/png');
    video.classList.add('d-none');
    canvas.classList.remove('d-none');
    captureBtn.classList.add('d-none');
    retakeBtn.classList.remove('d-none');
    errorLabel.classList.add('d-none');
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
