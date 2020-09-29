@extends('staff.layout')
@section('body')
@CSRF
<div class='container-fluid'>
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb" style='background-color:whitesmoke;'>
        <li class="breadcrumb-item"><a href="/staff">Home</a></li>    
        <li class="breadcrumb-item"><a href="/admin">Admin</a></li>
        <li class="breadcrumb-item"><a href="/admin/forms">Forms</a></li>
        <li class="breadcrumb-item active">{{$form->pdname}}</li>
    </ol>
    </nav>
    <div class='row'>
        <div class='col-3'>
            <div class="mr-3">               
                <div class='p-4 shadow-sm rounded' style='background-color:whitesmoke;'>
                <button type="button" class="btn btn-outline-secondary" onclick="window.open('/staff/forms/pd/{{$form->id}}/pdf')"><i class="fal fa-file-pdf pr-2" style="color:red"></i>Download / Print Form</button> 
                <br><br>
                @if($form->status == 2)
                <button type="button" class="btn btn-outline-primary" onclick="approveForm({{$form->id}})">Approve Form</button>
                @elseif($form->status == 4) 
                <button type="button" class="btn btn-outline-success" disabled>Form Approved</button>                
                @endif
                </div>
            </div>                 
        </div>
        <div class="col-9">
            <div class='p-4 shadow-sm rounded' style='background-color:whitesmoke;'>
                @include('staff.forms.pd._formdetails')
            </div>
        </div>
    </div>
</div>
<script>
    function approveForm(id){
        token = $("input[name='_token']").val();
        $.post("/admin/forms/pd/"+id+"/approve", {_token: token});
        window.location.href = "/admin/forms";
    }
</script>
@endsection