
@extends('layouts.master')

@section('title', 'Importeer contactmomenten')

@section('content')
	<header>
		<h1>Importeer contactmomenten</h1>
	</header>
    <section>
    	<form method="post">
    		{{ csrf_field() }}
    		URL: <input type="text" name="url" /><br />
    		<input type="submit" value="Importeren" />
    	</form>
    </section>
@endsection