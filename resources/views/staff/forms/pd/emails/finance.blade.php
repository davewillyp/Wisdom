@component('mail::message')
# PD notification

{{$form->firstname. ' ' .$form->surname}} will be attending {{$form->pdname}} from
{{\Carbon\Carbon::parse($form->startDate)->format('l j F').' '. Carbon\Carbon::parse($form->startTime)->format('g:i a')}}
to
{{\Carbon\Carbon::parse($form->finishDate)->format('l j F').' '. Carbon\Carbon::parse($form->finishTime)->format('g:i a')}}<br>

@if($expense->fee)
PD Fee: ${{number_format($expense->amount, 2, '.', ',')}}.<br>
@endif
@if($expense->invoiced)
PD fee to be invoiced.<br>
@endif
@if($expense->before)
PD fee payment required before PD.<br>
@endif

Other expenses to claim: {{$expense->claim}} <br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
