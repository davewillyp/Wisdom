<form id='dutyForm'>
    @CSRF
    <input type='hidden' id='dutyPeriod' name='period'>
    <input type='hidden' id='dutyLocation' name='location'>
    <input type='hidden' id='dutyName' name='name'>
    <input type='hidden' id='dutyUser' name='user'>
    <input type='hidden' id='dutyDay' name='day' value='{{ $day }}'>
</form>
<div style='width:100%;background:gainsboro;display:inline-block;border-top:1px solid silver;border-left:1px solid silver;border-right:1px solid silver';>
    <span class='p-3 text-center @if($day == 1) wistabactive @else wistab @endif' onclick='openDuty(1)'>Monday</span>
    <span class='p-3 text-center @if($day == 2) wistabactive @else wistab @endif' onclick='openDuty(2)'>Tuesday</span>
    <span class='p-3 text-center @if($day == 3) wistabactive @else wistab @endif' onclick='openDuty(3)'>Wednesday</span>
    <span class='p-3 text-center @if($day == 4) wistabactive @else wistab @endif' onclick='openDuty(4)'>Thursday</span>
    <span class='p-3 text-center @if($day == 5) wistabactive @else wistab @endif' onclick='openDuty(5)'>Friday</span>
</div>
<div style='color:#003366;background:whitesmoke;border-left:1px solid silver;border-right:1px solid silver;border-bottom:1px solid silver'>
<table class='table table-borderless' style='color:#003366'>
    <thead>
        <tr class='text-center' style='background:whitesmoke;'> 
            <th></th>
            @foreach($periods as $period)
            <th style='vertical-align:middle;'>{{ $period->name }}</th>
            @endforeach
        </tr>
    </thead>
    @foreach($duties as $duty)
    <tr>
        <td style='background:whitesmoke;'>{{ $duty['locationname'] }}</td> 
        @foreach($duty['periods'] as $period)           
        <td class='dutyfixed p-1' style='vertical-align:middle;background:gainsboro;border:1px solid silver;' >
            @if(isset($period['periodholderid']))
            <div class='shadow-sm'>
                <div class='row no-gutters' style='border-left:2px solid #003366;margin-bottom:1px;background:whitesmoke;font-size:14'>                                                                                          
                    <div class='col p-2'>
                        {{ $period['periodholdername'] }}            
                    </div>
                    <div class='col-3 p-2'>
                        <i class="fal fa-trash-alt pl-2 deletebin" style='cursor:pointer' onclick="deleteDutyUser({{ $duty['locationid'] }}, {{ $period['periodid'] }})"></i>        
                    </div>                                                        
                </div>
            </div>
            @else
            <div>
                <div class='row no-gutters' style='cursor:pointer' onclick='openDutySearch({{ $duty['locationid'] }}, {{ $period['periodid'] }})'>                                                                                          
                    <div class='col p-3'>
                                   
                    </div>                                                        
                </div>
            </div>
            @endif                           
        </td>
        @endforeach
    </tr>
    @endforeach      
</table>
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