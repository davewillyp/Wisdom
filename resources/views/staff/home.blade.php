@extends ('staff.layout')

@section ('welcometext')
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

@section ('links')
<div class='container'>
    <div class="row justify-content-center" style='background-color:#003366;color:white'>
    @foreach ($links as $link)
        <div class="col-sm-2">
            <div class="card top-link text-center item" style='width:110px;background-color:#003366' onclick="window.open('{{ $link->url }}')">                           
                @if ($mailNote > 0 & $link->name == 'Email')
                <span class="notify-badge"><strong>{{ $mailNote }}</strong></span>
                @endif
                @if ($seqtaNote > 0 & $link->name == 'SEQTA')
                <span class="notify-badge"><strong>{{ $seqtaNote }}</strong></span>
                @endif
                {!! $link->icon !!}
                <p class="card-text">{{ $link->name }}</p>
            </div>
        </div>
    @endforeach
    </div>
</div>
<div class='container-fluid'>
    <div class='row'>
        <div class='col text-right mb-1'>
            <i class="fal fa-arrow-circle-up" style='cursor:pointer;color:white;font-size:16px' onclick='closeWelcome()' id='closeWelcome'></i>
        </div>    
    </div>
</div>
@endsection

@section ('body')       
<div class='row justify-content-between'>    
    <div class='col-6' style='margin-bottom:20px border-right:1px solid silver' id='timetable'>
        {!! $timetable !!}
    </div>        
    <div class='col-6' id='sidebar' style='border-left:1px solid silver'>            
        {!! $events !!}
    </div>    
</div>
<br><br>
<script>   
    function getPapercut()
    {                    
        $.get("/staff/papercut",{},function(data){                
            $('#sidebar').html(data);                                       
        },"html");
    }

    function getNotices()
    {               
        $.get("/staff/notices",{},function(data){                
            $('#sidebar').html(data);                                        
        },"html");
    }

    function getTimetable(date)
    {            
        $.get("/staff/timetable/"+date,{},function(data){                
            $('#timetable').html(data);                                     
        },"html");
    }

    function getEvents()
    {               
        $.get("/staff/events",{},function(data){                
            $('#sidebar').html(data);                                        
        },"html");
    }
</script>
@endsection


