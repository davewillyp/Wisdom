<tr>
    <td>
        <select class='form-control form-control-sm' name="class[]" onchange='getClass(this)'>
            <option value="">Choose Class...</option>
            @foreach($classes as $class)
            <option value="{{ $class->id }}">{{ $class->name.'#'.$class->class_number.' - '.$class->description }}</option>
            @endforeach            
        </select>
    </td>
    <td>
        <i class="fal fa-trash-alt wis-deletebin" onclick="deleteClassRow(this)" style="cursor:pointer"></i>
    </td>
</tr>
