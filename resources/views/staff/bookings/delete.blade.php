<div class='row justify-content-center mb-4' style='color:#003366'>            
    <div class='col-auto text-center'>        
        <span>Do you wish to delete all occurrences or just this instance?</span>    
    </div>           
</div>
<br>
<div class='row justify-content-around text-center' style='color:#003366'>
    <div class='col-auto'>
        <div class='p-2 wisbutton' style='font-size:16px' onclick='deleteRecurr({{ $id }},{{ $recurr }})'>Delete All</div>
    </div>
    <div class='col-auto'>
        <div class='p-2 wisbutton' style='font-size:16px' onclick='deleteThisBooking({{ $id }})'>Delete This Instance</div>
    </div>
</div>