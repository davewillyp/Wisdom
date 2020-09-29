@extends('staff.forms.excur.formlayout')
    @section('formcontent')
            <form id='formdata'>
            @CSRF 
            <input type='hidden' name='id' value='{{ $form->id }}'>
                <div class='row justify-content-center'>
                    <div class='col shadow-sm p-2 rounded' style='background-color:whitesmoke;'>                            
                        <div class='p-2'>
                            <div class="form-group">
                                <label for="inputCourseName"><h6>Name of Excursion</h6></label>
                                <input type="text" class="form-control form-control-sm" id="inputCourseName" name='excurname' value='{{ $form->excurname }}'>
                                <div class="invalid-feedback">                            
                                </div>
                            </div>                    
                        </div>                      
                    </div>
                </div>
                <br>
                <div class='row justify-content-center'>
                    <div class='col shadow rounded p-2' style='background-color:whitesmoke;'>                            
                        <div class='p-2'>
                            <div class="form-row">
                                <div class="form-group col-3">
                                    <label for="inputStartDate"><h6>Start Date</h6></label>
                                    <input type="date" class="form-control form-control-sm" id="inputStartDate" name='startDate' value='@if($form->startDate){{ $form->startDate->format('Y-m-d') }}@endif' > 
                                    <div class="invalid-feedback">                            
                                    </div>
                                </div>
                                <div class="form-group col-3">
                                    <label for="inputStartTime"><h6>Start Time</h6></label>
                                    <input type="time" class="form-control form-control-sm" id="inputStartTime" name='startTime' value='@if($form->startTime){{ $form->startTime->format('H:i') }}@endif'>
                                    <div class="invalid-feedback">                            
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="inputFinishDate"><h6>Finish Date</h6></label>
                                    <input type="date" class="form-control form-control-sm" id="inputFinishDate" name='finishDate' value='@if($form->finishDate){{ $form->finishDate->format('Y-m-d') }}@endif'>
                                    <div class="invalid-feedback">                            
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="inputFinishTime"><h6>Finish Time</h6></label>
                                    <input type="time" class="form-control form-control-sm" id="inputFinishTime" name='finishTime' value='@if($form->finishTime){{ $form->finishTime->format('H:i') }}@endif'>
                                    <div class="invalid-feedback">                           
                                    </div>
                                </div>
                            </div>                   
                        </div>                      
                    </div>
                </div>
                <br>
                <div class='row justify-content-center'>
                    <div class='col shadow-sm rounded p-2' style='background-color:whitesmoke;'>                            
                        <div class='p-2'>
                            <div class="form-group">
                                <label for="inputOutcome"><h6>Excursion Aim:</h6></label>
                                <textarea class="form-control form-control-sm" rows="3" id="inputAim" name='aim'>{{ $form->aim }}</textarea>
                                <div class="invalid-feedback">                        
                                </div>
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="inputLocation"><h6>Excursion Location:</h6></label>
                                <textarea class="form-control form-control-sm" rows="3" id="inputLocation" name='location'>{{ $form->location }}</textarea>
                                <div class="invalid-feedback">                        
                                </div>
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="inputActivities"><h6>
                                    Excursion Activities:
                                </h6></label>
                                <textarea class="form-control form-control-sm" rows="3" id="inputActivities" name='activities'>{{ $form->activities }}</textarea>
                                <div class="invalid-feedback">                        
                                </div>
                            </div>                  
                        </div>                      
                    </div>
                </div>
                <div class='row justify-content-center'>
                    <div class='col p-2 mt-2'>                      
                        <button class='btn btn-outline-primary float-right' type='button' onclick='submitPage({{ $form->id }})'>Save & Next</button>                
                    </div>
                </div>
            </form>        
<script>
function submitPage(id){
    form = $('#formdata').serialize();
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').html("");
    $.ajax({
            type: "POST",
            url: "forms/excur/"+id+"/1",
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
                    if ($('input[name*="' + key +'"]').length){
                      $('input[name*="' + key +'"]').addClass('is-invalid');
                      $('input[name*="' + key +'"]').next('.invalid-feedback').html(value);
                    } else {
                        $('textarea[name="' + key +'"]').addClass('is-invalid');
                      $('textarea[name="' + key +'"]').next().html(value); 
                    }
                 });
            }
    });    
}
</script>
@endsection