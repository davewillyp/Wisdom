@component('mail::message')
# Excursion form submitted for review

{{$form->excurname}} has been submitted by {{$form->firstname}} {{$form->surname}}.<br>
Click the button below to review the form.
<br>
@component('mail::button', ['url' => 'https://wisdev.sjc.wa.edu.au/admin/forms/excur/'.$form->id])
Review Form
@endcomponent
<br>
Thanks,<br>
{{ config('app.name') }}
@endcomponent
