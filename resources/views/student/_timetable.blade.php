<input type="hidden" id="timetableDate" value='{{ $thisDate->format('Y-m-d') }}'>
<div class='row no-gutters'>
    <div class='col-md-3'>
        <span style='font-size:20px;color:#003366;font-weight:bold'>Timetable</span>
    </div>
    <div class='col-auto my-auto'>
        <i class="fad fa-arrow-right" style='margin-right:10px;font-size:15px;color:gainsboro'></i>
    </div>
    <div class='col-md text-center my-auto'>
        @if($prevDate) <i class="fal fa-arrow-square-left arrow" style='padding-right:10px' onclick="getTimetable('{{ $prevDate->format('Y-m-d') }}')"></i> {{ $thisDate->format('l j F') }} @else Today  @endif  <i class="fal fa-arrow-square-right arrow" style='padding-left:10px' onclick="getTimetable('{{ $nextDate->format('Y-m-d') }}')"></i>
    </div>   
</div>
<br>
<div>
<?php $dropdownint = 0;?>
@foreach ($timetable as $tt) 
    <div class='row no-gutters mb-2'>
        <div class='col-md-3 my-auto' style='color:#003366;margin-left:6px' >                     
            @if ($tt->thisPeriod & $isToday)<span><b>{{ $tt->period }}</b></span> 
            @else <span>{{ $tt->period }}</span>
            @endif
            <br>
            <span style='font-size:10px;color:gray'><?=date("g:i a", strtotime($tt->start))?> - <?=date("g:i a", strtotime($tt->end))?></span>                               
        </div>
        <div class='col-auto my-auto'>
                <i class="fad fa-arrow-right" style='margin-right:10px;font-size:15px;color: @if($tt->thisPeriod & $isToday) #003366 @else gainsboro @endif'></i>
        </div>
        <div class='col-md shadow-sm'>
            <div class="rounded" style='background-color:@if($tt->thisPeriod & $isToday) #E8E8F8 @else whitesmoke @endif; @if($tt->value)border-left:4px solid {{ $tt->value }} @endif ;border-bottom:1px solid gainsboro;'>
                <div class='container' style='color:#003366'>
                    <div class='row'>
                        <div class='col my-auto' style='padding-bottom:5px;padding-top:5px;'>
                            <div>
                                <b>{{ $tt->description }}</b><br>
                                <span style='font-size:12px'> {{ $tt->title }} {{ $tt->surname }} </span><br>
                                <span style='font-size:10px'> {{ $tt->room }} </span><br>                                                                
                            </div>
                        </div>
                        @if($tt->quicklinks)
                        <div class="col-auto my-auto">                        
                            <i class="fal fa-link duty" style="padding-right:10px;cursor:pointer;font-size:18px" title="QuickLink" id="quicklink{{$dropdownint}}" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>    
                            <div class="dropdown-menu" aria-labelledby="quicklink{{$dropdownint}}">                                                                   
                                @foreach($tt->quicklinks as $link)
                                <div class='dropdown-item'><a href="{{$link->url}}" target="_blank">{{$link->name}} </a></div>
                                @endforeach                                                                                           
                            </div>                                                                                
                        </div>
                        @endif
                        @if ($tt->programme)
                        <div class="col-auto my-auto">
                            <i class="fal fa-book course" style="cursor:pointer;font-size:20px" title="SEQTA Courses" onclick="window.open('https://learn.sjc.wa.edu.au/#?page=/courses/{{ $tt->programme }}:{{ $tt->metaclass }}')"></i>
                        </div>
                        <div class="col-auto my-auto">
                            <i class="fal fa-tasks assess" style="padding-right:5px;cursor:pointer;font-size:20px" title="SEQTA Assessments" onclick="window.open('https://learn.sjc.wa.edu.au/#?page=/assessments/{{ $tt->programme }}:{{ $tt->metaclass }}')"></i>    
                        </div>
                        @endif
                        <?php $dropdownint++;?>
                    </div>
                </div>
            </div>
        </div>
    </div>                     
@endforeach
</div>
