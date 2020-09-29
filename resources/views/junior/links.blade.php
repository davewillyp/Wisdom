@foreach($links as $link)
<div class='row mb-2'>            
    <div class='col'>
        <div class='shadow-sm rounded wisbutton'>
            <div class='container' style='color:#003366' onclick="window.open('{{ $link->url }}')">                        
                <div class='row'>
                    <div class="col-auto my-auto">
                        <i class="fal fa-link fa-2x"></i>
                    </div>
                    <div class='col my-auto'>
                        <div class='p-3'>
                           <span style='font-size:20px'>{{$link->name}}</span>
                        </div>                                
                    </div>                                                      
                </div>                        
            </div>
        </div>
    </div>
</div> 
@endforeach