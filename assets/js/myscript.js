// var debounceTimer;
const baseurl = $('meta[name="baseurl"]').attr("content");
const segment = $('meta[name="segment"]').attr("content");

$(document).ready(function() {
  if (segment.toLowerCase() == 'task/') {
    setInterval(function() {
      $('#1-last').html(getCookie(`1-last-${dateOnly()}`));
      $('#1-count').html(getCookie(`1-count-${dateOnly()}`));

      $('#2-last').html(getCookie(`1-last-${dateOnly()}`));
      $('#2-count').html(getCookie(`1-count-${dateOnly()}`));
      
      console.log('task!');
    }, 5000);

    // setInterval(function() {
    //   $.ajax({
    //     url: 'http://localhost:3000/transData',
    //     method: 'GET',
    //     success: function(response) {
    //         console.log(response);
    //         // Handle the successful response here

    //     },
    //     error: function(xhr, status, error) {
    //         console.error(error);
    //         // Handle the error response here
    //     }
    //   });

    //   console.log('transData!');
    // }, 3600000);
  }
  
  if (segment.toLowerCase() == 'home/') {
    readData(1);
    updateState();
    
    setInterval(function() {
      readData(1);
      updateState();
      console.log('success!');
    }, 10000);
  }
});

function updateState() {
  $.get(`${baseurl}${segment}/updateState/`, {}, function(data) {
    if (data.UpdateState != null) {
      const befCookie = getCookie(`1-count-${dateOnly()}`);
      setCookie(`1-last-${dateOnly()}`, dateTime(), 1);
      setCookie(`1-count-${dateOnly()}`, (befCookie !== null ? parseInt(befCookie)+1 : 1), 1);
    }
  }); 
}



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
// ===END COMPONENTS PAGINATION GLOBAL===



// COOKIE
function setCookie(name, value, days) { // Fungsi untuk mengatur cookie
  const expires = new Date();
  expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
  document.cookie = name + '=' + value + ';expires=' + expires.toUTCString();
}

function getCookie(name) { // Fungsi untuk mengambil nilai cookie
  const cookieName = name + '=';
  const cookies = document.cookie.split(';');
  for (let i = 0; i < cookies.length; i++) {
      let cookie = cookies[i].trim();
      if (cookie.indexOf(cookieName) === 0) {
          return cookie.substring(cookieName.length, cookie.length);
      }
  }
  return null;
}
// ===END COOKIE===



// DATE
function dateTime() {
  var currentDate = new Date();

  var year = currentDate.getFullYear();
  var month = String(currentDate.getMonth() + 1).padStart(2, '0'); // Ditambah 1 karena bulan dimulai dari 0
  var day = String(currentDate.getDate()).padStart(2, '0');
  var hour = String(currentDate.getHours()).padStart(2, '0');
  var minute = String(currentDate.getMinutes()).padStart(2, '0');
  var second = String(currentDate.getSeconds()).padStart(2, '0');

  var formattedDate = year + '-' + month + '-' + day + ' ' + hour + ':' + minute + ':' + second;

  return formattedDate;
}

function dateOnly() {
  var currentDate = new Date();

  var year = currentDate.getFullYear();
  var month = String(currentDate.getMonth() + 1).padStart(2, '0'); // Ditambah 1 karena bulan dimulai dari 0
  var day = String(currentDate.getDate()).padStart(2, '0');

  var formattedDate = year + '-' + month + '-' + day;

  return formattedDate;
}
// ===END DATE===