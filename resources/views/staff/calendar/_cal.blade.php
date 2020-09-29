<form id='deleteEvent'>
    @CSRF
    @method('delete')
    <input type='hidden' name='id' id='eventId'>
    <input type='hidden' name='termid' value='{{ $term->id }}'>
</form>
<table class='table table-borderless' style='color:#003366'>
    <tr>        
        <th class='text-center' colspan='8' style='font-size:18px;font-weight: bold;'>{{ $term->name }}</th>        
    </tr>             
    <tr class='text-center'>
        <th></th>
        @foreach($days as $day)
        <th style='background: whitesmoke;'><span>{{ $day }}</span></th>
        @endforeach
    </tr>
    @foreach($calendar as $cal)
    <tr>
        <td class='fixedsizeperiod text-left' style="vertical-align: middle;background:whitesmoke">
            <span class='pl-2'>{{ $cal['weekname'] }}</span><br>            
        </td>
        @foreach ($cal['day'] as $day)
        <td class='fixedsize' style='border:1px solid silver;background:gainsboro;'>
            <div class='text-left p-1'>
                <span style='font-size:12px'>{{ $day['datetext'] }}</span>
                @if (session('seqtaId') == 40)
                    <span class='float-right'><i class="fal fa-plus addplus" onclick="openEvent('{{ $day['date'] }}')"></i> </span>
                @endif
            </div>            
            @if (isset($day['events']))
                @foreach ($day['events'] as $event)                    
                <div class='shadow-sm' id="event{{ $event['id'] }}">
                    <div class='row no-gutters' style='border-left:3px solid {{ $event['colour'] }};margin-bottom:1px;background:whitesmoke;font-size:14'>                                                                                          
                        <div class='col-10 p-1'>
                            {{ $event['name'] }}
                        </div>
                        @if (session('seqtaId') == 40)
                        <div class='col-2 my-auto'>
                            <span><i class="fal fa-trash-alt pl-2 deletebin" style='cursor:pointer' onclick="deleteEvent({{ $event['id'] }})"></i></span>
                        </div>
                        @endif
                    </div>
                </div>                   
                @endforeach
            @endif                      
        </td>
        @endforeach
        <td class='fixedsizeperiod' style="background:whitesmoke;"></td>
    </tr>
    @endforeach                
</table>
<br>
<div id="addEventModal" class="mymodal">
    <!-- Modal content -->
    <div class="mymodal-content" style='background: gainsboro;'>
    <div class="myclose" >&times;</div><br>
      <div clas='container' id='modalcontent'></div>
    </div>  
</div>
<script>
    $(document).ready(function() {
        $("td").hover(function() {
            var index = $(this).index();
            index = index + 1;
            if (index > 1){
                $("th:nth-child("+index+")" ).css( "background", "#E8E8F8" );
                $(this).closest('tr').find('td:first').css( "background", "#E8E8F8" );
            }
        }, function() {
            var index = $(this).index();
            index = index + 1;
            $("th:nth-child("+index+")" ).css( "background", "none" );
            $(this).closest('tr').find('td:first').css( "background", "none" );    
        });
    });
</script>
