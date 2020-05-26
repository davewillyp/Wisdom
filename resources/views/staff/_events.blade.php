<div>
    <span class='menu-btn-active mr-4'>Events</span>
    <span class='menu-btn mr-4' onclick='getNotices()'>Notices</span>
    <span class='menu-btn mr-4' onclick='getPapercut()'>Papercut</span>
</div>
<br>
<div class='mb-4'>                     
    @foreach ($events as $day)    
        <div class='row no-gutters mb-2'>
            <div class='col-2' style='color:#003366;margin-left:6px' >                     
                @if ($day['isToday'])<span><b>{{ $day['day'] }}</b></span> 
                @else <span>{{ $day['day'] }}</span>
                @endif
                <br>
                <span style='font-size:10px;color:gray'>{{ $day['date'] }}</span>                               
            </div>            
            
            <div class='col-md'>
            @if (isset($day['events']))
            @foreach ($day['events'] as $event)                
                <div class='row'>
                    <div class='col-auto my-auto'>
                        <i class="fad fa-arrow-right" style="font-size:15px;color: @if($day['isToday'] AND $event['isNow']) #003366 @else gainsboro @endif"></i>
                    </div> 
                    <div class='col'>
                        <div class='shadow' style="background-color: @if($event['isNow']) #E8E8F8 @else whitesmoke @endif ;border-left: 4px solid {{ $event['colour'] }};border-bottom:1px solid gainsboro;">
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
            <div class='shadow' style="background-color:whitesmoke;border-bottom:1px solid gainsboro;">
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
            @endif
            </div>           
        </div>                          
    @endforeach
</div>

