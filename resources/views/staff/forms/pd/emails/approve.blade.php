@component('mail::message')
# PD form approved

Your PD {{$form->excurname}} has been approved.<br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
