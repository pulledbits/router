@formbuilder()

@if ($activiteit->id === null)
	{!! $formbuilder->open()->post()->action('/activiteit/create') !!}
@else
	{!! $formbuilder->open()->post()->action('/activiteit/edit/' . $activiteit->id) !!}
@endif

{!! $formbuilder->token() !!}
{!! $formbuilder->bind($activiteit) !!}

@section('fieldset')
 {!! $formbuilder->hidden('referencing_property')->value($referencing_property) !!}
<table class="multicol">
	<caption>{{ $title }}</caption>
	<tr>
		<th>{!! $formbuilder->label('tijd')->forId('tijd') !!}</th>
		<td id="tijd">{!! $formbuilder->text('tijd')->attribute('type', 'number') !!} minuten</td>
	</tr>
	<tr>
		<th>{!! $formbuilder->label('inhoud')->forId('inhoud') !!}</th>
		<td id="inhoud">{!! $formbuilder->textarea('inhoud') !!}</td>
	</tr>
</table>
{!! $formbuilder->submit('Activiteit opslaan') !!}
{!! $formbuilder->close() !!}
