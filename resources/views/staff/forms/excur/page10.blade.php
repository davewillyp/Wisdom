@extends('staff.forms.excur.formlayout')
    @section('formcontent')
            <form id='parentForm'>
                @CSRF 
                <input type="hidden" id="formId" value='{{$form->id}}'>      
                
            </form>          
            <div class='row mb-2' >
                <div class='col'>                         
                    <div class='shadow-sm rounded p-2' style='background-color:whitesmoke'>    
                        <div class='p-2'>
                            <form id="letterForm" method="post" enctype="multipart/form-data">
                            @CSRF
                            <label for="fileCert"><h6>
                                Excursion Letter
                            </h6></label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="file" onchange="updateLabel(this)" id="letterFile">
                                <label id= "customFileCertLabel" class="custom-file-label" for="letterFile">Choose file</label>
                            </div>
                            <input type="hidden" name="type" value="letter">
                            </form>
                            <button class='btn btn-outline-primary mt-2 mb-2' type='button' onclick="uploadFile('letter')">Upload</button> 
                            <div id='letterResponse'>{!! $letter !!}</div>
                        </div> 
                    </div>                       
                </div>
                <div class='col'> 
                    <div class="shadow-sm rounded p-2" style='background-color:whitesmoke;'>                           
                        <div class='p-2'>
                        <form id="permForm" method="post" enctype="multipart/form-data">
                            @CSRF
                            <label for="fileCert"><h6>
                                Excursion Permission Form
                            </h6></label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="file" onchange="updateLabel(this)" id="permFile">
                                <label id= "customFileCertLabel" class="custom-file-label" for="permFile">Choose file</label>
                            </div>
                            <input type="hidden" name="type" value="perm">
                            </form>
                            <button class='btn btn-outline-primary mt-2 mb-2' type='button' onclick="uploadFile('perm')">Upload</button> 
                            <div id='permResponse'>{!! $perm !!}</div>
                        </div>
                    </div>                      
                </div>
            </div>
            <div class='row justify-content-center'>
                <div class='col p-2 mt-2'>                      
                    <button class='btn btn-outline-primary float-right' type='button' onclick='submitPage({{ $form->id }})'>Save</button>                
                </div>
            </div>                     
<script>
function submitPage(id){
    
    form = $('#parentForm').serializeArray();

    if($('#letterResponse').find('input').length){
        fileid = $('#letterResponse').find('input').val();
        console.log(fileid);
        form.push({name: "fileLetter", value: fileid });        
    }

    if($('#permResponse').find('input').length){
        fileid = $('#permResponse').find('input').val();
        console.log(fileid);
        form.push({name: "filePerm", value: fileid });        
    }

    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').html("");
    $.ajax({
            type: "POST",
            url: "forms/excur/"+id+"/10",
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
    formData = new FormData($('#'+type+'Form')[0]);
    file = $('#'+type+'File')[0].files[0];
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