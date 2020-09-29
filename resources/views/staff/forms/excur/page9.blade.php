@extends('staff.forms.excur.formlayout')
    @section('formcontent')
            <form id='erpForm'>
                @CSRF 
                <input type="hidden" id="formId" value='{{$form->id}}'>                  
                        
                <div class='row mb-2'>
                    <div class='col shadow-sm rounded p-2' style='background-color:whitesmoke;'>                            
                        <div class='p-2'>
                        <p class='wis-med'>Please note that during an exursion a nominated supervisor must have ready access to a list of names of participating students and their parent/guardian telephone numbers and a copy of the student medical details.

                            All teachers attending excursion must be briefed on Emergency Response Plan.

                            A plan must be enterd in the box provided or uploaded as a seperate document.</p>                                                         
                        </div>                      
                    </div>
                </div> 
                <div class='row mb-2'>
                    <div class='col shadow-sm rounded p-2' style='background-color:whitesmoke;'>                            
                        <div class='p-2'>
                            <div class="form-group">
                                <label><h6>
                                    Emergency Response Plan:
                                </h6></label>
                                <textarea class="form-control form-control-sm" rows="5" name='erpText'>{{ $text }}</textarea>
                                <div class="invalid-feedback">                        
                                </div>
                            </div>                                       
                        </div>                      
                    </div>
                </div>
            </form>          
            <div class='row mb-2' >
                <div class='col shadow-sm rounded p-2' style='background-color:whitesmoke'>                            
                    <div class='p-2'>
                        <form id="erpFileForm" method="post" enctype="multipart/form-data">
                        @CSRF
                        <label for="fileCert"><h6>
                            Upload Emergency Response Plan
                        </h6></label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="file" onchange="updateLabel(this)" id="erpFile">
                            <label id= "customFileCertLabel" class="custom-file-label" for="fileCert">Choose file</label>
                        </div>
                        <input type="hidden" name="type" value="erp">
                        </form>
                        <button class='btn btn-outline-primary mt-2 mb-2' type='button' onclick="uploadFile('erp')">Upload</button> 
                        <div id='erpResponse'>{!! $file !!}</div>
                    </div>                      
                </div>        
            </div>          
            <div class='row justify-content-center'>
                <div class='col p-2 mt-2'>                      
                    <button class='btn btn-outline-primary float-right' type='button' onclick='submitPage({{ $form->id }})'>Save & Next</button>                
                </div>
            </div>
        </div>
    </div>              
<script>
function submitPage(id){
    
    form = $('#erpForm').serializeArray();

    if($('#erpResponse').find('input').length){
        fileid = $('#erpResponse').find('input').val();        
        form.push({name: "file", value: fileid });        
    }

    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').html("");
    $.ajax({
            type: "POST",
            url: "forms/excur/"+id+"/9",
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

function uploadFile(type){
    formData = new FormData($('#erpFileForm')[0]);
    file = $('#erpFile')[0].files[0];
    formData.append('file', file, file.name);


    id = $('#formId').val();
        
        $.ajax({
            type: "POST",
            url: '/staff/forms/upload/'+id,
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                $( "#"+type+"Response" ).append(data);
            },
            beforeSend: function(request) {
                request.setRequestHeader("X-Requested-With", "XMLHttpRequest");
            }            
        });      

}

function updateLabel(input){
    file = input.value;
    file = file.replace(/.*[\/\\]/, '');
    if (file.length > 30){
        file = file.substr(0,30) + "..";
    }    
    input.nextElementSibling.innerHTML = file;
}

function deleteFile(deletebtn, id){
    form = $('#formId').val();
    token = $('input[name="_token"]').val();
    $.post("forms/excur/"+form+"/file/"+id, {_token:token, _method:'DELETE'}, function(data){       
        $(deletebtn).parent().parent().parent().fadeOut();
    });
}
</script>
@endsection