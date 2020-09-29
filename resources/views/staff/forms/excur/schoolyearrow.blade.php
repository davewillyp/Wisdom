<tr>
    <td>
        <select class='form-control form-control-sm' name="year[]" onchange='getYear(this)'>
            <option value="">Choose Year...</option>
            @foreach($years as $year)
                <option value="{{ $year->id }}">{{ $year->name }}</option>
            @endforeach            
        </select>
    </td>
    <td>
        <i class="fal fa-trash-alt wis-deletebin" onclick="deleteYearRow(this)" style="cursor:pointer"></i>
    </td>
</tr>