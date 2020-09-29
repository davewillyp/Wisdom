<form method='post' action='/staff/bookings' id='deleteForm'>
    @csrf
    @method('delete')
    <input type='hidden' name='id' id='deleteId'>    
    <input type='hidden' name='recurrid' id='recurrId'>
</form>
<table class='table table-borderless' style='color:#003366'>
    <tr>        
        <th class='text-center' colspan='7' style='background: whitesmoke;font-size:18px;font-weight: bold;'>@if ($weeks['prev'])<i class="fal fa-arrow-square-left arrow" style='padding-right:10px' onclick="updateCalendar('{{ $weeks['prev'] }}')"></i>@endif {{ $weeks['name'] }} @if ($weeks['next'])<i class="fal fa-arrow-square-right arrow" style='padding-left:10px' onclick="updateCalendar('{{ $weeks['next'] }}')"></i>@endif</th>
    </tr>             
    <tr class='text-center'>
        <th style='background: whitesmoke;'></th>
        @foreach($days as $day)
        <th style='background: whitesmoke;'><span>{{ \Carbon\Carbon::parse($day)->format('l M') }}</span><br><span style='font-size:12px;color:gray'>{{ \Carbon\Carbon::parse($day)->format('j F') }}</span></th>
        @endforeach
        <td style='background: whitesmoke;'></th>
    </tr>
    @foreach($bookings['periods'] as $booking)
    <tr>
        <td class='fixedsizeperiod text-left pl-1' style="vertical-align: middle;">
            {{ $booking['periodname'] }}<br>
            <span style='font-size:10px;color:gray;'><?=date("g:i a", strtotime($booking['periodstart']))?> - <?=date("g:i a", strtotime($booking['periodend']))?></span>
        </td>
        @foreach ($booking['days'] as $days)
        <td class='fixedsize' style='border:1px solid silver;background:gainsboro'>
            <div class='text-right p-1'>
                <i class="fal fa-plus addplus" onclick="createBooking('{{ $days['dayname'] }}',{{ $booking['periodid'] }})"></i> 
            </div>
            @if($days['bookings'])
                @foreach ($days['bookings'] as $daybooked)
                    @if(isset($daybooked['items']))
                    <div id="booking{{ $daybooked['bookingid'] }}" class='shadow-sm'>
                        <div class='row no-gutters' style='border-left:2px solid #003366;margin-bottom:1px;background:whitesmoke;padding:5px 5px 5px 5px;font-size:14'>                                    
                            <div class='col-auto my-auto'>
                            @if( $daybooked['recurrid']) <i class="fal fa-retweet"></i> @endif                            
                            {{ $daybooked['displayname'] }}
                            </div>  
                            <div class='col-auto pl-2 my-auto text-right'>
                            <span style='font-size: 14px;'>
                            @foreach($daybooked['items'] as $item)
                            <i class="{{ $item['icon'] }} fa-fw" title ="{{ $item['name'] }}" style="color:{{ $item['colour'] }};"></i>
                            @endforeach
                            </div>
                            <div class='col-2 my-auto text-right pr-1'>
                            @if ($daybooked['userid'] == session('seqtaId'))                    
                            <i class="fal fa-trash-alt pl-2 deletebin" style='cursor:pointer' onclick="@if( $daybooked['recurrid']) deleteAll({{ $daybooked['bookingid'] }},{{ $daybooked['recurrid'] }}) @else deleteBooking({{ $daybooked['bookingid'] }}) @endif"></i>
                            @endif
                            </span>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            @endif 
            
        </td>
        @endforeach
        <td class='fixedsizeperiod' style='background:whitesmoke'></td>
    </tr>
    @endforeach                
</table>
<br>
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