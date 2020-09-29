@extends('staff.layout')
@section('body')
<div class="container-fluid">
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb" style='background-color:whitesmoke;'>
        <li class="breadcrumb-item"><a href="/staff">Home</a></li>
        <li class="breadcrumb-item"><a href="/admin">Admin</a></li>
        <li class="breadcrumb-item active" aria-current="page">Forms</li>
    </ol>
    </nav>
    <form id='formOpen' method='POST' action='/admin/forms/pd/pdf' target="_blank">
        @CSRF
        <input type="hidden" name='formid' id='formid'>
    </form>
    <div class="container">
    <h5>Professional Development Forms</h5>
    <div class='row'>
        <div class='col p-3 mb-3 rounded shadow-sm' style='background-color:whitesmoke;'>
            <table class='table table-hover table-sm'>
                <thead>
                <tr class='table-active'>
                    <th>
                        Form Name
                    </th>
                    <th>
                        Created By
                    </th>
                    <th>
                        Date of Pd
                    </th>
                    <th>
                        Status
                    </th>                    
                </tr>
                </thead>
                <tbody>
                @foreach($pdForms as $form)
                    <tr style='cursor:pointer' onclick="openForm('pd',{{$form->id}})">
                        <td>{{$form->formname}}</td>
                        <td>{{$form->firstname.' '.$form->surname}}</td>
                        <td>{{$form->startDate->format('j F Y')}}</td>
                        <td><span class='badge badge-pill badge-{{$form->colour}} p-2'>{{$form->name}}</span>  </td>                        
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div> 
    </div>  
    <br>
    <div class="container">
    <h5>Excursion Forms</h5>
    <div class='row'>
        <div class='col p-3 mb-3 rounded shadow-sm' style='background-color:whitesmoke;'>
            <table class='table table-hover table-sm'>
                <thead>
                    <tr class='table-active'>
                        <th>
                            Form Name
                        </th>
                        <th>
                            Created By
                        </th>
                        <th>
                            Date of Excursion
                        </th>
                        <th>
                            Status
                        </th>                              
                    </tr>
                </thead>
                <tbody>
                @foreach($excurForms as $form)
                    <tr style='cursor:pointer' onclick="openForm('excur',{{$form->id}})">
                        <td>{{$form->formname}}</td>
                        <td>{{$form->firstname.' '.$form->surname}}</td>
                        <td>{{$form->startDate->format('j F Y')}}</td>
                        <td><span class='badge badge-pill badge-{{$form->colour}} p-2'>{{$form->name}}</span>  </td>                        
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    </div>    
</div>
<script>  
    function openForm(type,id){
        window.location.href = "/admin/forms/"+type+"/"+id;
    }
</script>
@endsection