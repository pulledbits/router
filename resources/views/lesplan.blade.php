<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Lesplan {{ $contactmoment->les->module->naam }}</title>
<link rel="stylesheet" type="text/css" href="/css/lesplan.css">
</head>
<body>
	<header>
		<h1>Lesplan {{ $contactmoment->les->module->naam }}</h1>
		<h2>HBO-informatica (voltijd)</h2>
	</header>
	<section>
		<h2>{{ $contactmoment->les->naam }}</h2>
		<h3>Beginsituatie</h3>
		<table class="multicol">
			<tr>
				<th>doelgroep</th>
				<td id="doelgroep">{{ $contactmoment->les->doelgroep->beschrijving }}</td>
				<th>ervaring</th>
				<td id="ervaring">{{ $contactmoment->les->doelgroep->ervaring }}</td>
			</tr>
			<tr>
				<th>groepsgrootte</th>
				<td id="groepsgrootte" colspan="3">{{ $contactmoment->les->doelgroep->grootte }} personen</td>
			</tr>
			<tr>
				<th>tijd</th>
				<td id="tijd" colspan="3">van {{ $contactmoment->starttijd->format('H:i') }} tot {{ $contactmoment->eindtijd->format('H:i') }} ({{ $contactmoment->duur }} minuten)</td>
			</tr>
			<tr>
				<th>ruimte</th>
				<td id="ruimte" colspan="3">{{ $contactmoment->ruimte }}</td>
			</tr>
			<tr>
				<th>overige</th>
				<td id="overige" colspan="3">{{ $contactmoment->les->opmerkingen }}</td>
			</tr>
		</table>
		@if (count($contactmoment->les->media) > 0)
    		<h3>Media</h3>
    		<ul>
    			@foreach ($contactmoment->les->media as $medium)
    				<li>{{ $medium->omschrijving }}</li>
    			@endforeach
    		</ul>
        @endif
		<h3>Leerdoelen</h3>
		<p>Na afloop van de les kan de student:</p>
		<ul>
			@foreach ($contactmoment->les->leerdoelen as $leerdoel)
				<li>{{ $leerdoel->omschrijving }}</li>
			@endforeach
		</ul>
	</section>
	<section>
		<h2>Introductie</h2>
		@include('activiteit', [
			'title'=> 'Activerende opening',
			'activiteit' => $contactmoment->les->activerendeOpening,
			'referencing_property' => 'les.activerende_opening_id'
		])
		@include('activiteit', [
			'title'=> 'Focus',
			'activiteit' => $contactmoment->les->focus,
			'referencing_property' => 'les.focus_id'
		])
		@include('activiteit', [
			'title'=> 'Voorstellen',
			'activiteit' => $contactmoment->les->voorstellen,
			'referencing_property' => 'les.voorstellen_id'
		])
		@include('activiteit', [
			'title'=> 'Kennismaken',
			'activiteit' => $contactmoment->les->kennismaken,
			'referencing_property' => 'les.kennismaken_id'
		])
	</section>
	<section>
		<h2>Kern</h2>
		@foreach ($contactmoment->les->themas as $index => $thema)
		<section>
			<h3>Thema {{ $index+1 }}: {{ $thema->leerdoel }}</h3>
		</section>
		@endforeach 
         {{ Form::open(['route' => 'thema.create']) }}
         {{ Form::hidden('lesplan_id', $contactmoment->les->id) }}
		<section>
			<h3>Thema toevoegen</h3>
			{{ Form::label('leerdoel', 'Leerdoel:') }}
			{{ Form::text('leerdoel') }}
		</section>
        {{ Form::submit('Thema toevoegen') }}
        {{ Form::close() }} 
	</section>
	<section>
		<h2>Afsluiting</h2>
		@include('activiteit', [
			'title'=> 'Huiswerk',
			'activiteit' => $contactmoment->les->huiswerk,
			'referencing_property' => 'les.huiswerk_id'
		])
		@include('activiteit', [
			'title'=> 'Evaluatie',
			'activiteit' => $contactmoment->les->evaluatie,
			'referencing_property' => 'les.evaluatie_id'
		])
		@include('activiteit', [
			'title'=> 'Pakkend slot',
			'activiteit' => $contactmoment->les->pakkendSlot,
			'referencing_property' => 'les.pakkend_slot_id'
		])
	</section>
</body>
</html>