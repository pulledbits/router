 @if ($activiteit->id === null) 
 	{{ Form::model($activiteit, ['route' => 'activiteit.create']) }}
 @else
	{{ Form::model($activiteit, ['route' => ['activiteit.edit', $activiteit->id]]) }}
 @endif
 {{ Form::hidden('referencing_property', $referencing_property) }}
<table class="multicol">
	<caption>{{ $title }}</caption>
	<tr>
		<th>{{ Form::label('tijd', 'tijd') }}</th>
		<td id="tijd">{{ Form::number('tijd') }} minuten</td>
	</tr>
	<tr>
		<th>{{ Form::label('inhoud', 'inhoud') }}</th>
		<td id="inhoud">{{ Form::textarea('inhoud') }}</td>
	</tr>
</table>
{{ Form::submit('Activiteit opslaan') }}
{{ Form::close() }} 
