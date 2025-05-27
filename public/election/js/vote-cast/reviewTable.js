/* helpers */
function slugify(str) { return str.replace(/\s+/g, '-').toLowerCase(); }
function getLightColor(i) { return `hsl(${(i * 47) % 360},70%,92%)`; }

/* ---------- REVIEW TABLE ---------- */
function renderFinalReviewTable(votingData) {
  const tbody = $('#final-vote-table tbody').empty();

  votingData.forEach((entry, i) => {
    const { category, urdu, candidates } = entry;
    const vote = formData.multiVotes[category];
    const selected = vote ? candidates[vote.candidateIndex] : null;

    const row = $('<tr>').css('background-color', getLightColor(i))
      .append(`<td class="font-weight-bold">${selected ? selected.name : '---'}</td>`)
      .append(`<td><img src=${selected.image} style="width:100px"></img></td>`)
      .append(`
        <td class="bg-dark text-white font-weight-bold"
            data-category="${category}" style="cursor:pointer">
          ${urdu}<br><small>${category}</small>
        </td>`);

    tbody.append(row);
  });

  /* open modal on category-cell click */
  $('#final-vote-table td.bg-dark').off('click').on('click', function () {
    openReselectModal($(this).data('category'));
  });
}

/* ---------- RESELECT MODAL ---------- */
function openReselectModal(category) {
  const entry = votingData.find(e => e.category === category);
  if (!entry) return;

  const { candidates } = entry;
  const selectedIndex = formData.multiVotes[category]?.candidateIndex ?? -1;
  const table = $('<table class="table table-bordered text-center w-100"><tbody><tr></tr></tbody></table>');
  const row = table.find('tr');

  candidates.forEach((c, i) => {
    row.append(`
        
      <td data-index="${i}" style="cursor:pointer" class="${i === selectedIndex ? 'bg-success text-white' : ''}">
        <img src="${c.image}" style="width:80px"><br><strong>${c.name}</strong>
      </td>`);
  });
  /* category label on the far-right */
  $(
    '#category-name-review'
  ).text(`${entry.category}`)
  $('#reselect-body').empty().append(table);
  $('#reselectModal').modal('show');

  /* choose new candidate inside modal */
  $('#reselect-body td').off('click').on('click', function () {
    $('#reselect-body td').removeClass('bg-success text-white');
    $(this).addClass('bg-success text-white');
    $('#confirm-reselect')
      .data('index', $(this).data('index'))
      .data('category', category);
  });
}

/* confirm modal choice */
$('#confirm-reselect').on('click', function () {
  const idx = $(this).data('index');
  const category = $(this).data('category');
  if (idx == null || category == null) return;

  const entry = votingData.find(e => e.category === category);
  const cand = entry.candidates[idx];

  formData.multiVotes[category] = { candidateIndex: idx, candidateName: cand.name, image: cand.image };
  $('#reselectModal').modal('hide');
  renderFinalReviewTable(votingData);          // refresh review table
});

