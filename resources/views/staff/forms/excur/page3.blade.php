@extends('staff.forms.excur.formlayout')
    @section('formcontent')
            <form id='expenseForm'>
                @CSRF
                <input type="hidden" id="formId" value="{{ $form->id }}">         
                <div class='row justify-content-center'>
                    <div class='col shadow-sm rounded p-2' style='background-color:whitesmoke;'>                            
                        <div class='p-2'>
                            <div class="form-group mr-3">
                                <label for="exampleFormControlInput1" class="mr-3"><h6>
                                    Does the Excursion have expenses?
                                </h6></label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" name="expenses" type="radio" id="radioExpenses1" value="1" onchange='toggleExpenses(this)' @if($expense) @if($expense->expenses) checked @endif @endif> 
                                    <label class="form-check-label" for="radioAttending1">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" name="expenses" type="radio" id="radioExpenses2" value="0" onchange='toggleExpenses(this)' @if(!$expense) checked @else @if(!$expense->expenses) checked @endif @endif>
                                    <label class="form-check-label" for="radioAttending2">No</label>
                                </div>
                            </div>                   
                        </div>                      
                    </div>
                </div>
                <br>
                <div class='row justify-content-center' id='expenseOptions' @if($expense) @if(!$expense->expenses) style='display:none;' @endif @else style='display:none;' @endif>
                    <div class='col shadow-sm rounded p-2' style='background-color:whitesmoke;'>                            
                        <div class='p-2'>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="inputEmail4"><h6>
                                        Excursion Fee Invoiced?
                                    </h6></label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="invoiced" id="radioInvoiced1" value="1"  @if($expense) @if($expense->invoiced) checked @endif @endif>
                                        <label class="form-check-label" for="radioInvoiced1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="invoiced" id="radioInvoiced2" value="0" @if(!$expense) checked @else @if(!$expense->invoiced) checked @endif @endif>
                                        <label class="form-check-label" for="radioInvoiced2">No</label>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="inputPassword4"><h6>
                                        Payment Required before Start?
                                    </h6></label><br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="before" id="radioPaymentStart1" value="1"  @if($expense) @if($expense->before) checked @endif @endif>
                                        <label class="form-check-label" for="radioPaymentStart1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="before" id="radioPaymentStart2" value="0" @if(!$expense) checked @else @if(!$expense->before) checked @endif @endif>
                                        <label class="form-check-label" for="radioPaymentStart2">No</label>
                                    </div>
                                </div>
                            </div>                                      
                        </div>                      
                    </div>
                </div>
                <br>     
                <div class='row justify-content-center' id='expenses'  @if($expense) @if(!$expense->expenses) style='display:none;' @endif @else style='display:none;' @endif>
                    <div class='col shadow-sm rounded p-2' style='background-color:whitesmoke;'>                            
                        <div class='p-2' id='reliefResultData'>
                            <div class='mb-3'><h6>
                                Excursion Expenses
                            </h6></div>
                            <table class='table table-bordered table-striped' id='expenseTableFull'>
                                <thead>
                                    <th>Expense Name</th>
                                    <th>Cheque Text</th>
                                    <th>Amount</th>
                                    <th>Payment Due</th>
                                    <th></th>
                                </thead>
                                <tbody id='expenseTable'>
                                {!! $expenses !!}
                                </tbody>                           
                            </table>
                            <span class=float-right><span class='pr-2'>Add Expense</span><i class="fal fa-plus-square wis-additem" style='color:green' onclick='addExpensesRow()'></i></span>       
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
<script>
function submitPage(id){
    
    form = $('#expenseForm').serialize();
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').html("");
    $.ajax({
            type: "POST",
            url: "forms/excur/"+id+"/3",
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
                    row = parseInt(key.substr(key.indexOf('.')+1))+1;
                    name = key.substr(0, key.indexOf('.'));                    
                    cell = $('#expenseTableFull tr:eq('+row+')').find('input[name*="' + name +'"]')                   
                    if (cell.length){                 
                    
                        cell.addClass('is-invalid');
                   
                    } 
                });
            }
    });       
}
function toggleExpenses(expenses){    
    if($(expenses).val() == 1){
        $('#expenseOptions').fadeIn();
        $('#expenses').fadeIn();
        addExpensesRow();
    }else{
        $('#expenseOptions').fadeOut();
        $('#expenses').fadeOut();
        $('#expenseTable').html("");
    }
}

function deleteExpensesRow(row){       
    $(row).closest('tr').fadeOut(function(){
        $(row).closest('tr').remove();
        getRelief();
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
