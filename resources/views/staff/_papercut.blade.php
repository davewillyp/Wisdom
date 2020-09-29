@foreach ($accounts as $account)
<div class='ml-1'>
    <div class='row'>        
        <div class='col-5'>
            {{ $account['name'] }}
        </div>
        <div class='col-5'>
            <span style='font-size:14px'><i>${{ abs(round($account['balance'],2)) }}</i></span>
        </div>
    </div>                                  
</div>
@endforeach