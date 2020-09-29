@extends('staff.forms.pd.formlayout')
    @section('formcontent')
    <form id='formdata'>
    @CSRF 
    <input type='hidden' name='id' value='{{ $form->id }}'>
        <div class='row justify-content-center mb-2'>
            <div class='col shadow-sm p-2 rounded' style='background-color:whitesmoke;'>                            
                <div class='p-2'>
                    <div class="form-group">
                        <label for="inputCourseName">Name of Course</label>
                        <input type="text" class="form-control form-control-sm" id="inputCourseName" name='pdname' value='{{ $form->pdname }}'>
                        <div class="invalid-feedback">
                            Enter a name with at least 6 characters.
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputCourseName">Venue</label>
                        <input type="text" class="form-control form-control-sm" id="inputCourseVenue" name='venue' value='{{ $form->venue }}'>
                        <div class="invalid-feedback">
                            Enter a name with at least 6 characters.
                        </div>
                    </div>
                </div>                      
            </div>
        </div>        
        <div class='row justify-content-center mb-2'>
            <div class='col shadow rounded p-2' style='background-color:whitesmoke;'>                            
                <div class='p-2'>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="inputStartDate">Start Date</label>
                            <input type="date" class="form-control form-control-sm" id="inputStartDate" name='startDate' value='@if($form->startDate){{ $form->startDate->format('Y-m-d') }}@endif'> 
                            <div class="invalid-feedback">                            
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputStartTime">Start Time</label>
                            <input type="time" class="form-control form-control-sm" id="inputStartTime" name='startTime' value='@if($form->startTime){{ $form->startTime->format('H:i') }}@endif'>
                            <div class="invalid-feedback">                            
                            </div>
                        </div>
					</div>
					<div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="inputFinishDate">Finish Date</label>
                            <input type="date" class="form-control form-control-sm" id="inputFinishDate" name='finishDate' value='@if($form->finishDate){{ $form->finishDate->format('Y-m-d') }}@endif'>
                            <div class="invalid-feedback">                            
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="inputFinishTime">Finish Time</label>
                            <input type="time" class="form-control form-control-sm" id="inputFinishTime" name='finishTime' value='@if($form->finishTime){{ $form->finishTime->format('H:i') }}@endif'>
                            <div class="invalid-feedback">                           
                            </div>
                        </div>
					</div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="inputFinishDate">Total Hours in Attendance</label>
                            <input type="number" class="form-control form-control-sm" id="inputCourseHours" name='hours' value='{{ $form->hours }}'>
                            <div class="invalid-feedback">                            
                            </div>
                        </div>							
                    </div>			
                </div>                      
            </div>
        </div>        
        <div class='row justify-content-center mb-2'>
            <div class='col shadow-sm rounded p-2' style='background-color:whitesmoke;'>                            
                <div class='p-2'>
                    <div class="form-group">
                        <label for="inputOutcome">Desirable Outcome of Professional Development:</label>
                        <textarea class="form-control form-control-sm" rows="3" id="inputOutcome" name='outcome'>{{ $form->outcome }}</textarea>
                        <div class="invalid-feedback">                        
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <label>Which College Priorities is the PD addressing?</label><br>
                        <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="priority[]" value="Learning Technologies" @if($priorities) @if(in_array('Learning Technologies', $priorities)) checked @endif @endif>
                        <label class="form-check-label" for="inlineCheckbox1">Learning Technologies</label>
                        </div>
                        <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="priority[]" value="Religious Education" @if($priorities) @if(in_array('Religious Education', $priorities)) checked @endif @endif>
                        <label class="form-check-label" for="inlineCheckbox2">Religious Education</label>
                        </div>
                        <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="priority[]" value="Australian Curriculum" @if($priorities) @if(in_array('Australian Curriculum', $priorities)) checked @endif @endif>
                        <label class="form-check-label" for="inlineCheckbox3">Australian Curriculum</label>
                        </div>
                        <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="priority[]" value="Literacy and Numeracy" @if($priorities) @if(in_array('Literacy and Numeracy', $priorities)) checked @endif @endif>
                        <label class="form-check-label" for="inlineCheckbox3">Literacy and Numeracy</label>
                        </div>                        
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
<br>
<script>
function submitPage(id){
    form = $('#formdata').serialize();
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').html("");
    $.ajax({
            type: "POST",
            url: "forms/pd/"+id+"/1",
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
                    console.log(key);
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