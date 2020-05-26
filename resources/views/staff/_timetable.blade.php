
<div>
    <span style='font-size:20px;color:#003366'><i class="fal fa-calendar-alt timetable" style='cursor:pointer;margin-right:5px' title="SEQTA Timetable" onclick="window.open('https://teach.sjc.wa.edu.au/timetable/')"></i>Timetable</span>
    <span class='float-right' style='color:#003366;'>
    <span class='@if($isToday)menu-btn-active @else menu-btn @endif' style='margin-right:8px;font-size:18px;' onclick="getTimetable('{{date('Y-m-d') }}')">Today</span> 
    <span class='@if(!$isToday)menu-btn-active @else menu-btn @endif' onclick="getTimetable('{{ date("Y-m-d", strtotime('tomorrow')) }}')" style='font-size:18px;'>Tomorrow</span>
    </span>
</div>
<br>
<div id='timetable'>                     
    @foreach ($timetable as $period)
    @if ($period[0]->description)
        <div class='row no-gutters'>
            <div class='col-md-3 my-auto' style='color:#003366;margin-left:6px' >                     
                @if ($period[0]->thisPeriod & $isToday)<span><b>{{ $period[0]->period }}</b></span> 
                @else <span>{{ $period[0]->period }}</span>
                @endif
                <br>
                <span style='font-size:10px;color:gray'><?=date("g:i a", strtotime($period[0]->start))?> - <?=date("g:i a", strtotime($period[0]->end))?></span>                               
            </div>
            <div class='col-auto my-auto'>
                <i class="fad fa-arrow-right" style='margin-right:10px;font-size:15px;color: @if($period[0]->thisPeriod & $isToday) #003366 @else gainsboro @endif'></i>
            </div>
            
            <div class='col-md'>
                <div class='shadow' style='background-color:@if($period[0]->thisPeriod & $isToday) #E8E8F8 @else whitesmoke @endif;;border-left:4px solid <?php if ($period[0]->value) { echo $period[0]->value; } else { echo "smokewhite"; }?>;border-bottom:1px solid gainsboro;'>
                    <div class='container' style='color:#003366'>
                        @foreach ($period as $tt)
                        <div class='row'>
                            <div class='col my-auto' style='padding-bottom:5px;padding-top:5px;'>
                                <div>
                                    <b>{{ $tt->description }}</b>@if ($tt->code) <br> <span style='font-size:12px'> {{ $tt->code }} </span> @endif<br>                                   
                                    <span style='font-size:10px'>{{ $tt->room }}<span>
                                </div>
                            </div>
                            @if ($tt->id)
                                <div class="col-auto my-auto">
                                    <i class="fal fa-users-class course" style="cursor:pointer;font-size:18px" title="SEQTA Attendance" onclick="window.open('https://teach.sjc.wa.edu.au/#?page=/courses/{{ $tt->programme }}:{{ $tt->metaclass }}')"></i>
                                </div>
                                @if ($tt->programme)
                                <div class="col-auto my-auto">
                                    <i class="fal fa-book assess" style="padding-right:10px;cursor:pointer;font-size:18px" title="SEQTA Courses" onclick="window.open('https://teach.sjc.wa.edu.au/#?page=/assessments/{{ $tt->programme }}:{{ $tt->metaclass }}')"></i>    
                                </div>
                                @else
                                <div class="col-auto my-auto">
                                    <i class="fal fa-tasks" style="padding-right:10px;font-size:18px;color:whitesmoke"></i>    
                                </div>
                                @endif
                            @endif
                            @if ($tt->description == 'Duty')
                            <div class="col-auto my-auto">
                                <i class="fal fa-user-secret duty" style="padding-right:10px;cursor:pointer;font-size:18px" title="Duty" onclick="window.open('https://teach.sjc.wa.edu.au/#?page=/assessments/{{ $tt->programme }}:{{ $tt->metaclass }}')"></i>    
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>           
        </div> 
    @endif                
    @endforeach
</div>
<br>