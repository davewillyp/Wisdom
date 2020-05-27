<div class='text-right'>
    <span class='menu-btn ml-4' onclick='getEvents()'>Events</span>
    <span class='menu-btn ml-4' onclick='getNotices()'>Notices</span>
    <span class='menu-btn-active ml-4'>Papercut</span>
</div>
<br>
@foreach ($accounts as $account)
<div class='row no-gutters mb-1'>
    <div class='col-8' >
        <div class='shadow' style='background-color:whitesmoke;color:#003366;border-left:3px solid #00ae5b;border-bottom:1px solid gainsboro;'>
            <div class='container p-2'>
                <div class='row'>
                    <div class='col-1'>
                        <i class="fal fa-money-check-alt" style='font-size:18px'></i>
                    </div>
                    <div class='col' style='font-weight: bold;'>
                        {{ $account['name'] }}
                    </div>
                    <div class='col'>
                        <span >${{ abs(round($account['balance'],2)) }}</span>
                    </div>
                </div>
            </div>                       
        </div>                
    </div>           
</div>
@endforeach