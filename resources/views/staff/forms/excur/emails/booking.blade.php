@component('mail::message')
# Excursion notification

{{$form->excurname}} by {{$form->firstname. ' ' .$form->surname}} will be running from
{{\Carbon\Carbon::parse($form->startDate)->format('l j F').' '. Carbon\Carbon::parse($form->startTime)->format('g:i a')}}
to
{{\Carbon\Carbon::parse($form->finishDate)->format('l j F').' '. Carbon\Carbon::parse($form->finishTime)->format('g:i a')}}.<br>

@if($logistics->bus)
Bus booking required.<br>
@endif
@if($logistics->car)
Car booking required.<br>
@endif
@if($logistics->phone)
Excursion phone booking required.<br>
@endif
@if($logistics->epirb)
Epirb booking required.<br>
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent
