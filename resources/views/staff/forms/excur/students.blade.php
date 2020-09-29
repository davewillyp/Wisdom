@foreach($students as $student)
    <tr>
        <td>
            <input class="form-control form-control-sm" type="text" name="firstname[]" value="{{$student->firstname}}" readonly>
        </td>
        <td>
            <input class="form-control form-control-sm" type="text" name="surname[]" value="{{$student->surname}}" readonly>
        </td>
        <td>
            <input class="form-control form-control-sm" type="text" name="year[]" value="{{$student->year}}" readonly> 
        </td>
        <td>
            <input class="form-control form-control-sm" type="text" name="roll[]" value="{{$student->code}}" readonly>
        </td>
        <td class='align-middle' width='35px'>
            <input type="hidden" name="medical[]" value="{{$student->alert_medical}}">@if($student->alert_medical)<i class="fal fa-notes-medical fa-2x" style='color:red'></i>  @endif
        </td>
        <td class='align-middle' width='35px'>            
            <i class="fal fa-trash-alt wis-deletebin" onclick="deleteStudent(this)" style="cursor:pointer"></i>
        </td>
    </tr>
@endforeach