@foreach ($files as $file)
<div class='ml-1 wissidebutton'>
    <div class='row'>
        <div class='col-1'>
            <i class="fal fa-file"></i>
        </div>
        <div class='col-10' style='cursor:pointer' onclick="window.open('{{ $file->getWebUrl() }}')" title='{{ $file->getName() }}'>
        {{ substr($file->getName(),0,25) }}
        </div>        
    </div>                                  
</div>
@endforeach