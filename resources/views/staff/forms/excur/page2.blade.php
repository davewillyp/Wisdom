@extends('staff.forms.excur.formlayout')
    @section('formcontent')
        <form id='reliefForm'>
            @CSRF 
            <input type='hidden' name='id' value='{{ $form->id }}'>
            <input type="date" name="startDate" value="{{ $form->startDate->format('Y-m-d') }}" style='display:none'>
            <input type="time" name="startTime" value="{{ $form->startTime->format('H:i')  }}" style='display:none'>
            <input type="date" name="finishDate" value="{{ $form->finishDate->format('Y-m-d')  }}" style='display:none'>
            <input type="time" name="finishTime" value="{{ $form->finishTime->format('H:i')  }}" style='display:none'>
            <input type="hidden" name="staff[]" id='thisStaff' value='40'>

            <div class='row justify-content-center'>
                <div class='col shadow-sm rounded p-2' style='background-color:whitesmoke;'>                            
                    <div class='p-2'>
                        <div class="form-group mr-3">
                            <label for="exampleFormControlInput1" class="mr-3"><h6>
                                Are you attending the excursion?
                            </h6></label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" name="attending" type="radio" id="radioAttending1" value="1" onchange='toggleAttending(this)'  @if($form->attending) checked @endif> 
                                <label class="form-check-label" for="radioAttending1">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" name="attending" type="radio" id="radioAttending2" value="0" onchange='toggleAttending(this)' @if(!$form->attending) checked @endif>
                                <label class="form-check-label" for="radioAttending2">No</label>
                            </div>
                        </div>
                        <div class="form-group mr-3" id='attendingRelief'>
                            <label for="exampleFormControlInput1" class="mr-3"><h6>
                                Do you require Relief?
                            </h6></label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" name="relief" type="radio" value="1" onchange='toggleRelief(this)' @if($youRelief) checked @endif> 
                                <label class="form-check-label" >Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" name="relief" type="radio" value="0" onchange='toggleRelief(this)' @if(!$youRelief) checked @endif>
                                <label class="form-check-label" >No</label>
                            </div>
                        </div>
                        <div class="form-group mr-3">
                            <label for="exampleFormControlInput1" class="mr-3"><h6>
                                Are other staff Attending?
                            </h6></label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" name="otherStaff" type="radio" id="radioAttending1" onchange='toggleOther(this)' value="1" @if($staffattending) checked @endif> 
                                <label class="form-check-label" for="radioAttending1">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" name="otherStaff" type="radio" id="radioAttending2" onchange='toggleOther(this)' value="0" @if(!$staffattending) checked @endif>
                                <label class="form-check-label" for="radioAttending2">No</label>
                            </div>
                        </div>
                    </div>                      
                </div>
            </div>
            <br>
            <div class='row justify-content-center' id='staffAttending' @if(!$staffrow) style='display:none;' @endif>
                <div class='col shadow-sm rounded p-2' style='background-color:whitesmoke;'>                            
                    <div class='p-2'>                        
                        <table  class='table table-striped table-bordered'>
                            <thead>
                                <th colspan='3'>Staff Attending</th>                                
                            </thead>
                            <tbody id='reliefTable'>
                            {!! $staffrow !!}
                            </tbody>                        
                        </table>
                        <span class=float-right><span class='pr-2'>Add Staff</span><i class="fal fa-user-plus wis-adduser" onclick='addReliefRow()'></i></span>                   
                    </div>                      
                </div>
            </div>
            <br>     
            <div class='row justify-content-center' id='reliefResult' @if(!$relief) style='display:none' @endif>
                <div class='col shadow-sm rounded p-2' style='background-color:whitesmoke;'>                            
                    <div class='p-2' id='reliefResultData'>
                            {!! $relief !!}           
                    </div>                      
                </div>
            </div>
            <br>        
            <div class='row justify-content-center'>
                <div class='col p-2 mt-2'>                      
                    <button class='btn btn-outline-primary float-right' type='button' onclick='submitPage({{ $form->id }})'>Save & Next</button>                
                </div>
            </div>            
        </form>        

<script>
function submitPage(id){
    
    form = $('#reliefForm').serialize();
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').html("");
    $.ajax({
            type: "POST",
            url: "forms/excur/"+id+"/2",
            data: form,
            success: function(data) {
                $( "#formContent" ).html(data);
            },
            beforeSend: function(request) {
                request.setRequestHeader("X-Requested-With", "XMLHttpRequest");
            },
            error: function(data) {
                var errormsg = data.responseJSON;                
                $.each(errormsg.errors, function( key, value ) {
                    row = parseInt(key.substr(key.indexOf('.')+1)) + 1;
                    name = key.substr(0, key.indexOf('.'));                    
                    if ($('input[name*="' + key +'"]').length){
                    $('input[name*="' + key +'"]').addClass('is-invalid');
                    $('input[name*="' + key +'"]').closest('.invalid-feedback').html(value);
                    }                   
                });
            }
    });       
}
function toggleRelief(relief){    
    if($(relief).val() == 1){
        $('#thisStaff').attr("name", "staffRelief[]" );
        getRelief();       
    }else{
        $('#thisStaff').attr("name", "staff[]" );
        getRelief();
    }
}

function getRelief(){

    formdata = $('#reliefForm').serialize();

    startDate = $('input[name="startDate"]').val();
    finishDate = $('input[name="finishDate"]').val();
    startTime = $('input[name="startTime"]').val();
    finishTime = $('input[name="finishTime"]').val();
    token = $('input[name="_token"]').val();
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').html("");

    $.ajax({
            type: "POST",
            url: "forms/reliefall",
            data: formdata,
            success: function(data) {
                if(data != 'false'){
                    $( "#reliefResultData" ).html(data);
                    $( "#reliefResult" ).fadeIn();
                } else {
                    $( "#reliefResult" ).fadeOut( function(){
                        $( "#reliefResultData" ).html("");   
                    });
                }                
            },
            beforeSend: function(request) {
                request.setRequestHeader("X-Requested-With", "XMLHttpRequest");
            },
            error: function(data) {
                var errormsg = data.responseJSON;               
                $.each(errormsg.errors, function( key, value ) {                    
                    if ($('input[name="' + key +'"]').length){
                      $('input[name="' + key +'"]').addClass('is-invalid');
                      $('input[name="' + key +'"]').next().html(value);
                    }
                 });
            }
    });    
}

function deleteReliefRow(row){       
    $(row).closest('tr').fadeOut(function(){
        $(row).closest('tr').remove();
        getRelief();
    });       
}

function addRelief(radio){
    if($(radio).val() == 1){        
        $(radio).closest("tr").find("select").attr("name", "staffRelief[]" );       
        if ($(radio).closest("tr").find("select").val() != 0){
            getRelief();           
        }        
    }else{
        $(radio).closest("tr").find("select").attr("name", "staff[]" ); 
        getRelief();        
    }    
}

function addReliefRow(){
    rownum = $('#reliefTable tr').length;
    $.get('/staff/forms/reliefrow/'+rownum, function(data){
        $('#reliefTable').append(data);
        $('.staffAttending').fadeIn();
    });    
}

function toggleOther(other){    
    if($(other).val() == 1){
        addReliefRow();
        $('#staffAttending').fadeIn();                        
    }else{
        $('#staffAttending').fadeOut(); 
        $('#reliefTable').html("");
        getRelief();
    }
}

function toggleAttending(radio){
    if($(radio).val() == 1){        
        $('#attendingRelief').fadeIn();
    }else{
        $('#attendingRelief').fadeOut();        
    }    
    
}

</script>
@endsection