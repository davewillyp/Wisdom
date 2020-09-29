@extends('staff.forms.pd.formlayout')
    @section('formcontent')
    <form id='formdata'>
    @CSRF 
    <input type='hidden' name='id' value='{{ $form->id }}'>
        <div class='row justify-content-center mb-2'>
            <div class='col shadow-sm rounded p-2' style='background-color:whitesmoke;'>                            
                <div class='p-2'>
                    <div class="form-group mr-3">
						<label for="exampleFormControlInput1" class="mr-3">Do you require Relief?</label><br>
						<div class="form-check form-check-inline">
							<input class="form-check-input" name="relief" type="radio" id="radioAttending1" value="1" onchange='toggleRelief(this)' @if($form->relief) checked @endif> 
							<label class="form-check-label" for="radioAttending1">Yes</label>
						</div>
						<div class="form-check form-check-inline">
							<input class="form-check-input" name="relief" type="radio" id="radioAttending2" value="0" onchange='toggleRelief(this)' @if(!$form->relief) checked @endif>
							<label class="form-check-label" for="radioAttending2">No</label>
						</div>
					</div>
                </div>                      
            </div>
        </div>       
        <div class='row justify-content-center mb-2' id='reliefDates' @if(!$form->relief) style='display:none' @endif>
            <div class='col shadow-sm rounded p-2' style='background-color:whitesmoke;'>                            
                <div class='p-2'>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="inputStartDate">Relief Start Date</label>
                            <input type="date" class="form-control form-control-sm" name='reliefStartDate' @if($dates) value='{{ $dates->startDate->format('Y-m-d') }}' @else @if($form->startDate) value='{{ $form->startDate->format('Y-m-d') }}' @endif @endif> 
                            <div class="invalid-feedback">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputStartTime">Relief Start Time</label>
                            <input type="time" class="form-control form-control-sm" name='reliefStartTime' @if($dates) value='{{ $dates->startTime->format('H:i') }}' @else @if($form->startTime) value='{{ $form->startTime->format('H:i') }}' @endif @endif>
                            <div class="invalid-feedback">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="inputFinishDate">Relief Finish Date</label>
                            <input type="date" class="form-control form-control-sm" name='reliefFinishDate' @if($dates) value='{{ $dates->finishDate->format('Y-m-d') }}' @else @if($form->finishDate) value='{{ $form->finishDate->format('Y-m-d') }}' @endif @endif>
                            <div class="invalid-feedback">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputFinishTime">Relief Finish Time</label>
                            <input type="time" class="form-control form-control-sm" name='reliefFinishTime' @if($dates) value='{{ $dates->finishTime->format('H:i') }}' @else @if($form->finishDate) value='{{ $form->finishTime->format('H:i') }}' @endif @endif>
                            <div class="invalid-feedback">                            
                            </div>
                        </div>
                    </div>
                    <div class=""><button class='btn btn-primary float-right btn-sm' type='button' onclick='getRelief({{ $form->id }})'>Generate Relief</button></div>							
                </div>                      
            </div>
        </div>        
        <div class='row justify-content-center mb-2' id='reliefResult' @if(!$relief) style='display:none' @endif>
            <div class='col shadow-sm rounded p-2' style='background-color:whitesmoke;'>                            
                <div class='p-2' id='reliefResultData'>
                {!! $relief !!}                    
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
    if($('input[name="relief"]').val() != 1){
        token = $('input[name="_token"]').val();
        $.post("forms/pd/"+id+"/2",{_token: token, relief: 0 }, function (data){
            $( "#formContent" ).html(data); 
        }
        , 'html');
    } else {
        form = $('#formdata').serialize();
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').html("");
        $.ajax({
                type: "POST",
                url: "forms/pd/"+id+"/2",
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
                        $('input[name*="' + key +'"]').closest('.invalid-feedback').html(value);
                        } else {
                            $('textarea[name="' + key +'"]').addClass('is-invalid');
                        $('textarea[name="' + key +'"]').next().html(value); 
                        }
                    });
                }
        });
    }    
}
function toggleRelief(relief){    
    if($(relief).val() == 1){
        $('#reliefDates').fadeIn();
    }else{
        $('#reliefDates').fadeOut();        
        $('#reliefResult').fadeOut(); 
        $('#reliefResultData').html(""); 

    }
}

function getRelief(id){
    startDate = $('input[name="reliefStartDate"]').val();
    finishDate = $('input[name="reliefFinishDate"]').val();
    startTime = $('input[name="reliefStartTime"]').val();
    finishTime = $('input[name="reliefFinishTime"]').val();
    token = $('input[name="_token"]').val();
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').html("");

    $.ajax({
            type: "POST",
            url: "forms/relief",
            data: {_token: token, reliefStartDate: startDate , reliefFinishDate: finishDate , reliefStartTime: startTime , reliefFinishTime: finishTime},
            success: function(data) {
                $( "#reliefResultData" ).html(data);
                $( "#reliefResult" ).fadeIn();
            },
            beforeSend: function(request) {
                request.setRequestHeader("X-Requested-With", "XMLHttpRequest");
            },
            error: function(data) {
                var errormsg = data.responseJSON;
                console.log(errormsg.errors);
                $.each(errormsg.errors, function( key, value ) {
                    console.log(key);
                    if ($('input[name="' + key +'"]').length){
                      $('input[name="' + key +'"]').addClass('is-invalid');
                      $('input[name="' + key +'"]').next().html(value);
                    }
                 });
            }
    });    
}

function deleteRelief(row){
    var i = row.parentNode.parentNode.rowIndex;
    row.parentNode.parentNode.parentNode.parentNode.deleteRow(i);
}
</script>
@endsection