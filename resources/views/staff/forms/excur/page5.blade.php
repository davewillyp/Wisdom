@extends('staff.forms.excur.formlayout')
    @section('formcontent')
            <form id='studentForm'>
                @CSRF
                <input type="hidden" id="formId" value="{{ $form->id }}">   
            </form>      
                <div class='row justify-content-center mb-2'>
                    <div class='col shadow-sm rounded p-2' style='background-color:whitesmoke;'>                            
                        <div class='p-2'>
                            <div class="form-group mr-3">
                                <label for="exampleFormControlInput1" class="mr-3"><h6>
                                    Add Students By
                                </h6></label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" name='addBy' type="radio" id="radioAddBy1" value="1" onchange='toggleAddBy(this)'> 
                                    <label class="form-check-label" for="radioAddBy1">Class</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" name='addBy' type="radio" id="radioAddBy2" value="2" onchange='toggleAddBy(this)'>
                                    <label class="form-check-label" for="radioAddBy2">Year</label>
                                </div>                        
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" name='addBy' type="radio" id="radioAddBy3" value="3" onchange='toggleAddBy(this)'>
                                    <label class="form-check-label" for="radioAddBy3">Manual</label>
                                </div>
                            </div>                   
                        </div>                      
                    </div>
                </div>                
                <div class='row justify-content-center mb-2' id='addByClass' style='display:none'>
                    <div class='col shadow-sm rounded p-2' style='background-color:whitesmoke;'>                            
                        <div class='p-2'>
                            <form id='classForm'>                        
                                <table id='classTable' class='table'>
                                    
                                </table>
                                <span class=float-right><i class="fal fa-plus" style='color:green' onclick='getClassRow()'></i></span>                   
                            </form>                                                
                        </div>                      
                    </div>
                </div>
                <div class='row justify-content-center mb-2' id='addByYear' style='display:none'>
                    <div class='col shadow-sm rounded p-2' style='background-color:whitesmoke;'>                            
                        <div class='p-2'>
                            <form id='yearForm'>                        
                                <table id='yearTable' class='table'>                            

                                </table>
                                <span class=float-right><i class="fal fa-plus" style='color:green' onclick='getYearRow()'></i></span> 
                            </form>                                  
                        </div>                      
                    </div>
                </div>
                <div class='row justify-content-center mb-2' id='addByManual' style='display:none'>
                    <div class='col shadow-sm rounded p-2' style='background-color:whitesmoke;'>                            
                        <div class='p-2'>
                            <form id='manualForm'>                        
                                <table class='table'>                            
                                    <tr>
                                        <td><input class='form-control form-control-sm' type="text" name="student" onkeyup="getManual();" placeholder='Type to search student' id='studentInput'></td>
                                    </tr>
                                </table>                        
                            </form>                            
                            <table class='table' id='manualResult' style='display:none'>                               
                            </table>                                  
                        </div>                      
                    </div>
                </div>                   
                <div class='row justify-content-center' id='students'>
                    <div class='col shadow-sm rounded p-2' style='background-color:whitesmoke;'>                            
                        <div class='p-2'>
                            <div class='mb-3'><h6>
                                Students Attending
                            </h6></div>
                            <form id="studentAttending">
                                @CSRF
                                <table class='table table-bordered table-striped'>
                                    <thead>
                                        <tr>
                                            <th>First Name</th>
                                            <th>Surname</th>
                                            <th>School Year</th>
                                            <th>Roll Group</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id='studentResult'>
                                        {!! $students !!}
                                    </tbody>
                                </table> 
                            </form>                   
                        </div>                      
                    </div>
                </div>
                <br>        
                <div class='row justify-content-center'>
                    <div class='col p-2 mt-2'>                      
                        <button class='btn btn-outline-primary float-right' type='button' onclick='submitPage({{ $form->id }})'>Save & Next</button>                
                    </div>
                </div>        
<script>
function submitPage(id){
    
    form = $('#studentAttending').serialize();
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').html("");
    $.ajax({
            type: "POST",
            url: "forms/excur/"+id+"/5",
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
function toggleAddBy(add){    
    if($(add).val() == 1){
        $('#addByYear').hide();
        $('#addByManual').hide();
        $('#yearTable').html("");              
        getClassRow()        
               
    } else if($(add).val() == 2){
        $('#addByClass').hide();
        $('#classTable').html("");  
        $('#addByManual').hide();        
        getYearRow();
        
        
    } else if($(add).val() == 3){
        $('#addByClass').hide();
        $('#classTable').html("");         
        $('#addByYear').hide();
        $('#yearTable').html("");  
        $('#addByManual').show();       
    }
}

function getClassRow(){
    $.get('/staff/forms/studentrow/1', function(data){
        $('#classTable').append(data);
        $('#addByClass').show();         
    });  
}

function getYearRow(){
    $.get('/staff/forms/studentrow/2', function(data){
        $('#yearTable').append(data); 
        $('#addByYear').show();       
    });  
}

function getClass(){
    form = $('#classForm').serializeArray();
    token = $('input[name="_token"]').val()
    form.push({ name: "_token", value: token});
    $.post('/staff/forms/students/1', form ,function(data){
        $('#studentResult').html(data);        
    });   
}

function getYear(){
    form = $('#yearForm').serializeArray();
    token = $('input[name="_token"]').val()
    form.push({ name: "_token", value: token});
    $.post('/staff/forms/students/2', form ,function(data){
        $('#studentResult').html(data);        
    });   
}

function getManual(){
    if($('#studentInput').val() != ""){
    form = $('#manualForm').serializeArray();
    token = $('input[name="_token"]').val()
    form.push({ name: "_token", value: token});
    $.post('/staff/forms/studentslist', form ,function(data){
        $('#manualResult').html(data);
        $('#manualResult').fadeIn();        
    });   
    } else {
        $('#manualResult').fadeOut(function(){
            $('#manualResult').html(""); 
        });  
        
    }
}

function getStudent(id){
    form = $('#yearForm').serializeArray();
    token = $('input[name="_token"]').val()
    form.push({ name: "_token", value: token});
    form.push({ name: "student", value: id})
    $.post('/staff/forms/students/3', form ,function(data){
        $('#studentResult').append(data);        
    });   
}

function deleteStudent(row){       
    $(row).closest('tr').fadeOut(function(){
        $(row).closest('tr').remove();        
    });       
}

function deleteClassRow(row){       
    $(row).closest('tr').fadeOut(function(){
        $(row).closest('tr').remove();
        getClass();        
    });       
}

function deleteYearRow(row){       
    $(row).closest('tr').fadeOut(function(){
        $(row).closest('tr').remove();
        getYear();        
    });       
}

function addExpensesRow(){    
    id = $('#formId').val();
    $.get('/staff/forms/expenserow/'+id, function(data){
        $('#expenseTable').append(data);
        $('.expenseItem').fadeIn();
    });    
}

</script>
@endsection