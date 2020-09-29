@extends('staff.forms.pd.formlayout')
    @section('formcontent')
    <form id='formdata'>
    @CSRF 
    <input type='hidden' name='id' value='{{ $form->id }}'>
        <div class='row justify-content-center mb-2'>
            <div class='col shadow-sm rounded p-2' style='background-color:whitesmoke;'>                            
                <div class='p-2'>
                    <div class="form-group mr-3">
						<label for="exampleFormControlInput1" class="mr-3">Do you require the College Car?</label><br>
						<div class="form-check form-check-inline">
							<input class="form-check-input" name="car" type="radio" id="radioCar1" value="1" onchange='toggleCar(this)' @if($logistics) @if($logistics->car) checked @endif @endif > 
							<label class="form-check-label" for="radioCar1">Yes</label>
						</div>
						<div class="form-check form-check-inline">
							<input class="form-check-input" name="car" type="radio" id="radioCar2" value="0" onchange='toggleCar(this)' @if(!$logistics) checked @else @if(!$logistics->car) checked @endif @endif>
							<label class="form-check-label" for="radioCar2">No</label>
						</div>
					</div>
                </div>                      
            </div>
        </div>
        <div class='row justify-content-center mb-2' id='carDates' @if(!$logistics) style='display:none' @else @if(!$logistics->car) style='display:none' @endif @endif>
            <div class='col shadow-sm rounded p-2' style='background-color:whitesmoke;'>                            
                <div class='p-2'>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label>Car Pickup Date</label>
                            <input type="date" class="form-control form-control-sm" name='carPickupDate' @if($logistics)@if($logistics->car)value='{{$logistics->pickupDate->format('Y-m-d')}}'@endif @endif> 
                            <div class="invalid-feedback">
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Car Pickup Time</label>
                            <input type="time" class="form-control form-control-sm" name='carPickupTime' @if($logistics)@if($logistics->car)value='{{$logistics->pickupTime->format('H:i')}}'@endif @endif'>
                            <div class="invalid-feedback">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label>Car Drop Off Date</label>
                            <input type="date" class="form-control form-control-sm" name='carDropoffDate' @if($logistics)@if($logistics->car)value='{{$logistics->dropoffDate->format('Y-m-d')}}'@endif @endif'>
                            <div class="invalid-feedback">
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Car Drop Off Time</label>
                            <input type="time" class="form-control form-control-sm" name='carDropoffTime' @if($logistics)@if($logistics->car)value='{{$logistics->dropoffTime->format('H:i')}}'@endif @endif'>
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
                    <div class="form-group mr-3">
						<label for="exampleFormControlInput1" class="mr-3">Do you require accommodation?</label><br>
						<div class="form-check form-check-inline">
							<input class="form-check-input" name="accommodation" type="radio" id="radioAccom1" value="1" onchange='toggleAccommodation(this)' @if($logistics) @if($logistics->accommodation) checked @endif @endif> 
							<label class="form-check-label" for="radioAccom1">Yes</label>
						</div>
						<div class="form-check form-check-inline">
							<input class="form-check-input" name="accommodation" type="radio" id="radioAccom2" value="0" onchange='toggleAccommodation(this)'  @if(!$logistics) checked @else @if(!$logistics->accommodation) checked @endif @endif>
							<label class="form-check-label" for="radioAccom2">No</label>
						</div>
					</div>
                </div>                      
            </div>
        </div>
        <div class='row justify-content-center mb-2' id='accommodationDates' @if(!$logistics) style='display:none' @else @if(!$logistics->accommodation) style='display:none' @endif @endif>
            <div class='col shadow-sm rounded p-2' style='background-color:whitesmoke;'>                            
                <div class='p-2'>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label>Arrival Date</label>
                            <input type="date" class="form-control form-control-sm" name='arrival' @if($logistics)@if($logistics->accommodation)value='{{$logistics->arrival->format('Y-m-d')}}' @endif @endif> 
                            <div class="invalid-feedback">
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Departure Date</label>
                            <input type="date" class="form-control form-control-sm" name='departure' @if($logistics)@if($logistics->accommodation)value='{{$logistics->departure->format('Y-m-d')}}' @endif @endif>
                            <div class="invalid-feedback">
                            </div>
                        </div>
                    </div>                    
                </div>                      
            </div>
        </div>                           
        <div class='row justify-content-center'>
            <div class='col p-2 mt-2'>                      
                <button class='btn btn-outline-primary float-right' type='button' onclick='submitPage({{ $form->id }})'>Save</button>                
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
                url: "forms/pd/"+id+"/4",
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
                        
                        $('input[name*="' + key +'"]').addClass('is-invalid');
                        $('input[name*="' + key +'"]').next('.invalid-feedback').html(value);
                       
                    });
                }
        });
    }    
function toggleCar(car){    
    if($(car).val() == 1){
        $('#carDates').fadeIn();
    }else{
        $('#carDates').fadeOut();
        $('input[name="carPickupDate"]').val('');
        $('input[name="carPickupTime"]').val('');
        $('input[name="carDropoffDate"]').val('');
        $('input[name="carDropoffTime"]').val('');

    }
}
function toggleAccommodation(accommodation){    
    if($(accommodation).val() == 1){
        $('#accommodationDates').fadeIn();
    }else{
        $('#accommodationDates').fadeOut();
        $('input[name="arrival"]').val('');
        $('input[name="departure"]').val('');
    }
}
</script>
@endsection