@if (count($contactmomenten) === 0)
<p>Geen {{ strtolower($caption) }}</p>
@else
<table>
	<caption>{{ $caption }}</caption>
	<thead>
		<tr>
			<th width="30">KW</th>
			<th width="30">BW</th>
			<th width="80">Dag</th>
			<th width="80">Starttijd</th>
			<th width="80">Eindtijd</th>
			<th width="80">&nbsp;</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($contactmomenten as $contactmoment)
		@if (date('U', $contactmoment->starttijd) <= time() && date('U', $contactmoment->eindtijd) >= time())
			<tr class="active">
		@elseif (date('U', $contactmoment->eindtijd) <= time())
			<tr class="past">
		@else
			<tr>
		@endif
			<td>{{ $contactmoment->fetchFirstByFkContactmomentLes()->fetchFirstByFkLeslesweek()->kalenderweek }}</td>
			<td>{{ $contactmoment->fetchFirstByFkContactmomentLes()->fetchFirstByFkLeslesweek()->blokweek }}</td>
			<td>{{ strftime('%A', strtotime($contactmoment->starttijd)) }}</td>
			<td>{{ date('H:i', $contactmoment->starttijd) }}</td>
			<td>{{ date('H:i', $contactmoment->eindtijd) }}</td>
			<td><a href="/contactmoment/{{ $contactmoment->id }}" target="_blank">Lesplan</a></td>
			<td>@foreach ($ipv4Adresses as $ipv4Adress) <a
				href="http://{{ $ipv4Adress }}/feedback/{{ $contactmoment->id }}"
				target="_blank">Feedback ({{ $ipv4Adress }})</a> @endforeach
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
@endif
