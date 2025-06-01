/* helpers */
function slugify(str) { return str.replace(/\s+/g, '-').toLowerCase(); }
function getLightColor(i) { return `hsl(${(i * 47) % 360},70%,92%)`; }

/* ---------- REVIEW TABLE ---------- */
function renderFinalReviewTable(votingData) {
  const tbody = $('#final-vote-table tbody').empty();

  votingData.forEach((entry, i) => {
    const { category, urdu, candidates } = entry;
    const vote       = formData.multiVotes[category];
    const selected   = vote ? candidates[vote.candidateIndex] : null;

    const row = $('<tr>').css('background-color', "#E8FFCF ")
      .append(`<td class="font-weight-bold" style="min-width: 120px; background-color: #B6E6FE; color: #0B3563;">${selected ? selected.name : '---'}</td>`)
      .append(`<td><img src=${selected.image} style="width:100px"></img> <div class="mt-1">${selected.name}</div></td>`)
      .append(`
        <td  class="category-cell text-white font-weight-bold" data-category="${category}" style="cursor:pointer; background-color:#0B3563;">
          ${urdu}<br><small>${category}</small>
        </td>`);

    tbody.append(row);
  });

  /* open modal on category-cell click */
  $('#final-vote-table td.category-cell').off('click').on('click', function () {
    
    openReselectModal($(this).data('category'));
  });
}

/* ---------- RESELECT MODAL ---------- */
function openReselectModal(category) {
  const entry = votingData.find(e => e.category === category);
  if (!entry) return;

  const { candidates }   = entry;
  const selectedIndex    = formData.multiVotes[category]?.candidateIndex ?? -1;
  const table            = $('<table class="table table-bordered text-center w-100"><tbody><tr></tr></tbody></table>');
  const row              = table.find('tr').css('background-color', "#E8FFCF ");

  candidates.forEach((c, i) => {
    row.append(`
        
      <td data-index="${i}" style="cursor:pointer" class="${i === selectedIndex ? 'bg-success text-white':''}">
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
  const idx       = $(this).data('index');
  const category  = $(this).data('category');
  if (idx == null || category == null) return;

  const entry     = votingData.find(e => e.category === category);
  const cand      = entry.candidates[idx];

  formData.multiVotes[category] = { candidateIndex: idx, candidateName: cand.name, image: cand.image };
  $('#reselectModal').modal('hide');
  renderFinalReviewTable(votingData);          // refresh review table
});

