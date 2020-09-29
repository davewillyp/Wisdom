@component('mail::message')
# Excursion form approved

Your excursion {{$form->excurname}} has been approved.<br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
