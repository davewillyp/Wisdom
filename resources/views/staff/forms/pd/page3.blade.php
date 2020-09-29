@extends('staff.forms.pd.formlayout')
    @section('formcontent')
    <form id='formdata'>
    @CSRF 
    <input type='hidden' name='id' value='{{ $form->id }}'>
        <div class='row justify-content-center mb-2'>
            <div class='col shadow-sm rounded p-2' style='background-color:whitesmoke;'>                            
                <div class='p-2'>
                    <div class="form-group mr-3">
						<label for="exampleFormControlInput1" class="mr-3">Does your course have a fee?</label><br>
						<div class="form-check form-check-inline">
							<input class="form-check-input" name="fee" type="radio" id="radioFee1" value="1" onchange='toggleFee(this)' @if($expenses) @if($expenses->fee) checked @endif @endif> 
							<label class="form-check-label" for="radioFee1">Yes</label>
						</div>
						<div class="form-check form-check-inline">
							<input class="form-check-input" name="fee" type="radio" id="radioFee2" value="0" onchange='toggleFee(this)' @if(!$expenses)  checked @else @if(!$expenses->fee) checked @endif @endif>
							<label class="form-check-label" for="radioFee2">No</label>
						</div>
					</div>
                </div>                      
            </div>
        </div>                
        <div class='row justify-content-center mb-2' id='feeDetails' @if(!$expenses) style='display:none' @else @if(!$expenses->fee) style='display:none' @endif @endif>
            <div class='col shadow-sm rounded p-2' style='background-color:whitesmoke;'>                            
                <div class='p-2'>
                    <div class='form-group'>
                        <label class="form-label" for="inputAmount">Fee Amount</label>
                        <div class="row">
                            <div class="input-group mb-2 col-3">						
                                <div class="input-group-prepend">
                                    <div class="input-group-text">$</div>
                                </div>
                                <input type="text" class="form-control" id="inputAmount"  name='amount' value='@if($expenses) {{ $expenses->amount }} @endif'>
                                <div class="invalid-feedback">                        
                                </div>		
                            </div>						
                        </div>
                    </div>
					<div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="inputEmail4">Course Fee Invoiced?</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="invoiced" id="radioInvoiced1" value="1" @if($expenses) @if($expenses->invoiced) checked @endif @endif>
                                <label class="form-check-label" for="radioInvoiced1">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="invoiced" id="radioInvoiced2" value="0" @if(!$expenses) checked @else @if(!$expenses->invoiced) checked @endif @endif>
                                <label class="form-check-label" for="radioInvoiced2">No</label>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputPassword4">Payment Required before Start?</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="before" id="radioPaymentStart1" value="1" @if($expenses) @if($expenses->before) checked @endif @endif>
                                <label class="form-check-label" for="radioPaymentStart1">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="before" id="radioPaymentStart2" value="0" @if(!$expenses) checked @else @if(!$expenses->before) checked @endif @endif>
                                <label class="form-check-label" for="radioPaymentStart2">No</label>
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
                        <label for="inputOutcome">Expenses you intend to claim:</label>
                        <textarea class="form-control form-control-sm" rows="3" name='claim'>@if($expenses) {{ $expenses->claim }} @endif</textarea>			
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
                url: "forms/pd/"+id+"/3",
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
function toggleFee(fee){    
    if($(fee).val() == 1){
        $('#feeDetails').fadeIn();
    }else{
        $('#feeDetails').fadeOut();
    }
}
</script>
@endsection