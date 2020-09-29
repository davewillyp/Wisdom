@extends('staff.layout')

@section('body')
<div class="container-fluid">
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb" style='background-color:whitesmoke;'>
        <li class="breadcrumb-item"><a href="/staff">Home</a></li>    
        <li class="breadcrumb-item"><a href="/staff/calendar">Calendar</a></li>
    </nav>
    <div class='row justify-content-center'>
        <div class='col-10'>           
            <div class='container-fluid shadow-sm rounded' style='background:whitesmoke;' id='calendar'>
            @include('staff.calendar._cal')
            </div>        
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