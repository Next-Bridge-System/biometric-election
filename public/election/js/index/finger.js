// js/index/finger.js

window.addEventListener('load', function() {
  let selected = null;

  // enable Bootstrap tooltips
  var tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-toggle="tooltip"]')
  );
  tooltipTriggerList.forEach(function(el) {
    new bootstrap.Tooltip(el);
  });

  // on click, only one finger can be selected
  document.querySelectorAll('.finger').forEach(function(btn) {
    btn.addEventListener('click', function() {
      // clear previous
      document.querySelectorAll('.finger').forEach(function(f) {
        f.classList.remove('selected', 'animate_finger');
      });
      
      // mark this one
      btn.classList.add('selected', 'animate_finger');
      selected = btn.getAttribute('data-original-title');
      console.log(selected)
      // show name and hide any previous error
      document.getElementById('selected-finger').textContent = selected;
      document.getElementById('finger-error').classList.add('d-none');
    });
  });

  // expose getter
  window.getSelectedFinger = function() {
    return selected;
  };
});
