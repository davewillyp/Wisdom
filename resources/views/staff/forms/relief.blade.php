
    <table class="table table-striped table-bordered" id="reliefTable" style="border-collapse: collapse;">
        <thead>
            <tr>
                <th width:200px">Teacher</th>
                <th width:150px">Date</th>
                <th width:115px">Period</th>
                <th>Class Name</th>            
                <th>Note</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
    @foreach($reliefTimetable as $relief)
            <tr>                
                <td><input class='form-control form-control-sm' type='text' name='name[]' value='{{$relief->name}}' readonly></td>
                <td><input style='width:150px' class='form-control form-control-sm' type='date' name='date[]' value='{{ Carbon\Carbon::parse($relief->date)->format('Y-m-d') }}' readonly></td>
                <td><input style='width:115px' class='form-control form-control-sm' type='text' name='period[]' value='{{ $relief->period}}' readonly></td>            
                <td><input class='form-control form-control-sm' type='text' name='class[]' value='{{ $relief->class }}' readonly></td>            
                <td><input type="text" class="form-control form-control-sm" name='note[]' value='{{ $relief->note ?? '' }}'></td>
                <td class='align-middle' width='35px'><i class="fal fa-trash-alt pl-2 wis-deletebin" onclick="deleteRelief(this)" style="cursor:pointer"></i></td>   
            </tr>
    @endforeach
        </tbody>
    </table>
