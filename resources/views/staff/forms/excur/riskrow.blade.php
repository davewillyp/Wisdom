<?php
$riskLevel[] = array(1,"Low");
$riskLevel[] = array(2,"Moderate");
$riskLevel[] = array(3,"High");
$riskLevel[] = array(4,"Critical");
$riskLevel[] = array(5,"Catastrophic");
?>
<tr>
    <td><input class='form-control form-control-sm' type="text" name="activity[]" id="" value="{{ $activity }}"></td>
    <td><input class='form-control form-control-sm' type="text" name="hazard[]" id="" value="{{ $hazard }}"></td>    
    <td>
        <select class='form-control form-control-sm' name="risk[]" id="">
        @foreach($riskLevel as $riska)
            <option value="{{$riska[0]}}" @if($riska[0] == $risk) selected @endif >{{$riska[1]}}</option>
        @endforeach
        </select>
    </td>
    <td><textarea class='form-control form-control-sm' name="control[]" id="" rows="3">{{ $control }}</textarea></td>
    <td><i class="fal fa-trash-alt wis-deletebin" onclick="deleteRow(this)" style="cursor:pointer"></i></td>
</tr>