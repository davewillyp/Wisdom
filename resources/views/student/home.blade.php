@extends('student.layout')
@section('welcometext')
<div style='color:whitesmoke;'>
    <div class='container'>
        <div class='row justify-content-center justify-content-md-between'>
            <div class='col-sm-auto'>
                <p class='text-center' style='font-size:22px;'><b>Good Afternoon {{ session('givenName') }}</b></p>
            </div>            
            <div class='col-sm-auto'>
                <p class='text-center' style='font-size:22px'><b><?=date('l j F')?></b></p>
            </div>
        </div>
    </div>
</div>
@endsection
@section('links')
<div class='container mb-3'>
    <div class="row justify-content-around" style='background-color:#003366;color:white'>
    @foreach ($links as $link)
        <div class="col-sm-2">
            <div class="card top-link text-center item" style='width:110px;background-color:#003366' onclick="window.open('{{ $link->url }}')">           
                @if ($mailNote > 0 & $link->name == 'Email')
                <span class="notify-badge"><strong>{{ $mailNote }}</strong></span>
                @endif
                @if ($seqtaNote > 0 & $link->name == 'SEQTA Learn')
                <span class="notify-badge"><strong>{{ $seqtaNote }}</strong></span>
                @endif
                {!! $link->icon !!}
                <p class="card-text">{{ $link->name }}</p>
            </div>
        </div>
    @endforeach
    </div>    
</div>
@endsection
@section('body')
<div class="container">
    <div class='row justify-content-between'>   
        <div class='col-6'>        
            <div id='timetable'>
                {!! $timetable !!}  
            </div>      
            <br>
        </div>          
        <div class='col-6' style='border-left:1px solid silver'>
            <div id="classDetail">
                @include('student._classdetail')
            </div>                        
            <br>
        </div>        
    </div>
</div>    
<script>
function getTimetable(date)
{            
    $.get("/student/timetable/"+date,{},function(data){                
        $('#timetable').html(data);                                     
    },"html");
}
</script>
@endsection   