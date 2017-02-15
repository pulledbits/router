<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Lesplan {{ $module->naam }}</title>
<link rel="stylesheet" type="text/css" href="/css/lesplan.css">
</head>
<body>
	<header>
		<h1>Lesplan {{ $module->naam }}</h1>
		<h2>HBO-informatica (voltijd)</h2>
	</header>
	<section>
		<h2>{{ $les->naam }}</h2>
		<h3>Beginsituatie</h3>
		<table class="multicol">
			<tr>
				<th>doelgroep</th>
				<td id="doelgroep">{{ $doelgroep->beschrijving }}</td>
				<th>ervaring</th>
				<td id="ervaring">{{ $doelgroep->ervaring }}</td>
			</tr>
			<tr>
				<th>groepsgrootte</th>
				<td id="groepsgrootte" colspan="3">{{ $doelgroep->grootte }} personen</td>
			</tr>
			<tr>
				<th>tijd</th>
				<td id="tijd" colspan="3">van {{ date('H:i', strtotime($contactmoment->starttijd)) }} tot {{ date('H:i', strtotime($contactmoment->eindtijd)) }} ({{ $contactmoment->duur }} minuten)</td>
			</tr>
			<tr>
				<th>ruimte</th>
				<td id="ruimte" colspan="3">{{ $contactmoment->ruimte }}</td>
			</tr>
			<tr>
				<th>overige</th>
				<td id="overige" colspan="3">{{ $les->opmerkingen }}</td>
			</tr>
		</table>

		@if (count($lesmedia) > 0)
    		<h3>Media</h3>
    		<ul>
    			@foreach ($lesmedia as $medium)
    				<li>{{ $medium->omschrijving }}</li>
    			@endforeach
    		</ul>
        @endif
		<h3>Leerdoelen</h3>
		<p>Na afloop van de les kan de student:</p>
		<ul>
			@foreach ($lesleerdoelen as $leerdoel)
				<li>{{ $leerdoel->omschrijving }}</li>
			@endforeach
		</ul>
	</section>
	<section>
		<h2>Introductie</h2>
		@include('activiteit', [
			'title'=> 'Activerende opening',
			'activiteit' => $les->fetchFirstByFkLesActiverendeOpening(),
			'referencing_property' => 'les.activerende_opening_id'
		])
		@include('activiteit', [
			'title'=> 'Focus',
			'activiteit' => $les->fetchFirstByFkLesFocus(),
			'referencing_property' => 'les.focus_id'
		])
		@include('activiteit', [
			'title'=> 'Voorstellen',
			'activiteit' => $les->fetchFirstByFkLesVoorstellen(),
			'referencing_property' => 'les.voorstellen_id'
		])
		@include('activiteit', [
			'title'=> 'Kennismaken',
			'activiteit' => $les->fetchFirstByFkLesKennismaken(),
			'referencing_property' => 'les.kennismaken_id'
		])
	</section>
	<section>
		<h2>Kern</h2>
		@foreach ($les->fetchByFkThemaLesId() as $index => $thema)
		<section>
			<h3>Thema {{ $index+1 }}: {{ $thema->leerdoel }}</h3>
		</section>
		@endforeach
		@formbuilder()
		{!! $formbuilder->open()->post()->action('/thema/create') !!}
		{!! $formbuilder->hidden('lesplan_id')->value($les->id) !!}
		<section>
			<h3>Thema toevoegen</h3>
			{!! $formbuilder->label('Leerdoel:')->forId('leerdoel') !!}
			{!! $formbuilder->text('leerdoel') !!}
		</section>
		{!! $formbuilder->submit('Thema toevoegen') !!}
		{!! $formbuilder->close() !!}
	</section>
	<section>
		<h2>Afsluiting</h2>
		@include('activiteit', [
			'title'=> 'Huiswerk',
			'activiteit' => $les->fetchFirstByFkLesHuiswerk(),
			'referencing_property' => 'les.huiswerk_id'
		])
		@include('activiteit', [
			'title'=> 'Evaluatie',
			'activiteit' => $les->fetchFirstByFkLesEvaluatie(),
			'referencing_property' => 'les.evaluatie_id'
		])
		@include('activiteit', [
			'title'=> 'Pakkend slot',
			'activiteit' => $les->fetchFirstByFkLesPakkendSlot(),
			'referencing_property' => 'les.pakkend_slot_id'
		])
	</section>
</body>
</html>