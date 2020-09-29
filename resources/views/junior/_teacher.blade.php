<div class="container pt-3 pb-3">
    <div class="row justify-content-center">    
    @foreach ($teachers as $teacher)   
    <div class="col text-center">
        <img class='rounded-circle cropped1' src='junior/image/{{$teacher->code}}' alt="" onclick="openLinks({{ $teacher->id }})">
        <h6 style='color:whitesmoke'>{{$teacher->title.' '.$teacher->surname}}</h6>
    </div>        
    @endforeach
    </div>
</div>
