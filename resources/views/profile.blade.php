

<div class='container'>
    <div class='row'>
        <div class='col pr-2 my-auto'>                  
            <i class="fal fa-arrow-circle-down" style='cursor:pointer;color:white;font-size:16px;display:none' onclick='openWelcome()' id='openWelcome'></i>
        </div>
        <div class='col-auto p-1 my-auto'>
            <span style='font-size:18px;color:white'> {{ session('givenName') }}</span>
        </div>
        <div class='col-auto p-1'>
            @if (isset($image)) <img class='rounded-circle' src='data:image/jpg;base64,{!! $image !!}' style='height:40px;border:3px solid whitesmoke'>
            @else<i class="fas fa-user-circle fa-2x"style='color:white'></i>
            @endif
        </div>        
    </div>    
</div>
