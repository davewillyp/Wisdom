<?php
$riskLevel[] = array(1,"Low");
$riskLevel[] = array(2,"Moderate");
$riskLevel[] = array(3,"High");
$riskLevel[] = array(4,"Critical");
$riskLevel[] = array(5,"Catastrophic");
?>
<tr>
    <td><input class='form-control form-control-sm' type="text" name="student[]" id="" value="{{ $student }}"></td>
    <td><input class='form-control form-control-sm' type="text" name="concern[]" id="" value="{{ $concern }}"></td>    
    <td>
        <select name="risk[]" id="" class='form-control form-control-sm'>
        @foreach($riskLevel as $riska)
            <option value="{{$riska[0]}}" @if($riska[0] == $risk) selected @endif >{{$riska[1]}}</option>
        @endforeach
        </select>
    </td>
    <td><textarea name="control[]" id="" rows="3" class='form-control form-control-sm'>{{ $control }}</textarea></td>
    <td>@if(!$file)<input type="hidden" name="file[]" value="0"><i class="fal fa-file-upload fa-2x" onclick="uploadFileModal(this)"></i>@else {!!$file!!} @endif</td>
</tr>