<form method='post' action='/staff/bookings' id='bookingform'>
    @CSRF
    <input type='hidden' value='{{ $date }}' name='date'>
    <input type='hidden' value='{{ $period }}' name='period'>
    <div class='row justify-content-center mb-4' style='color:#003366'>
        @foreach($items as $item)       
        <div class='col-3 text-center'>        
            <i class="{{ $item['icon'] }} fa-3x" style="color:{{ $item['colour'] }}"></i>
            <br>
            <div class='mb-3' style='font-size:18px;font-weight:bold'>{{ $item['name'] }}</div>
            
            @if (isset($item['items']))
            @foreach($item['items'] as $thisitem)
            <div id='div{{ $thisitem['id'] }}' class="rounded @if ($thisitem['booked']) itembooked @else wisbutton shadow-sm @endif mb-2" >
                <div class='container'>
                    <div class='row' onclick="@if (!$thisitem['booked']) selectItem({{ $thisitem['id'] }}) @endif">
                        <div class='col my-auto p-2 text-left'>
                            {{ $thisitem['name'] }}
                        </div>
                        <div class='col-auto my-auto p-2'>
                            <i class="far fa-check" style='color:green;display:none;font-size:14px' id="icon{{ $thisitem['id'] }}"></i>
                            <input type='checkbox' style='display:none' value='{{ $thisitem['id'] }}' name='items[]' id="checkbox{{ $thisitem['id'] }}">                        
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @endif
        </div>
        @endforeach        
    </div>
    <br>
    <div class='row justify-content-around text-center' style='color:#003366'>
        <div class='col-auto' onclick='updateRepeat()'>
            <div class='p-2'><span style='font-size:16px'><i class="far fa-square pr-2" id='repeat'></i>Repeat Booking</span>
            <input id='repeatbox' type='checkbox' name='repeat' style='display:none'>
            </div>
        </div>
        <div class='col-auto'>
            <div class='p-2 wisbutton rounded' style='font-size:16px' onclick='submitBooking()'>Book Now!</div>
        </div>
    </div>
</form>
