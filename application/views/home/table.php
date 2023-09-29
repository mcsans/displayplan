<div class="table-responsive">
  <table class="table table-dark table-hover table-bordered table-striped text-nowrap mt-1 mb-3">
    <thead class="text-black">
      <tr align="center">
        <th class="bg-primary text-light text-uppercase">No</th>
        <th class="bg-primary text-light text-uppercase">Mesin</th>
        <th class="bg-primary text-light text-uppercase">ID_WO</th>
        <th class="bg-primary text-light text-uppercase">KP</th>
        <th class="bg-primary text-light text-uppercase">Kain</th>
        <th class="bg-primary text-light text-uppercase">Warna</th>
        <th class="bg-primary text-light text-uppercase">Kode Warna</th>
        <th class="bg-primary text-light text-uppercase">KG</th>
        <th class="bg-primary text-light text-uppercase">Antrian Planning</th>
        <th class="bg-primary text-light text-uppercase">Status</th>
        <th class="bg-primary text-light text-uppercase">Selesai</th>
        <th class="bg-primary text-light text-uppercase">Action</th>
      </tr>
    </thead>
    <tbody>
      <?php $i=0; ?>
      <?php foreach($pagination['paginator'] as $data) : ?>
        <?php
          $queue = strtotime($data['QueueTime']);
          $now   = strtotime(date('Y-m-d 00:00:00'));
          $late  = number_format((($now - $queue) / 86400), 0);
          
          $day1  = ($late == 1) ? 'text-warning' : '';
          $day2  = ($late >= 2) ? 'text-danger' : '';
        ?>
      <tr>
        <td align="center" class="<?= $day1 ?> <?= $day2 ?>" width="1%"><?= ++$i; ?></td>
        <td align="center" class="<?= $day1 ?> <?= $day2 ?>"><?= $data['Machine'] ?></td>
        <td align="center" class="<?= $day1 ?> <?= $day2 ?>"><?= $data['Dyelot'] ?></td>
        <td align="center" class="<?= $day1 ?> <?= $day2 ?>"><?= $data['Text11'] ?></td>
        <td align="center" class="<?= $day1 ?> <?= $day2 ?>"><?= $data['Article'] ?></td>
        <td align="center" class="<?= $day1 ?> <?= $day2 ?>"><?= $data['ColourDescript'] ?></td>
        <td align="center" class="<?= $day1 ?> <?= $day2 ?>"><?= $data['ColourNo'] ?></td>
        <td align="center" class="<?= $day1 ?> <?= $day2 ?>"><?= number_format($data['Weight'], 2) ?></td>
        <td align="center" class="<?= $day1 ?> <?= $day2 ?>"><?= date('d/m/Y - H:i:s', strtotime($data['QueueTime'])) ?></td>
        <td align="left" class="<?= $day1 ?> <?= $day2 ?>">
          <?= $data['State'] == 0 ? 'Belum planning' : ''; ?>
          <?= $data['State'] == 10 ? 'Planning' : ''; ?>
          <?= $data['State'] == 20 ? 'Planning dengan resep' : ''; ?>
          <?= $data['State'] == 25 ? 'Dikirim ke mesin' : ''; ?>
          <?= $data['State'] == 27 ? 'Dikirim ke mesin dengan resep' : ''; ?>
          <?= $data['State'] == 30 ? 'Aktif' : ''; ?>
          <?= $data['State'] == 35 ? 'Mesin mulai' : ''; ?>
          <?= $data['State'] == 40 ? 'Selesai' : ''; ?>
        </td> 
				<td align="center" width="1%">
					<button type="button" class="btn btn-sm <?= ($data['Text20'] == 1) ? 'btn-success-light' : 'btn-secondary' ?> py-0">DS</button>
					<button type="button" class="btn btn-sm <?= ($data['Text20'] == 2) ? 'btn-success-light' : 'btn-secondary' ?> py-0">AX</button>
				</td>
				<td align="center" width="1%">
					<button type="button" class="btn btn-sm btn-warning py-0" onClick="endPlan('<?= $data['Dyelot'] ?>', '<?= $data['Text11'] ?>')">END</button>
				</td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
  
  <div class="d-flex justify-content-between d-none">
    <span class="mt-2">Showing <?= ++$pagination['from'] ?> to <?= $pagination['to'] ?> from <?= $pagination['total'] ?> entries</span>
  
    <nav>
      <ul class="pagination">
        <li class="page-item m-0 <?= $pagination['now'] == 1 ? 'disabled' : ''; ?>"><span class="page-link paginator" data-page="<?= ($pagination['now']-1) ?>">Prev</span></li>
  
        <?php if($pagination['now'] != 1) : ?>
          <?php if($pagination['now'] != 2) : ?>
          <li class="page-item"><span class="page-link paginator" data-page="<?= ($pagination['now']-2) ?>"><?= ($pagination['now']-2) ?></span></li>
          <li class="page-item disabled"><span class="page-link paginator">...</span></li>
          <?php endif ?>
        <li class="page-item"><span class="page-link paginator" data-page="<?= ($pagination['now']-1) ?>"><?= ($pagination['now']-1) ?></span></li>
        <?php endif ?>
  
        <li class="page-item active"><span class="page-link paginator" data-page="<?= ($pagination['now']) ?>"><?= ($pagination['now']) ?></span></li>
  
        <?php if($pagination['now'] != $pagination['lastPage']) : ?>
        <li class="page-item"><span class="page-link paginator" data-page="<?= ($pagination['now']+1) ?>"><?= ($pagination['now']+1) ?></span></li>
          <?php if($pagination['now'] != ($pagination['lastPage']-1)) : ?>
          <li class="page-item disabled"><span class="page-link paginator">...</span></li>
          <li class="page-item"><span class="page-link paginator" data-page="<?= ($pagination['now']+2) ?>"><?= ($pagination['now']+2) ?></span></li>
          <?php endif ?>
        <?php endif ?>
  
        <li class="page-item m-0  <?= $pagination['now'] == $pagination['lastPage'] ? 'disabled' : ''; ?>"><span class="page-link paginator"  data-page="<?= ($pagination['now']+1) ?>">Next</span></li>
      </ul>
    </nav>
  </div>
</div>
