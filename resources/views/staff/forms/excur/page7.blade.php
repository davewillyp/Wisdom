@extends('staff.forms.excur.formlayout')
    @section('formcontent')
            <form id='formRisk'>
            @CSRF 
            <input type='hidden' name='id' value='{{ $form->id }}'>
                <div class='row justify-content-center mb-2'>
                    <div class='col shadow-sm p-2 rounded' style='background-color:whitesmoke;'>                                              
                        <div class='p-2'>
                            <h6>Risk Assessment</h6> 
                            <table class='table table-bordered table-striped' id='fullRiskTable'>
                                <thead>
                                    <th>Activity</th>
                                    <th>Hazard Identified</th>
                                    <th>Risk Level</th>
                                    <th>Elimination/Control Measures</th>
                                    <th></th>
                                </thead>
                                <tbody id="riskTable">
                                {!! $risks !!}
                                </tbody>
                            </table>
                            <span class=float-right><span class='pr-2'>Add Risk</span><i class="fal fa-plus-square wis-additem" style='color:green' onclick='addRiskRow()'></i></span>                                      
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
    form = $('#formRisk').serialize();
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').html("");
    $.ajax({
            type: "POST",
            url: "forms/excur/"+id+"/7",
            data: form,
            success: function(data) {
                $( "#formContent" ).html(data);
            },
            beforeSend: function(request) {
                request.setRequestHeader("X-Requested-With", "XMLHttpRequest");
            },
            error: function(data) {
                var errormsg = data.responseJSON;
                console.log(errormsg.errors);
                $.each(errormsg.errors, function( key, value ) {
                    row = parseInt(key.substr(key.indexOf('.')+1)) + 1;
                    name = key.substr(0, key.indexOf('.')); 
                    cell = $('#fullRiskTable tr:eq('+row+')').find('input[name*="' + name +'"]')
                    cell2 = $('#fullRiskTable tr:eq('+row+')').find('textarea[name*="' + name +'"]')
                    if (cell.length){
                        $(cell).addClass('is-invalid');                      
                    } else {
                        $(cell2).addClass('is-invalid');                      
                    }
                 });
            }
    });    
}
function addRiskRow(){
    $.get('/staff/forms/riskrow', function(data){
        $('#riskTable').append(data);        
    });  
}

function deleteRow(row){       
    $(row).closest('tr').fadeOut(function(){
        $(row).closest('tr').remove();       
    });       
}
</script>
@endsection