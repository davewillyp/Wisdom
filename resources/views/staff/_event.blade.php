<input type="hidden" id="timetableDate" value='{{ $thisDate->format('Y-m-d') }}'>
<div class='row no-gutters'>
    <div class='col-3'>
        <span style='font-size:20px;color:#003366;font-weight:bold'>Events</span>
    </div>    
    <div class='col-7 text-center my-auto'>
        @if($prevDate) <i class="fal fa-arrow-square-left arrow" style='padding-right:10px' onclick="getEvents('{{ $prevDate->format('Y-m-d') }}')"></i> {{ $thisDate->format('l j F') }} @else Today  @endif  <i class="fal fa-arrow-square-right arrow" style='padding-left:10px' onclick="getEvents('{{ $nextDate->format('Y-m-d') }}')"></i>
    </div>   
</div>
<br>
<div class='mb-4'>                         
    <div class='row no-gutters mb-2'>           
        <div class='col-md'>
        @if ($events)
            @foreach ($events as $event)                
            <div class='row mb-2'>            
                <div class='col'>
                    <div class='shadow-sm rounded' style="background-color: @if($event['isNow']) #E8E8F8 @else whitesmoke @endif ;border-left: 3px solid {{ $event['colour'] }};border-bottom:1px solid gainsboro;">
                        <div class='container' style='color:#003366'>                        
                            <div class='row'>
                                <div class='col my-auto'>
                                    <div class='pt-1 pb-1'>
                                        <b>{{ $event['name'] }}</b> @if($event['starttime']) - <span style='font-size:12px'> {{ date("g:i a", strtotime($event['starttime'])) }} @endif
                                        @if($event['endtime'])<span class='pl-1' style='font-size:12px'>- {{ date("g:i a", strtotime($event['endtime'])) }} @endif
                                        @if($event['location'])<br><span style='font-size:12px'>{{ $event['location'] }}</span>@endif
                                    </div>                                
                                </div>                                                      
                            </div>                        
                        </div>
                    </div>
                </div>
            </div> 
            @endforeach     
        @else
        <div class='row'>           
            <div class='col'>
                <div class='shadow-sm rounded' style="background-color:whitesmoke;border-bottom:1px solid gainsboro;">
                    <div class='container' style='color:#003366'>                        
                        <div class='row'>
                            <div class='col my-auto' style='padding-bottom:5px;padding-top:5px;'>
                                <div class='pt-1 pb-1'>
                                    <span style='color:gray'>No Events</span>
                                </div>                                
                            </div>                                                      
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
        @endif
        </div>           
    </div>                          
</div>

