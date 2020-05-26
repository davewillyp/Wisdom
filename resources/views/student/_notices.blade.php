@foreach ($notices as $notice)
<div class='row'>
    <div class='col' >
        <div style='background-color:whitesmoke;color:#003366;border-left:3px solid purple'>
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
<br>
@endforeach