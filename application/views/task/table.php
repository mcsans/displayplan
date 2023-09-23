<?php $i=1; ?>
<?php foreach($data as $row) : ?>
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
