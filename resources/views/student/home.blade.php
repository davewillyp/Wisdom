@extends ('student.layout')

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

@if ($timetable OR $notices)
    @section ('body')    
    <div class='row justify-content-between'>
        @if ($timetable)
        <div class='col-6' style='margin-bottom:20px'>
            <div>
                <span style='font-size:20px;color:#003366'><i class="fal fa-calendar-alt timetable" style='cursor:pointer;margin-right:5px' title="SEQTA Timetable" onclick="window.open('https://teach.sjc.wa.edu.au/timetable/')"></i>Timetable</span>
                <span class='float-right' style='font-size:20px;color:#003366;'>
                    <span id="tt-today" class='timetable-btn-active' style='margin-right:8px' onclick="updateTimetable(this,'2020-04-08')">Today</span> 
                    <span id='tt-tomorrow' class='timetable-btn' onclick="updateTimetable(this,'2020-04-09')">Tomorrow</span>
                </span>
            </div>
            <br>
            <div id='timetable'>
                @include ('student._timetable')
            </div>
            <br>
        </div>
        @endif
        @if ($notices)
        <div class='col-6'>
            <div>
                <p style='font-size:20px;color:#003366'>Student Notices</p>
            </div>            
            @include ('student._notices')
            <br>
        </div>
        @endif
    </div>
    @endsection
@else
    @section ('body')
    <div class='row justify-content-center'>
        <div class='col-6 text-center'>
            <div style="font-size:80px;cursor:pointer">&#128540</div>
            <h1>No Classes or Notices!</h1>
            <br>
            <p style='font-size:20px'>You must be on holidays?</p>
            <br><br>        
        </div>
    </div>
    @endsection
@endIf