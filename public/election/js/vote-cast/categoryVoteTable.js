function slugify(str) {
  return str.replace(/\s+/g, '-').toLowerCase();
}
function getLightColor(index) {
  const hue = (index * 47) % 360; // spread hues
  return `hsl(${hue}, 70%, 92%)`;  // light pastel tone
}
function renderCategoryVoteTable(data) {
  const table = $('#category-vote-table tbody');
  table.empty();
  formData.votes = [];

  const maxCandidates = Math.max(...data.map(entry => entry.candidates.length));

  data.forEach((entry, rowIdx) => {
    const { category, urdu, candidates,id } = entry;
    const safeId = slugify(category);
    const rowColor = getLightColor(rowIdx);

    // const tr = $(`<tr  style="background-color: #E8FFCF;"></tr>`);
    const tr = $(`<tr style="background-color: ${rowColor};"></tr>`);


    // Left: Selected Cell
    tr.append(`
      <td id="selected-${safeId}" class="text-center py-2 font-weight-bold align-middle" style="min-width: 250px; background-color: #B6E6FE; color: #0B3563;"">---</td>
    `);

    // Middle: Candidate Wrapper inside a single <td>
    const candidateTd = $('<td colspan="' + maxCandidates + '" style="width: 100%;"></td>');
    const flexWrap = $(`
      <div class="d-flex justify-content-center gap-2 w-100">
      </div>
    `);

    candidates.forEach((candidate, i) => {
    const cell = $(`
  <div class="selectable py-2  text-center"
       style="flex: 1 1 0; min-width: 0; cursor: pointer;border-left: 1px solid black !important;"
        data-row="${safeId}-${id}"
                        data-seat-id="${id}"
                        data-candidate-id="${candidate.id}"
                        data-category="${category}">
  ${candidate.image}<br>
    <div class="mt-1">${candidate.name}</div>
  </div>
`);

      flexWrap.append(cell);
    });

    candidateTd.append(flexWrap);
    tr.append(candidateTd);

    // Right: Category Name
    tr.append(`
      <td class=" text-white py-2 font-weight-bold text-center align-middle" style="min-width: 250px; background-color:#0B3563">
        ${urdu}<br><small>${category}</small>
      </td>
    `);

    table.append(tr);
    formData.votes.push({
        seat_id: id,
        seat_name: category,
        candidate_id: null,
        candidate_name: null,
    });
  });

   $('#category-vote-table')
    .off('click', '.selectable')
    .on('click', '.selectable', function () {
      const rowId    = $(this).data('row');
      const category = $(this).data('category');
      const index    = $(this).data('index');
      const candName = $(this).find('div').text();

      $(`.selectable[data-row="${rowId}"]`)
        .removeClass('selectedCandidate');

      $(this).addClass('selectedCandidate ');
      $(`#selected-${rowId}`).text(candName);

      formData.multiVotes[category] = {
        candidateIndex: index,
        candidateName: candName,
        image: $(this).find('img').attr('src')
      };
    });
  // Click Handler
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
          .removeClass('selectedCandidate');

      $(this).addClass('selectedCandidate');

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
