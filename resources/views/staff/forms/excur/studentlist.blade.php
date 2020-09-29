@foreach($students as $student)
    <tr>
        <td>
           {{$student->firstname}}
        </td>
        <td>
            {{$student->surname}}
        </td>
        <td>
            {{$student->year}}
        </td>
        <td>
           {{$student->code}}
        </td>
        
        <td class='align-middle' width='35px'>            
            <i class="fas fa-plus fa-2x" onclick="getStudent({{$student->id}})" style="cursor:pointer"></i>
        </td>
    </tr>
@endforeach