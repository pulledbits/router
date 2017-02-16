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
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($contactmomenten as $contactmoment) 
		@if ($contactmoment->active)
			<tr class="active">
		@elseif ($contactmoment->past)
			<tr class="past">
		@else
			<tr>
		@endif
		
			<td>{{ $contactmoment->les->lesweek->kalenderweek }}</td>
			<td>{{ $contactmoment->les->lesweek->blokweek }}</td>
			<td>{{ $contactmoment->starttijd->formatLocalized('%A') }}</td>
			<td>{{ $contactmoment->starttijd->format('H:i') }}</td>
			<td>{{ $contactmoment->eindtijd->format('H:i') }}</td>
			<td>@foreach ($ipv4Adresses as $ipv4Adress) <a href="http://{{ $ipv4Adress }}/feedback/{{ $contactmoment->id }}" target="_blank" title="{{ $ipv4Adress }}">Feedback</a> @endforeach
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
@endif
