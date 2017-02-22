<?php if (count($contactmomenten) === 0): ?>
<p>Geen <?= strtolower($caption) ?></p>
<?php else: ?>
<table>
	<caption><?= $caption ?></caption>
	<thead>
		<tr>
			<th width="30">KW</th>
			<th width="30">BW</th>
			<th width="80">Dag</th>
			<th width="80">Starttijd</th>
			<th width="80">Eindtijd</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($contactmomenten as $contactmoment): ?>
		<?php if (strtotime($contactmoment->starttijd) <= time() && strtotime($contactmoment->starttijd) >= time()): ?>
			<tr class="active">
		<?php elseif (strtotime($contactmoment->eindtijd) <= time()): ?>
			<tr class="past">
		<?php else: ?>
			<tr>
		<?php endif;?>
			<td><?= $contactmoment->fetchFirstByFkContactmomentLes()->fetchFirstByFkLeslesweek()->kalenderweek ?></td>
			<td><?= $contactmoment->fetchFirstByFkContactmomentLes()->fetchFirstByFkLeslesweek()->blokweek ?></td>
			<td><?= strftime('%A', strtotime($contactmoment->starttijd)) ?></td>
			<td><?= date('H:i', strtotime($contactmoment->starttijd)) ?></td>
			<td><?= date('H:i', strtotime($contactmoment->eindtijd)) ?></td>
			<td><a href="<?= $this->url('/feedback/' . rawurlencode($contactmoment->id)) ?>" target="_blank">Feedback</a>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
<?php endif; ?>
