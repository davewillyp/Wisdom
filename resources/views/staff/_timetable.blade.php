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
    @foreach ($timetable as $period)
    @if ($period[0]->description or $period[0]->booking)
        <div class='row no-gutters mb-2'>
            <div class='col-md-3' style='color:#003366;margin-left:6px;' >                     
                @if ($period[0]->thisPeriod & $isToday)<span><b>{{ $period[0]->period }}</b></span> 
                @else <span>{{ $period[0]->period }}</span>
                @endif
                <br>
                <span style='font-size:10px;color:gray'><?=date("g:i a", strtotime($period[0]->start))?> - <?=date("g:i a", strtotime($period[0]->end))?></span>                               
            </div>
            <div class='col-auto my-auto'>
                <i class="fad fa-arrow-right" style='margin-right:10px;font-size:15px;color: @if($period[0]->thisPeriod & $isToday) #003366 @else gainsboro @endif'></i>
            </div>            
            <div class='col-md shadow-sm'>
                <div class='rounded' style='background-color:@if($period[0]->thisPeriod & $isToday) #E8E8F8 @else whitesmoke @endif;;border-left:4px solid <?php if ($period[0]->value) { echo $period[0]->value; } else { echo "smokewhite"; }?>;'>
                    <div class='container' style='color:#003366'>
                        <div class='row'>
                            <div class='col'>
                            <?php $dropdownint = 0;
                                    $arrayInt = 1;
                                    $arraylength = count($period); ?>
                            @foreach ($period as $tt)
                                @if($tt->description)                                
                                <div class='row' style='@if($arrayInt != $arraylength)border-bottom:1px solid gainsboro;@endif'>                                    
                                    <div class='col my-auto pb-1 pt-1'>
                                        <div class='pb-2'>                                    
                                            <span @if($tt->staff_relieving == 217) style='color:red' @endif><b>{{ $tt->description }}</b></span>  @if ($tt->code) <br> <span style='font-size:12px'> {{ $tt->code }} </span> @endif<br>                                   
                                            <span style='font-size:10px'>{{ $tt->room }}<span>                                                                
                                        </div>                                  
                                    </div>         
                                    <div class="col-auto my-auto">                                        
                                        @if($tt->description != 'Duty' and $tt->description != '' and $tt->description !='Exam Supervision')
                                            <div>
                                                <i class="fal fa-link assess" style="padding-right:10px;cursor:pointer;font-size:18px" title="QuickLink" id="quicklink{{$dropdownint}}" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>    
                                                <div class="dropdown-menu" aria-labelledby="quicklink{{$dropdownint}}">
                                                    <span class="dropdown-item" onclick="openAddLink({{$tt->metaclass}},{{$tt->year}})" style='cursor:pointer'>Add QuickLink</span>                                                   
                                                    @if($tt->quicklinks)
                                                        <div class="dropdown-divider"></div>
                                                        @foreach($tt->quicklinks as $link)
                                                        <div class='dropdown-item'><a href="{{$link->url}}" target="_blank">{{$link->name}} </a><i class="fal fa-trash-alt wis-deletebin ml-3" onclick="deleteLink(this,{{$link->id}})"></i></div>
                                                        @endforeach                                                    
                                                    @endif
                                                </div>
                                            </div>                                       
                                        @endif                                                                       
                                    </div>
                                    @if($tt->programme)
                                    <div class="col-auto my-auto">                                                                                
                                            <div>
                                            <i class="fal fa-book assess" style="padding-right:10px;cursor:pointer;font-size:18px" title="SEQTA Courses" onclick="window.open('https://teach.sjc.wa.edu.au/programme/{{ $tt->programme }}/Planning?meta={{$tt->metaclass}}')"></i>    
                                            </div>                                                                                                                                                      
                                    </div>
                                    @endif
                                </div>
                                <?php $dropdownint++;
                                        $arrayInt++; ?>
                                @endif
                            @endforeach                            
                            </div>
                            @if ($period[0]->metaclass)
                            <div class="col-auto d-flex align-items-center">                                
                                @if ($period[0]->description == 'Duty')
                                <i class="fal fa-user-secret duty" style="padding-right:10px;cursor:pointer;font-size:18px" title="Duty" onclick="window.open('/staff/duty"></i>    
                                @else 
                                    <?php $string = 'https://teach.sjc.wa.edu.au/attendance/';
                                        $int = 0; ?>
                                    @foreach($period as $tt)                                   
                                        <?php 
                                            if($int != 0){
                                                $comma = ',';
                                            } else {
                                                $comma = '';
                                            }
                                            $string = $string . $comma . $tt->id;
                                            $int++;
                                        ?>
                                    @endforeach
                                        <?php $string = $string . '/roll?meta=' . $period[0]->metaclass?>
                                    <i class='fal fa-users-class course' style="padding-right:10px;cursor:pointer;font-size:18px" title="SEQTA Attendance" onclick="window.open('{{ $string }}')"></i>                                    
                                @endif                               
                            </div> 
                            @endif     
                        </div>
                        <div class="row">
                            <div class="col">
                            @if($period[0]->booking)                               
                                <div class='row' style='@if($period[0]->description)border-top:1px solid gainsboro;@endif'>                                    
                                    <div class='col my-auto pb-1 pt-1'>
                                        <div class='pt-2 pb-2'>                                    
                                            <span><b>Booking</b></span>                                                                                                                                         
                                        </div>                                  
                                    </div>                                             
                                    <div class="col-auto my-auto">                                        
                                        @foreach($period[0]->booking as $booking)
                                            <i class="{{ $booking->icon }} fa-fw m-1" style="color:{{ $booking->colour }};font-size:18px" title="{{ $booking->name }}"></i>
                                        @endforeach                                                                                                             
                                    </div>
                                </div>                               
                            @endif
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>           
        </div> 
    @endif                
    @endforeach
</div>
<br>