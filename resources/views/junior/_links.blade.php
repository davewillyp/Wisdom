<div class="row justify-content-center">
    @foreach($links as $link)
    <div class="col text-center">
        <div class="card top-link" style='width:110px;background-color:#003366' onclick="window.open('{{$link->url}}')">
            {!! $link->icon !!}                           
            <p class="card-text mt-2" style='color:whitesmoke'>{{ $link->name }}</p>
        </div>        
    </div>
    @endforeach
</div>
