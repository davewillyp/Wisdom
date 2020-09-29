@component('mail::message')
# Excursion notification

{{$form->excurname}} by {{$form->firstname. ' ' .$form->surname}} will be running from
{{\Carbon\Carbon::parse($form->startDate)->format('l j F').' '. Carbon\Carbon::parse($form->startTime)->format('g:i a')}}
to
{{\Carbon\Carbon::parse($form->finishDate)->format('l j F').' '. Carbon\Carbon::parse($form->finishTime)->format('g:i a')}}<br>

@if($expense->invoiced)
Excursion Payment to be Invoiced.<br>
@endif
@if($expense->before)
Excursion Payment required before start.<br>
@endif

@component('mail::table')
| Expense | Name on Cheque | Amount | Date Req|
|---------|----------------|--------|---------|
@foreach($expenses as $exp)
|{{$exp->expense}}|{{$exp->cheque}}|${{$exp->amount}}|{{\Carbon\Carbon::parse($exp->required)->format('j F')}}|
@endforeach
@endcomponent
<br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
