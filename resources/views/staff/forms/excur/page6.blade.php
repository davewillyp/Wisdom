@extends('staff.forms.excur.formlayout')
    @section('formcontent')
            <form id='riskForm'>
                @CSRF 
                <input type="hidden" id="formId" value='{{$form->id}}'>
        
                <div class='row justify-content-center mb-2'>
                    <div class='col shadow-sm rounded p-2' style='background-color:whitesmoke;'>                            
                        <div class='p-2'>
                            <div class="form-group mr-3">
                                <label for="exampleFormControlInput1" class="mr-3"><h6>
                                    Are staff attending with First Aid Certificate?
                                </h6></label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" name="firstaid" type="radio" id="radioFirstAid1" value="1" onchange='toggleFirstAid(this)' @if($firstAid) checked @endif> 
                                    <label class="form-check-label" for="radioFirstAid1">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" name="firstaid" type="radio" id="radioFirstAid2" value="0" onchange='toggleFirstAid(this)' @if($firstAid == false) checked @endif>
                                    <label class="form-check-label" for="radioFirstAid2">No</label>
                                </div>
                            </div>                                 
                        </div>                      
                    </div>
                </div>
            
                <div class='row justify-content-center mb-2'  @if($firstAid == false) style='display:none' @endif id='staffFirstAid'>
                    <div class='col shadow-sm rounded p-2' style='background-color:whitesmoke;'>                            
                        <div class='p-2'>                            
                            <table class='table table-striped table-bordered'>
                                <thead>
                                    <th>Staff with Firstaid</th>
                                </thead>
                                <tbody id='firstAidTable'>
                                {!! $firstAidStaff !!}
                                </tbody>                            
                            </table>
                            <span class=float-right><span class='pr-2'>Add Staff</span><i class="fal fa-user-plus wis-adduser" onclick='addStaffFirstAidRow({{$form->id}})'></i></span>                                                                                         
                        </div>                      
                    </div>
                </div>
                <div class='row justify-content-center mb-2'>
                    <div class='col shadow-sm rounded p-2' style='background-color:whitesmoke;'>                            
                        <div class='p-2'>                    
                            <div class="form-group mr-3">
                                <label for="exampleFormControlInput1" class="mr-3"><h6>
                                    Does your excursion contain water activities?
                                </h6></label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" name="bronze" type="radio" value="1" onchange='toggleBronze(this)' @if($bronze) checked @endif> 
                                    <label class="form-check-label" >Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" name="bronze" type="radio" value="0" onchange='toggleBronze(this)' @if($bronze == false) checked @endif>
                                    <label class="form-check-label" >No</label>
                                </div>
                            </div>                    
                        </div>                      
                    </div>
                </div>
                
                <div class='row justify-content-center mb-2' @if($bronze == false) style='display:none' @endif id='staffBronze'>
                    <div class='col shadow-sm rounded p-2' style='background-color:whitesmoke;'>                            
                        <div class='p-2'>                            
                            <table class='table table-bordered table-striped'>
                                <thead>
                                    <th>Staff with Firstaid</th>
                                </thead>
                                <tbody id='bronzeTable'>
                                {!! $bronzeStaff !!}
                                </tbody>                           
                            </table> 
                            <span class=float-right><span class='pr-2'>Add Staff</span><i class="fal fa-user-plus wis-adduser" onclick='addStaffBronzeRow({{$form->id}})'></i></span>                                                                              
                        </div>                      
                    </div>
                </div>       
                <div class='row justify-content-center mb-2'>
                    <div class='col shadow-sm rounded p-2' style='background-color:whitesmoke;'>                            
                        <div class='p-2'>
                            <div class="form-group">
                                <label><h6>
                                    Persons attending other than Staff or Students:
                                </h6></label>
                                <textarea class="form-control form-control-sm" rows="3" name='attending'>{{ $attending }}</textarea>
                                <div class="invalid-feedback">                        
                                </div>
                            </div>                   
                            <div class="form-group">
                                <label><h6>
                                    Outside Instructors:
                                </h6></label>
                                <textarea class="form-control form-control-sm" rows="3" name='instructors'>{{ $instructors }}</textarea>
                                <div class="invalid-feedback">                        
                                </div>
                            </div>
                        </div>                      
                    </div>
                </div>
            </form>            
            <div class='row mb-2' >
                <div class='col'>
                    <div class="">
                        <div class="shadow-sm rounded p-2" style='background-color:whitesmoke'>
                            <div class='p-2'>
                                <form id="cocForm" method="post" enctype="multipart/form-data">
                                @CSRF
                                <label for="fileCert"><h6>
                                    Upload Certificate of Currency for Providers
                                </h6></label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="file" onchange="updateLabel(this)" id="cocFile">
                                    <label id= "customFileCertLabel" class="custom-file-label" for="fileCert">Choose file</label>
                                </div>
                                <input type="hidden" name="type" value="coc">
                                </form>
                                <button class='btn btn-outline-primary mt-2 mb-2' type='button' onclick="uploadFile('coc')">Upload</button> 
                                <div id='cocResponse'>{!! $cocFiles !!}</div>
                            </div>     
                        </div>
                    </div>                                    
                </div>
                <div class='col'>
                    <div class="">
                        <div class="shadow-sm rounded p-2" style='background-color:whitesmoke'>
                            <div class='p-2'>
                            <form id="epForm" method="post" enctype="multipart/form-data">
                                @CSRF
                                <label for="fileCert"><h6>
                                    Upload Emergency Plan for Providers
                                </h6></label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="file" onchange="updateLabel(this)" id="epFile">
                                    <label id= "customFileCertLabel" class="custom-file-label" for="epFile">Choose file</label>
                                </div>
                                <input type="hidden" name="type" value="ep">
                                </form>
                                <button class='btn btn-outline-primary mt-2 mb-2' type='button' onclick="uploadFile('ep')">Upload</button> 
                                <div id='epResponse'>{!! $epFiles !!}</div>
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
<script>
function submitPage(id){
    
    form = $('#riskForm').serializeArray();

    if($('#cocResponse').find('input').length){
        fileid = $('#cocResponse').find('input').val();    
        form.push({name: "cocFile", value: fileid });        
    }

    if($('#epResponse').find('input').length){
        fileid = $('#epResponse').find('input').val();        
        form.push({name: "epFile", value: fileid });        
    }

    console.log(form);
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').html("");
    $.ajax({
            type: "POST",
            url: "forms/excur/"+id+"/6",
            data: form,
            success: function(data) {
                $( "#formContent" ).html(data);
            },
            beforeSend: function(request) {
                request.setRequestHeader("X-Requested-With", "XMLHttpRequest");
            }          
            
    });       
}
function toggleFirstAid(radio){    
    if($(radio).val() == 1){
        $('#staffFirstAid').fadeIn();
        addStaffFirstAidRow();      
    }else{        
        $('#staffFirstAid').fadeOut(function(){
            $('#firstAidTable').html(""); 
        });
               

    }
}

function toggleBronze(radio){    
    if($(radio).val() == 1){
        $('#staffBronze').fadeIn();
        addStaffBronzeRow();      
    }else{        
        $('#staffBronze').fadeOut(function(){
            $('#bronzeTable').html(""); 
        });
               
    }
}

function deleteRow(row){       
    $(row).closest('tr').fadeOut(function(){
        $(row).closest('tr').remove();        
    });       
}

function addStaffFirstAidRow(){ 
    id = $('#formId').val();
    $.get('/staff/forms/staffrow/'+id+'/FirstAid', function(data){
        $('#firstAidTable').append(data);
        $('.staffFirstAid').fadeIn();
    });    
}

function addStaffBronzeRow(){
    id = $('#formId').val();    
    $.get('/staff/forms/staffrow/'+id+'/Bronze', function(data){
        $('#bronzeTable').append(data);
        $('.staffBronze').fadeIn();
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
