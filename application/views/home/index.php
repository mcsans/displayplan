<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="today" content="<?= date('Y-m-d'); ?>">
  <meta name="baseurl" content="<?= base_url(); ?>">
  <meta name="segment" content="<?= $this->uri->segment(1) . '/' . $this->uri->segment(2); ?>">
  <title>Planing Display</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

	<style>
		.btn-success-light {
			background-color: greenyellow;
		}
	</style>
</head>

<body class="bg-dark">
  <div id="app">
    <section class="section my-5 mx-5">
      <div class="card d-none">
        <div class="card-header bg-white">
          <div class="row">
            <div class="d-flex justify-content-between mt-3 mb-2">
              <div class="d-flex w-25">
                <small class="mt-1">Show</small>
                <select class="form-select form-select-sm w-25 mx-1" id="perPage">
                  <option value="5">5</option>
                  <option value="10">10</option>
                  <option value="100" selected>15</option>
                  <option value="20">20</option>
                  <option value="25">25</option>
                  <option value="25">25</option>
                  <option value="50">50</option>
                  <option value="100">100</option>
                </select>
                <small class="mt-1">entries</small>
              </div>
              <div class="w-25"></div>
              <div class="d-flex w-50 justify-content-end">
                <small class="mt-1">Mesin:</small>
                <select class="form-select form-select-sm w-25 mx-1" id="perMesin">
                  <option value="ALL">Semua</option>
                  <option value="CN01">Canlar 01</option>
                  <option value="CN02">Canlar 02</option>
                  <option value="CN03">Canlar 03</option>
                  <option value="TH01">Thies</option>
                </select>
              </div>
              <div class="d-flex w-25 ms-5">
                <div class="input-group input-group-sm">
                  <small class="mt-1 me-1">Search:</small>
                  <input class="form-control" type="texts" id="keyword">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body pb-0">
          <!-- TABLE -->
          <!-- <div id="mytable"></div> -->

          <!-- FORMS -->
          <!-- <div id="myform"></div> -->
        </div>
      </div>

      <!-- TABLE -->
      <div id="mytable"></div>

<!-- FORMS -->
<div id="myform"></div>
    </section>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="<?= base_url('assets/js/myscript.js') ?>"></script>
</body>

</html>
