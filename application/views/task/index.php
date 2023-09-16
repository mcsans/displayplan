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
</head>

<body class="bg-dark">
  <div id="app">
    <section class="section my-5 mx-5">
      <!-- TABLE -->
      <div id="mytable">
        <div class="table-responsive">
          <table class="table table-dark table-hover table-bordered table-striped text-nowrap mt-1 mb-3">
            <thead class="text-black">
              <tr align="center">
                <th class="bg-primary text-light text-uppercase">No</th>
                <th class="bg-primary text-light text-uppercase">Event</th>
                <th class="bg-primary text-light text-uppercase">Last Runtime</th>
                <th class="bg-primary text-light text-uppercase">Count</th>
                <th class="bg-primary text-light text-uppercase">Action</th>
              </tr>
            </thead>
            <tbody id="tbody"></tbody>
          </table>
        </div>
      </div>

      <!-- FORMS -->
      <div id="myform"></div>
    </section>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="<?= base_url('assets/js/transData.js') ?>"></script>
  <script src="<?= base_url('assets/js/callProcedure.js') ?>"></script>
  <script src="<?= base_url('assets/js/myscript.js') ?>"></script>
</body>

</html>

<!-- DS -->
