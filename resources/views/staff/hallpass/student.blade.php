@extends('staff.hallpass.layout')
@section('students')
<h3>
@foreach($class as $cla)
    <span class="mr-2">{{ $cla->description }}</span>
@endforeach
</h3>
<div id="studentList">
@foreach($students as $student)
    <div class="row justify-content-center">
        <div class="col-8 mb-2">
            <div class='wisbutton p-2 rounded shadow-sm' onclick='selectStudent({{ $student->id }},this)'>{{ $student->firstname.' '.$student->surname }}</div>
        </div>
    </div>
@endforeach
</div>
<h4 id="studentText" style='display:none'></h4>
<div id="studentAction" style='display:none;'>
@foreach($studentAction as $action)
    <div class="row justify-content-center">
        <div class="col-8 mb-2">
            <div class='wisbutton p-2 rounded shadow-sm' onclick="selectAction({{ $action->id }},this)">{{ $action->name }}</div>
        </div>
    </div>
@endforeach
</div>
<h6 id="actionText" style='display:none'></h6>
<div id="studentCounter" style='display:none;'>
    <div style='font-size:35px;font-weight:bold;' class="mb-3">
        <span id="minutes">00</span>:<span class="div" id="seconds">00</span>
    </div>   
    <div class="row justify-content-center">
        <div class="col-6 mb-2">
            <div class='wisbutton p-2 rounded shadow-sm' onclick="endCount()" id="finishButton">Finish</div>
            <div id="finishMessage" style='display:none'><h5>Hall Pass Finished! <i class="fas fa-check-circle" style="color:green"></i></h5><small>Window will close automatically</small></div>
        </div>
    </div>
</div>
@endsection