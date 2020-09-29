@extends('staff.layout')
@section('body')
<div class="container-fluid">
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb" style='background-color:whitesmoke;'>
        <li class="breadcrumb-item"><a href="/staff">Home</a></li>    
        <li class="breadcrumb-item"><a href="/staff/forms">Forms</a></li>
    </ol>
    </nav>
    <div id='formContent'>
        <form id='newForm' method='POST'>
            @CSRF           
        </form>
        <div class='container'>
            <div class='row'>
                <div class='col-3 mb-2'>
                    <div class="card wisbutton" style='height:175px;' onclick="newForm('pd')">
                        <div class="card-body text-center" style='display:flex'>
                            <div class="card-title" style='font-size:18px;margin:auto'><i class="fal fa-file-edit fa-2x" style='color:royalblue'></i><br><b>New Pd Form</b></div>                
                        </div>            
                    </div>
                </div>
                <div class='col-3 mb-2'>
                    <div class="card wisbutton" style='height:175px;' onclick="newForm('excur')">            
                    <div class="card-body text-center" style='display:flex'>
                            <div class="card-title" style='font-size:18px;margin:auto'><i class="fal fa-file-edit fa-2x" style='color:royalblue'></i><br><b>New Excursion Form</b></div>               
                        </div>            
                    </div>
                </div>
                @foreach($forms as $form)            
                <div class='col-3 mb-2'>
                    <div class="card wisbutton" style='height:175px'>                           
                        <div class="card-body" onclick="openForm('{{$form->type}}',{{ $form->id }})">
                            <div class="card-title" style='font-size:18px;margin-bottom:-5px' ><b>@if($form->formname)  {{ $form->formname }} @else <i class="fal fa-sparkles" style='color:royalblue'></i> New Form @endif</b></div>                                                              
                            <small>@if($form->startDate) {{ $form->startDate->format('l j F Y') }} @endif</small><br>
                            @if($form->formname)
                            <span class='badge badge-{{$form->colour}} p-2'>{{$form->name}}</span>                        
                            @endif
                        </div>
                        <div class='card-footer'>
                        <small class="text-muted">Created: {{ $form->created_at->format('j M Y g:ia') }}</small><span class='float-right'><i class="fal fa-trash-alt pl-2 deletebin" style='cursor:pointer' onclick="archiveForm({{ $form->id }},'{{$form->type}}',this)"></i></span>
                        </div>
                    </div>
                </div> 
                @endforeach
            </div>
        </div>
    </div>        
    <br>
    <br>
    <div id='loading' class='wis-loading-icon text-center'><i class="fad fa-circle-notch fa-spin fa-7x fa-fw"></i></div>
</div>
<script>
    function newForm(type){
        form = $('#newForm').serialize();        
        $.post( "forms/"+type, form ,function(data) {
            $( "#formContent" ).html(data);
       } , "html");
    }
    function openForm(type,id){
        $.get( "forms/"+type+"/"+id, function(data) {
            $( "#formContent" ).html(data);
       } , "html"); 
    }
    function openPage(type,id,page){
        $.get( "forms/"+type+"/"+id+"/"+page, function(data) {
            $( "#formContent" ).html(data);
       } , "html");
    }
    function archiveForm(id,type,box){
        form = $('#newForm').serializeArray();        
        form.push({name: '_method', value: 'DELETE'});
        $.post( "forms/"+type+"/"+id, form ,function(data) {            
            $(box).closest('div.col-3').fadeOut();
        } , "html");
    }
</script>
@endsection
