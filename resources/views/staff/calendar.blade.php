@extends('staff.layout')

@section('body')
<div class='row justify-content-between'>
    <div class='col-12'>
        <div class='mb-3' class='container'>
            <span style='font-size:20px;color:#003366;font-weight:bold'>Calendar of Events</span>
        </div>     
        <div class='container-fluid' style='color:#003366;background:whitesmoke;border:1px solid silver' id='calendar'>
           @include('staff._cal')
        </div>        
    </div>
</div>
<script>
function deleteEvent(id)
{
    $('#eventId').val(id);    
    form = $('#deleteEvent').serialize();
    $.post("/staff/calendar",form,function(data){                                    
            $('#event'+id).fadeOut("slow", function(){
                    $('#event'+id).remove();
                });                                           
        },"html");
}
function createCalEvent()
{
    form = $('#newEvent').serialize();
    $('#addEventModal').fadeOut("fast");
        $.post("/staff/calendar/create",form,function(data){                        
            $('#calendar').html(data);
        },"html");
}
function openEvent(date)
{
    $.get("/staff/calendar/create/"+date,{},function(data){
        $('#addEventModal').fadeIn("fast");
        $('#modalcontent').html(data);                         
    },"html");
}
    
var modal = document.getElementById("addEventModal");
var span = document.getElementsByClassName("myclose")[0];

window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }

  span.onclick = function() {
  modal.style.display = "none";
}
}
</script>
@endsection