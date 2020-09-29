<tr class='staffAttending' @if(!$selected) style='display:none' @endif>
    <td>
        <select class="form-control form-control-sm" @if(!$reliefReq) name="staff[]" @else name="staffRelief[]" @endif onchange="getRelief()">
        <option value="">Choose Staff Member</option>
            @foreach($staffmembers as $staff)
            <option value="{{$staff->id}}" @if($selected == $staff->id) selected @endif>{{$staff->firstname.' '.$staff->surname}}</option>
            @endforeach
        </select>
    </td>
    <td>
        <label class="mr-3">Relief Required?</label>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name='req{{$row}}' value="1" onchange='addRelief(this)' @if($reliefReq) checked @endif> 
            <label class="form-check-label">Yes</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name='req{{$row}}' value="0" onchange='addRelief(this)' @if(!$reliefReq) checked @endif>
            <label class="form-check-label">No</label>
        </div>
    </td>
    <td>
        <i class="fal fa-trash-alt pl-2 wis-deletebin" onclick="deleteReliefRow(this)"></i>
    </td>
</tr>