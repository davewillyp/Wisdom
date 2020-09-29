<tr class='staff{{ $type }}'>       
    <td>
       <select name="staff{{ $type }}[]" id="" class='form-control form-control-sm'>
           @foreach($staffs as $staff)
           <?php $thisname = $staff->firstname.' '.$staff->surname;?>
           <option value="{{ $thisname }}" @if(trim($name) == trim($thisname)) selected @endif>{{ $thisname}}</option>
           @endforeach
       </select>
    </td>
    <td class='align-middle' width='35px'>            
        <i class="fal fa-trash-alt wis-deletebin" onclick="deleteRow(this)" style="cursor:pointer"></i>
    </td>
</tr>
