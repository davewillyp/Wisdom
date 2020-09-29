@extends('staff.layout')
@section('body')
@CSRF
<div class='container-fluid'>
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb" style='background-color:whitesmoke;'>
        <li class="breadcrumb-item"><a href="/staff">Home</a></li>    
        <li class="breadcrumb-item"><a href="/admin">Admin</a></li>
        <li class="breadcrumb-item"><a href="/admin/forms">Forms</a></li>
        <li class="breadcrumb-item active">{{$form->excurname}}</li>
    </ol>
    </nav>
    <div class='row'>
        <div class='col-3'>
            <div class="mr-3">
                <div class='p-4 shadow-sm rounded' style='background-color:whitesmoke;'>
                    <h4>File Attachments</h4>  
                    <br>               
                    @if($fileCoc)
                    <div class='mb-3'>
                    <h6>Provider Certificate of Currancy</h6>
                    {!! $fileCoc !!}
                    </div>
                    @endif
                    @if($fileEp)
                    <div class='mb-3'>
                    <h6>Provider Emergency Plan</h6>
                    {!! $fileEp !!}
                    </div>
                    @endif
                    @if($fileCare)
                    <div class='mb-3'>
                    <h6>Care Plans</h6>
                    {!! $fileCare !!}
                    </div>
                    @endif
                    @if($fileErp)
                    <div class='mb-3'>
                    <h6>Emergency Response Plan</h6>
                    {!! $fileErp !!}
                    </div>
                    @endif
                    @if($fileCoc)
                    <div class='mb-3'>
                    <h6>Parent Letter</h6>
                    {!! $fileLetter !!}
                    </div>
                    @endif     
                    @if($filePerm)
                    <div class='mb-3'>
                    <h6>Permission Form</h6>
                    {!! $filePerm !!}
                    </div>
                    @endif 
                                   
                </div>
                <br>
                <div class='p-4 shadow-sm rounded' style='background-color:whitesmoke;'>
                <button type="button" class="btn btn-outline-secondary" onclick="window.open('/staff/forms/excur/{{$form->id}}/pdf')"><i class="fal fa-file-pdf pr-2" style="color:red"></i>Download / Print Form</button>                 
                @if($form->status == 2)
                <button type="button" class="btn btn-outline-primary mt-3" onclick="approveForm({{$form->id}})">Approve Form</button>
                @elseif($form->status == 4)
                <button type="button" class="btn btn-outline-success mt-3" disabled>Form Approved</button>
                @endif
                </div>
            </div>                 
        </div>
        <div class="col-9">
            <div class='p-4 shadow-sm rounded' style='background-color:whitesmoke;'>
                @include('staff.forms.excur._formdetails')
            </div>
        </div>
    </div>
</div>
<script>
    function approveForm(id){
        token = $("input[name='_token']").val();
        $.post("/admin/forms/excur/"+id+"/approve", {_token: token});
        window.location.href = "/admin/forms";
    }
</script>
@endsection