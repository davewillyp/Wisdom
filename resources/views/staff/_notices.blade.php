<div class='row no-gutters'>
    <div class='col-md-3'>
        <span style='font-size:20px;color:#003366;font-weight:bold'>Notices</span>
    </div>   
</div>
<br>
<div>
@foreach ($notices as $notice)
    <div class='row mb-2'>
        <div class='col' >
            <div class='shadow-sm rounded' style='background-color:whitesmoke;color:#003366;border-left:3px solid {{$notice->colour}}'>
                <header class="container" style='padding-top:5px;padding-bottom:5px;'>
                    <span style='font-size:15px;padding-right:10px'><b>{{ $notice->title }}</b></span><br>
                    <span style='font-size:14px;padding-right:10px'>{{ $notice->stitle }} {{ $notice->surname }}</span>                       
                </header>
                <div class="container" style='padding-bottom:5px'>
                    <p>{!! $notice->contents !!}</p>                            
                </div>                   
            </div>                
        </div>           
    </div>    
@endforeach
</div>
