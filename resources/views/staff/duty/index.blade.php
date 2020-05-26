@extends('staff.layout')
@section('body')
<div class='row justify-content-between'>
    <div class='col-12'>
        <div class='mb-3' class='container'>
            <span style='font-size:20px;color:#003366;font-weight:bold'>Duty Roster</span>
        </div>
       
        <div id='duty'>
            @include('staff.duty._duty')
        </div>
    </div>
</div>
<br><br>
<div id="myModal" class="mymodal">
    <!-- Modal content -->
    <div class="mymodal-content-small" style='background: whitesmoke;'>
      <div class="myclose" >&times;</div><br>
      <div class='container text-center'>
            <span style='color:#003366;font-size:20px;font-weight:bold'>Search Staff</span>
            <br>
            <div class='row justify-content-center'>
                <div class='col-6 p-2 mb-3'>
                  <input type='text' class='form-control' id='userSearch' onkeyup='userSearch()'>
                </div>
            </div>          
      </div>
      <div class='container' id='userSearchResult'>    
      </div>
      
    </div>     
      
    </div>  
  
<script>
  function openDuty(day){
    $.get("/staff/duty/"+day,{},function(data){                        
            $('#duty').html(data);
        },"html");
  }

  function clearDutyInputs()
  {
    $('#userSearch').val('');
    $('#userSearchResult').html('');
    $('#dutyLocation').val('');
    $('#dutyPeriod').val('');
    $('#dutyName').val('');
    $('#dutyUser').val('');  
  }

  function updateDutyUser(user,name)
  {
    $('#dutyName').val(name);
    $('#dutyUser').val(user); 

    form = $('#dutyForm').serialize();

    $.post("/staff/duty",form,function(data){            
      $('#myModal').fadeOut('fast', function(){
        clearDutyInputs();
        $('#duty').html(data);                
      });          
    },"html"); 
  }

  function deleteDutyUser(location,period)
  {
    $('#dutyLocation').val(location);
    $('#dutyPeriod').val(period);
    $('#dutyName').val('delete');
    $('#dutyUser').val('delete');
    
    form = $('#dutyForm').serialize();

    $.post("/staff/duty",form,function(data){            
      $('#myModal').fadeOut('fast', function(){
        clearDutyInputs();
        $('#duty').html(data);                
      });          
    },"html")

  }

    function openDutySearch(location,period)
    {
        $('#dutyLocation').val(location);
        $('#dutyPeriod').val(period);

        $('#myModal').fadeIn('fast');        
        $('#userSearch').focus();        
    }

    function userSearch()
    {
      text = $('#userSearch').val();

      if (text != ''){
      $.get("/staff/duty/user/"+text,{},function(data){                        
            $('#userSearchResult').html(data);
        },"html");
      } else {
        $('#userSearchResult').html('');
      }
    }

    var modal = document.getElementById("myModal");
    var span = document.getElementsByClassName("myclose")[0];

    window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
    clearDutyInputs();
  }

  span.onclick = function() {
  modal.style.display = "none";
  clearDutyInputs();
}
}
</script>
@endsection
