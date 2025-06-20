function slugify(str) {
  return str.replace(/\s+/g, '-').toLowerCase();
}

function getLightColor(index) {
  const hue = (index * 47) % 360; // spread hues
  return `hsl(${hue}, 70%, 92%)`;  // light pastel tone
}

function renderCategoryVoteTable(data) {

  const MAX_CANDIDATES = Math.max(...data.map(entry => entry.candidates.length));
  const table = $('#category-vote-table tbody');
  table.empty();

  formData.votes = []; // reset global

  data.forEach((entry, rowIdx) => {
    const { category, urdu, candidates, id } = entry;
    const safeId = slugify(category);
    const rowColor = getLightColor(rowIdx);
    const tr = $(`<tr style="background-color: ${rowColor};"></tr>`);

    // First: selected candidate name
    const selectedTd = $(`<td id="selected-${safeId}-${id}" class="text-center font-weight-bold">---</td>`);
    tr.append(selectedTd);

    // Left blank padding to push candidates right
    const blanksNeeded = MAX_CANDIDATES - candidates.length;
    for (let i = 0; i < blanksNeeded; i++) tr.append('<td></td>');

    // Candidates
    candidates.forEach((candidate, i) => {
      const td = $(`
                    <td class="text-center selectable"
                        data-row="${safeId}-${id}"
                        data-seat-id="${id}"
                        data-candidate-id="${candidate.id}"
                        data-category="${category}">
                  </td>`);
            td.append(`
                      <b> ${candidate.image}</b><br>
                      <div class="mt-1">${candidate.name}</div>
                    `);
            tr.append(td);
    });

    // Last: category name
    tr.append(`<td class="bg-dark text-white font-weight-bold">${urdu}<br><small>${category}</small></td>`);
    table.append(tr);

    formData.votes.push({
        seat_id: id,
        seat_name: category,
        candidate_id: null,
        candidate_name: null,
    });
  });

  // Selection handler
  $('#category-vote-table')
    .off('click', 'td.selectable')
    .on('click', 'td.selectable', function () {

      const rowId = $(this).data('row');
      const candidateName = $(this).find('div').text();   // text under image
      const seatId = $(this).data('seat-id');
      const candidateId = $(this).data('candidate-id');
      const isSelected = $(this).hasClass('bg-success');

      if (isSelected) {
        // Unselect if already selected
        $(this).removeClass('bg-success text-white');
        $(`#selected-${rowId}`).text('');

        // Remove from votes array
        const existingVote = formData.votes.find(vote => vote.seat_id === seatId);
        if (existingVote) {
          existingVote.candidate_id = null;
          existingVote.candidate_name = null;
        }
      } else {
        // Clear previous selection in the row
        $(`#category-vote-table td[data-row="${rowId}"]`)
          .removeClass('bg-success text-white');

        // Highlight the current cell
        $(this).addClass('bg-success text-white');

        // Update left-most display cell
        $(`#selected-${rowId}`).text(candidateName);

        // Replace or add vote
        const existingVote = formData.votes.find(vote => vote.seat_id === seatId);
        if (existingVote) {
          existingVote.candidate_id = candidateId;
          existingVote.candidate_name = candidateName;
        }
      }
    });

  return data;
}