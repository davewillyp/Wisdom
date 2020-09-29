@extends('staff.forms.excur.formlayout')
    @section('formcontent')
            <form id='logisticsForm'>
                @CSRF        
                <div class='row justify-content-center'>
                    <div class='col shadow-sm rounded p-2' style='background-color:whitesmoke;'>                            
                        <div class='p-2'>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label><h6>
                                        College Bus Required?
                                    </h6></label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="bus" id="radioBus1" value="1" @if($logistics) @if($logistics->bus) checked @endif @endif>
                                        <label class="form-check-label" for="radioBus1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="bus" id="radioBus2" value="0" @if(!$logistics) checked @else @if(!$logistics->bus) checked @endif @endif>
                                        <label class="form-check-label" for="radioBus2">No</label>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label><h6>
                                        College Car Required?
                                    </h6></label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="car" id="radioCar1" value="1"  @if($logistics) @if($logistics->bus) checked @endif @endif>
                                        <label class="form-check-label" for="radioCar1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="car" id="radioCar2" value="0" @if(!$logistics) checked @else @if(!$logistics->car) checked @endif @endif>
                                        <label class="form-check-label" for="radioCar2">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="transport"><h6>
                                    Transport Details
                                </h6></label>
                                <textarea class="form-control form-control-sm" rows="3" id="inputTransport" name='transport'> @if($logistics) {{ $logistics->transport }} @endif</textarea>
                            </div>                                                          
                        </div>                      
                    </div>
                </div>
                <br>         
                <div class='row justify-content-center'>
                    <div class='col shadow-sm rounded p-2' style='background-color:whitesmoke;'>                            
                        <div class='p-2'>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label><h6>
                                        Excursion Phone Required?
                                    </h6></label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="phone" id="radioPhone1" value="1"  @if($logistics) @if($logistics->phone) checked @endif @endif>
                                        <label class="form-check-label" for="radioPhone1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="phone" id="radioPhone2" value="0" @if(!$logistics) checked @else @if(!$logistics->phone) checked @endif @endif>
                                        <label class="form-check-label" for="radioPhone2">No</label>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label><h6>
                                        EPIRB Required?
                                    </h6></label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="epirb" id="radioEpirb1" value="1"  @if($logistics) @if($logistics->epirb) checked @endif @endif>
                                        <label class="form-check-label" for="radioEpirb1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="epirb" id="radioEpirb2" value="0" @if(!$logistics) checked @else @if(!$logistics->epirb) checked @endif @endif>
                                        <label class="form-check-label" for="radioEpirb2">No</label>
                                    </div>
                                </div>
                            </div>            
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
<br>
<script>
function submitPage(id){
    
    form = $('#logisticsForm').serialize();
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').html("");
    $.ajax({
            type: "POST",
            url: "forms/excur/"+id+"/4",
            data: form,
            success: function(data) {
                $( "#formContent" ).html(data);
            },
            beforeSend: function(request) {
                request.setRequestHeader("X-Requested-With", "XMLHttpRequest");
            }            
    });       
}
</script>
@endsection