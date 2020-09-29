@extends('staff.forms.excur.formlayout')
    @section('formcontent')
            <form id='formCare'>
            @CSRF 
            <input type='hidden' name='id' value='{{ $form->id }}' id='formId'>
                <div class='row justify-content-center mb-2'>
                    <div class='col shadow-sm p-2 rounded' style='background-color:whitesmoke;'>                            
                        <div class='p-2'>
                            <h6>Student Care Plans</h6>
                            <table class='table table-bordered table-striped' id='fullCareTable'>
                                <thead>
                                    <th>Student</th>
                                    <th>Nature of Concern</th>
                                    <th>Risk Level</th>
                                    <th>Elimination/Control Measures</th>
                                    <th>Care Plan</th>
                                </thead>
                                <tbody id="careTable">
                                {!! $cares !!}
                                </tbody>
                            </table>
                            <span class=float-right><i class="fal fa-plus-square" style='color:green' onclick='addCareRow()'></i></span>       
                        </div>                      
                    </div>
                </div>
                
            
                <div class='row justify-content-center'>
                    <div class='col p-2 mt-2'>                      
                        <button class='btn btn-success float-right' type='button' onclick='submitPage({{ $form->id }})'>Save & Next</button>                
                    </div>
                </div>
            </form>
        </div>
<br>
<div id="myModal" class="mymodal">
    <!-- Modal content -->
    <div class="mymodal-content" style='background: whitesmoke;'>
        <div class="myclose" >&times;</div><br>
        <div class='container' id='modalcontent'>
            <input type='hidden' id='rowIndex'>
            <form id="careUploadForm" method="post" enctype="multipart/form-data">
                @CSRF
                <label for="fileCert">Upload Care Plan</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" name="file" onchange="updateLabel(this)" id="careFile">
                    <label id= "customFileCertLabel" class="custom-file-label" for="careFile">Choose file</label>
                </div>
                <input type="hidden" name="type" value="care">
            </form>
            <button class='btn btn-outline-primary mt-2 mb-2' type='button' onclick="uploadFile()">Upload</button> 
      </div>
    </div>  
<script>
function submitPage(id){
    form = $('#formCare').serialize();
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').html("");
    $.ajax({
            type: "POST",
            url: "forms/excur/"+id+"/8",
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
                    row = parseInt(key.substr(key.indexOf('.')+1)) + 1;
                    name = key.substr(0, key.indexOf('.')); 
                    cell = $('#fullCareTable tr:eq('+row+')').find('input[name*="' + name +'"]')
                    cell2 = $('#fullCareTable tr:eq('+row+')').find('textarea[name*="' + name +'"]')
                    if (cell.length){
                        $(cell).addClass('is-invalid');                      
                    } else {
                        $(cell2).addClass('is-invalid');                      
                    }
                 });
            }
    });    
}
function addCareRow(){
    $.get('/staff/forms/carerow', function(data){
        $('#careTable').append(data);        
    });  
}
function uploadFileModal(button){
    index = $(button).parent().parent().index('tr');  
    $('#rowIndex').val(index);
    $('#myModal').fadeIn();
}

function uploadFile(){
    formData = new FormData($('#careUploadForm')[0]);
    file = $('#careFile')[0].files[0];
    formData.append('file', file, file.name);
    row = $('#rowIndex').val();

    id = $('#formId').val();
        
        $.ajax({
            type: "POST",
            url: '/staff/forms/upload/'+id,
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                $('#myModal').fadeOut();
                $('#fullCareTable tr:eq('+row+') td').eq(4).html(data);
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
var modal = document.getElementById("myModal");
    var span = document.getElementsByClassName("myclose")[0];

    window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }

  span.onclick = function() {
  modal.style.display = "none";
}
}
function deleteFile(deletebtn, id){
    form = $('#formId').val();
    token = $('input[name="_token"]').val();
    $.post("forms/excur/"+form+"/file/"+id, {_token:token, _method:'DELETE'}, function(data){
        
        $(deletebtn).parent().parent().parent().fadeOut(function(){
            $(deletebtn).parent().parent().parent().parent().html('<input type="hidden" name="file[]" value="0"><i class="fal fa-file-upload fa-2x" onclick="uploadFileModal(this)"></i>');
        })        
    });
}
</script>
@endsection