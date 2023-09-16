<?php $i=1; ?>
<?php foreach($data as $row) : ?>
<tr align="center">
	<td width="1%"><?= $i++ ?></td>
	<td align="left"><?= $row->event ?></td>
	<td><?= $row->lastruntime ?></td>
	<td><?= $row->count ?></td>
	<td width="1%"><button type="button" class="btn btn-sm btn-warning text-white" onClick="<?= $row->name ?>()">RUN</button></td>
</tr>
<?php endforeach; ?>
