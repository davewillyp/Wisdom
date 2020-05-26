@extends('staff.layout')

@section('body')
<div class='row justify-content-between'>
    <div class='col-12'>
        <div class='mb-3' class='container'>
            <span style='font-size:20px;color:#003366;font-weight:bold'>Resource Bookings</span>
        </div>     
        <div class='container' style='color:#003366; border:1px solid silver; background:whitesmoke' id='calender'>
           @include('staff.bookings.calendar')
           <br>
        </div>        
    </div>
</div>
<br>
<div id="myModal" class="mymodal">

    <!-- Modal content -->
    <div class="mymodal-content" style='background: whitesmoke;'>
      <div class="myclose" >&times;</div><br>
      <div clas='container' id='modalcontent'>This is a test</div>
    </div>
  
  </div>
  
<script>
    function updateCalendar(week)
    {
        $.get("/staff/bookings/"+week,function(data){
            $('#calender').fadeOut('fast', function(){
                $('#calender').html(data);               
            });
            $('#calender').fadeIn('fast');
        },"html");   
        
    }
    function createBooking(date,period)
    {
        $.get("/staff/bookings/items",{date:date, period:period},function(data){
            $('#myModal').fadeIn("fast");
            $('#modalcontent').html(data);                         
        },"html");
           
    }
    function selectItem(id)
    {
        checkbox = $('#checkbox'+id);
        $('#icon'+id).toggle("fast");
        $('#div'+id).toggleClass('itemselected');
        checkbox.prop("checked", !checkbox.prop("checked"));        
    }

    function updateRepeat()
    {  
        checkbox = $('#repeatbox');
        $('#repeat').toggleClass("fa-square fa-check-square greenitem");
        checkbox.prop("checked", !checkbox.prop("checked"));       
      
    }

    function submitBooking(){       
        var data = $('#bookingform').serialize();
        $('#myModal').fadeOut("fast");
        $.post("/staff/bookings",data,function(data){                        
            $('#calender').fadeOut('fast', function(){
                $('#calender').html(data);               
            });
            $('#calender').fadeIn('fast');
        },"html"); 
    }

    function deleteBooking(id){               
        $('#deleteId').val(id);
        var data = $('#deleteForm').serialize();       
        $.post("/staff/bookings",data,function(data){                                        
                $('#deleteId').val('');
                $('#booking'+id).fadeOut("slow", function(){
                    $('#booking'+id).remove();
                });                                           
        },"html");      
    }

    function deleteAll(id, recurr)
    {   
        $.get("/staff/bookings/delete/"+id+"/"+recurr,{},function(data){
            $('#myModal').fadeIn("fast");
            $('#modalcontent').html(data);                         
        },"html");        
    }

    function deleteRecurr(id,recurrid)
    {   
        $('#recurrId').val(recurrid);
        $('#deleteId').val(id);
        var data = $('#deleteForm').serialize(); 
        $('#myModal').fadeOut("fast");
        $.post("/staff/bookings",data,function(data){                                    
            $('#booking'+id).fadeOut("slow", function(){
                $('#booking'+id).remove();
                $('#recurrId').val('');
                $('#deleteId').val('');
            });
        },"html"); 
    }

    function deleteThisBooking(id)
    {
        $('#myModal').fadeOut("fast");
        deleteBooking(id);
    }

    var modal = document.getElementById("myModal");
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