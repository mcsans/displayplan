// var debounceTimer;
const baseurl = $('meta[name="baseurl"]').attr("content");
const segment = $('meta[name="segment"]').attr("content");

$(document).ready(function() {
  setInterval(readData(1), 1000);
});



// COMPONENTS PAGINATION GLOBAL
$('.filter-date').change(function() { // filter tanggal pagination global
  readData(1);
});

$('#keyword').keyup(function() { // filter search pagination global
  readData(1);
});

$('#perPage').change(function() { // filter per page pagination global (show entries)
  readData(1);
});

$('#perMesin').change(function() { // filter per page pagination global (show entries)
  readData(1);
});

$('#mytable').on('click', '.paginator', function() { // pindah page pagination global (prev, 1, 2.., Next)
  const page = $(this).data('page');
  readData(page);
});

function readData(page) { // load data (tbody) pagination global
  const perPage  = $('#perPage').val();
  const perMesin = $('#perMesin').val();
  const keyword  = btoa($('#keyword').val());

  if(keyword == "") {
    $.get(`${baseurl}${segment}/readData/`, {page, perPage, perMesin}, function(data) {
      $('#mytable').html(data);
    }); 
  } else {
    $.get(`${baseurl}${segment}/readData/${keyword}`, {page, perPage, perMesin}, function(data) {
      $('#mytable').html(data);
    }); 
  }
}
// END COMPONENTS PAGINATION GLOBAL