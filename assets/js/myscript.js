const baseurl = $('meta[name="baseurl"]').attr("content");
const segment = $('meta[name="segment"]').attr("content");

$(document).ready(function() {
  if (segment.toLowerCase() == 'task/') {
		readDataTask();
    
    // setInterval(function() {
    //   readDataTask();
    // }, 10000);
    
    setInterval(function() {
			if ($('#updateState').val() != 0) {
				updateState();
			}
    }, 60000);
    
    setInterval(function() {
			if ($('#updateStatusOrga').val() != 0) {
				updateStatusOrga();
			}
    }, 60000);

    setInterval(function() {
			if ($('#transData').val() != 0) {
				transData();
			}
    }, 60000);

    setInterval(function() {
			if ($('#callProcedure').val() != 0) {
				callProcedure();
			}
    }, 3600000);
  }
  
  if (segment.toLowerCase() == 'home/') {
    readData(1);
    
    // setInterval(function() {
    //   updateState();
    // }, 60000);

    setInterval(function() {
      readData(1);
    }, 10000);
  }
});

function updateState() {
	$('.updateState').prop('disabled', true);
  $.get(`${baseurl}home/updateState/`, {}, function() {
		if (segment.toLowerCase() == 'home/') {
			readData(1);
		}
	}); 
}

function updateStatusOrga() {
	$('.updateStatusOrga').prop('disabled', true);
  $.get(`${baseurl}task/updateStatusOrga/`, {}, function() {
		readDataTask();
	}); 
}

function transData() {
	$('.transData').prop('disabled', true);
  $.get(`${baseurl}task/transData/`, {}, function() {
		readDataTask();
	}); 
}

function callProcedure() {
	$('.callProcedure').prop('disabled', true);
  // $.get(`${baseurl}task/callProcedure/`, {}, function() {
		readDataTask();
	// }); 
}

function running(uri) {
  $.get(`${baseurl}task/running/${uri}/`, {}, function() {
		readDataTask();
	}); 
}

function stopped(uri) {
  $.get(`${baseurl}task/stopped/${uri}/`, {}, function() {
		readDataTask();
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

function readDataTask() {
  $.get(`${baseurl}${segment}/readDataTask/`, {}, function(data) {
    $('tbody').html(data);
  });
}
