 @if ($activiteit->id === null) 
 	{{ Form::model($activiteit, ['route' => 'activiteit.create']) }}
 @else
	{{ Form::model($activiteit, ['route' => ['activiteit.edit', $activiteit->id]]) }}
 @endif
 {{ Form::hidden('referencing_property', $referencing_property) }}
<table class="multicol">
	<caption>{{ $title }}</caption>
	<tr>
		<th>{{ Form::label('werkvorm', 'werkvorm') }}</th>
		<td id="werkvorm">{{ Form::text('werkvorm') }}</td>
		<th>{{ Form::label('organisatievorm', 'organisatievorm') }}</th>
		<td id="organisatievorm">{{ Form::select('organisatievorm', \App\Activiteit::ORGANISATIEVORMEN, null, ['placeholder' => 'Organisatievorm']) }}</td>
	</tr>
	<tr>
		<th>{{ Form::label('tijd', 'tijd') }}</th>
		<td id="tijd">{{ Form::number('tijd') }} minuten</td>
		<th>{{ Form::label('soort werkvorm', 'soort werkvorm') }}</th>
		<td id="soort werkvorm">{{ Form::select('werkvormsoort', \App\Activiteit::WERKVORMSOORTEN, null, ['placeholder' => 'Werkvormsoort']) }}</td>
	</tr>
	<tr>
		<th>{{ Form::label('intelligenties', 'intelligenties') }}</th>
		<td id="intelligenties" colspan="3">{{ Form::checklist('intelligenties', \App\Activiteit::INTELLIGENTIES) }}</td>
	</tr>
	<tr>
		<th>{{ Form::label('inhoud', 'inhoud') }}</th>
		<td id="inhoud" colspan="3">{{ Form::textarea('inhoud') }}</td>
	</tr>
</table>
{{ Form::submit('Activiteit opslaan') }}
{{ Form::close() }} 
