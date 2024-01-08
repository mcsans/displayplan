<?php $i = 1; ?>
<?php foreach ($data as $row) : ?>
	<input type="hidden" id="<?= $row->name ?>" value="<?= $row->status ?>">
	<tr align="center">
		<td width="1%"><?= $i++ ?></td>
		<td align="left"><?= $row->event ?></td>
		<td><?= ($row->status == 1) ? 'Running' : '' ?></td>
		<td><?= $row->lastruntime ?></td>
		<td><?= $row->count ?></td>
		<td width="1%">
			<button type="button" class="btn btn-sm btn-warning text-white <?= $row->name ?>" onClick="running('<?= $row->name ?>'); <?= $row->name ?>();">RUN</button>
			<button type="button" class="btn btn-sm btn-danger text-white <?= $row->name ?>" onClick="stopped('<?= $row->name ?>')">STOP</button>
		</td>
	</tr>

<?php endforeach; ?>
<tr align="center">
	<td width="1%"><?= $i ?></td>
	<td align="left">Daftar Cekal KP yang actual amount nya 0 </td>
	<td>Running</td>
	<td></td>
	<td> <?php
			// Add your SQL query here
			$result = $this->db->query("SELECT m.*,(SELECT COUNT(*) from Dyelot_Recipe WHERE dyelot = m.dyelot AND ActualAmount = 0 ) FROM dyelots as m 
															where (SELECT COUNT(*) from Dyelot_Recipe WHERE dyelot = m.dyelot AND ActualAmount = 0 ) > 0 AND state > 25 AND year(m.queuetime) >= 2024")->num_rows();
			?> <?= $result ?></td>
	<td> <a href="<?= base_url() ?>/cekal" type="button" class="btn btn-sm btn-warning text-black">VIEW</a></td>
</tr>