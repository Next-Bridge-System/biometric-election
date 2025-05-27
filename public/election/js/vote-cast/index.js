
$(document).ready(function () {

  // $('#cnic-scan').val('1234-1234567-1');


  let returnedVotingData;

  let current = 0;
  const steps = $('.vote-step');


  function showStep(index) {
    steps.addClass('d-none');
    steps.eq(index).removeClass('d-none');
  }

  function collectData(step) {
    if (step === 0) {
      formData.cnic = $('#cnic-scan').val().trim();
    }
  }


  $('.next-btn').on('click', function () {

    if (!validateStep(current)) return;

    collectData(current);

    if (current === 2) {

      returnedVotingData = renderCategoryVoteTable(votingData);

    }

    current++;
    showStep(current);
  });


  $('.back-btn').on('click', function () {
    if (current > 0) {
      current--;
      showStep(current);
    }
  });


  $('#submit-multi-vote').off('click').on('click', () => {
    if (!returnedVotingData) {
      console.error('votingData not set');
      return;
    }

    const missing = returnedVotingData.filter(entry => !formData.multiVotes[entry.category]);
    if (missing.length > 0) {
      alert(`Please select a candidate for: ${missing.map(e => e.category).join(', ')}`);
      return;
    }

    //  renderFinalReviewTable(returnedVotingData);
    showStep(5)
  });

  /* final submission */
  $('#submit-final').on('click', () => {
    console.log('Form Data:', formData);
    showStep(6);                                // next step / backend submit
  });

});
