<div class='row justify-content-center'>
    <div class='col shadow-sm p-2 rounded' style='background-color:whitesmoke;font-size:15px'>
        <div class="container">
        @foreach($status[1] as $stat)        
            <div class="row">
                <div class="col p-1" @if($stat->page) style='font-weight:bold' @else style="cursor:pointer" onclick="openPage({{$stat->number}}, {{$form->id}})"@endif>{{$stat->number}}. {{ $stat->name }}</div>
                <div class="col-1 my-auto"> @if($stat->status)<i class="far fa-check-circle" style='color:green'></i>@endif</div>
            </div>
        @endforeach
            <br>
        @if($status[0])
            <div class='text-center'>
                <button class="btn btn-outline-primary" type="button"  onclick="submitForm({{$form->id}})">Submit For Review</button>        
            </div>
        @endif
        </div>                       
    </div>
</div>
<script>
function openPage(page,id){
    $.get( "forms/excur/"+id+"/"+page, function(data) {
            $( "#formContent" ).html(data);
       } , "html"); 
}
function submitForm(id){
    token = $('input[name="_token"]').val();
    $.post("forms/excur/"+id+"/11", {_token:token});
    window.location.href = "forms/";   
}
</script>


