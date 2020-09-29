<tr class='expenseItem'>
    <td>
        <input class='form-control form-control-sm' type="text" name="expense[]" value='{{ $expense }}'>        
    </td>
    <td>
        <input class='form-control form-control-sm' type="text" name="cheque[]" value='{{ $cheque }}'> 
    </td>
    <td>
        <div class="input-group input-group-sm">
		    <div class="input-group-prepend">
			  <span class="input-group-text">$</span>
			</div>
			<input type="number" name="amount[]" class="form-control form-control-sm" value="{{ $amount }}">
		</div>
    </td>
    <td>
        <input class='form-control form-control-sm' type="date" name="required[]" value='{{ $required->format('Y-m-d') }}'> 
    </td>
    <td>
        <i class="fal fa-trash-alt pl-2 wis-deletebin" style='cursor:pointer' onclick="deleteExpensesRow(this)"></i>
    </td>
</tr>