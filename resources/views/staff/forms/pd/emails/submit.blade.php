@component('mail::message')
# PD Form Submitted for Approval

{{$form->pdname}} has been submitted by {{$form->firstname}} {{$form->surname}}.<br>
Click the button below to review the form.
<br>
@component('mail::button', ['url' => 'https://wisdev.sjc.wa.edu.au/admin/forms/pd/'.$form->id])
Review Form
@endcomponent
<br>
Thanks,<br>
{{ config('app.name') }}
@endcomponent
