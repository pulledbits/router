<?php if (count($contactmomenten) === 0): ?>
<p>Geen <?=$this->escape(strtolower($caption)); ?></p>
<?php else: ?>
<table>
	<caption><?=$this->escape($caption); ?></caption>
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
			<td><?=$this->escape($contactmoment->fetchFirstByFkContactmomentLes()->fetchFirstByFkLeslesweek()->kalenderweek); ?></td>
			<td><?=$this->escape($contactmoment->fetchFirstByFkContactmomentLes()->fetchFirstByFkLeslesweek()->blokweek); ?></td>
			<td><?=$this->escape(strftime('%A', strtotime($contactmoment->starttijd))); ?></td>
			<td><?=$this->escape(date('H:i', strtotime($contactmoment->starttijd))); ?></td>
			<td><?=$this->escape(date('H:i', strtotime($contactmoment->eindtijd))); ?></td>
			<td><a href="<?=$this->url('/feedback/%s', $contactmoment->id); ?>" target="_blank">Feedback</a>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php endif; ?>
