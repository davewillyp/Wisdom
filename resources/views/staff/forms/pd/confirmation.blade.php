<div class='container'>
    <div class='row justify-content-center'>
        <div class='col-10 p-2'>
           {!! $status['view'] !!}
        </div>
    </div>
</div>
<br>
<div class='container'>
    <form id='formdata'>
    @CSRF     
        <div class='row justify-content-center'>
            <div class='col-10 shadow-sm p-2' style='background-color:whitesmoke;'>                
                @include('staff.forms.pd._formdetails')            
            </div>
        </div>        
    </form>   
    <div class='text-center mt-3'>
    @if($status['value'])
        <button type='button' class='btn btn-success' onclick='submitPage({{ $form->id }})'>Submit for Approval</button>
    @endif
    </div>
</div>
@if($status['value'])
<script>
function submitPage(id){ 
        $('#loading').show();
        form = $('#formdata').serialize();
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').html("");
        $.ajax({
                type: "POST",
                url: "forms/pd/"+id+"/5",
                data: form,
                success: function(data) {
                    $( "#formContent" ).html(data);
                    $('#loading').hide();
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
</script>    
@endif