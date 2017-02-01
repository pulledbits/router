<ul>
@foreach ($options as $optionIdentifier => $optionLabel)
<li>{{ Form::checkbox($name . '[]', $optionIdentifier, in_array($optionIdentifier, Form::getValueAttribute($name))) }} {{ $optionLabel }}</li>
@endforeach
</ul>