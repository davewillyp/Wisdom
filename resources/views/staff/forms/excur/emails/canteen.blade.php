@component('mail::message')
# Excursion notification

{{$form->excurname}} will be running from
{{\Carbon\Carbon::parse($form->startDate)->format('l j F').' '. Carbon\Carbon::parse($form->startTime)->format('g:i a')}}
to
{{\Carbon\Carbon::parse($form->finishDate)->format('l j F').' '. Carbon\Carbon::parse($form->finishTime)->format('g:i a')}}.<br><br>
{{$count}} students will be attending.


Thanks,<br>
{{ config('app.name') }}
@endcomponent
