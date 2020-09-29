@component('mail::message')
# PD notification

{{$form->firstname. ' ' .$form->surname}} will be attending {{$form->pdname}} from
{{\Carbon\Carbon::parse($form->startDate)->format('l j F').' '. Carbon\Carbon::parse($form->startTime)->format('g:i a')}}
to
{{\Carbon\Carbon::parse($form->finishDate)->format('l j F').' '. Carbon\Carbon::parse($form->finishTime)->format('g:i a')}}.<br>


@if($logistics->car)
### Car booking required
Pickup Date: {{\Carbon\Carbon::parse($logistics->pickupDate)->format('l j F')}} <br>
Pickup Time: {{Carbon\Carbon::parse($logistics->pickupTime)->format('g:i a')}}<br>
Dropoff Date: {{\Carbon\Carbon::parse($logistics->dropoffpDate)->format('l j F')}} <br>
Dropoff Time: {{Carbon\Carbon::parse($logistics->dropoffTime)->format('g:i a')}}<br>
<br>
@endif

@if($logistics->car)
### Accommodation booking required
Arrival: {{\Carbon\Carbon::parse($logistics->arrival)->format('l j F')}}<br>
Departure: {{\Carbon\Carbon::parse($logistics->departure)->format('l j F')}}<br>
@endif



Thanks,<br>
{{ config('app.name') }}
@endcomponent
