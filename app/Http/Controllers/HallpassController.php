<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use DB;
use App\Hallpass;
use App\HallpassAction;

class HallpassController extends Controller
{
    public function show(){

        $class = $this->getStudentList();
        $studentActions = HallpassAction::orderBy('sort')->get();

        if($class['students']->isNotEmpty()){
            return view('staff.hallpass.student',['students' => $class['students'], 'studentAction' => $studentActions, 'class' => $class['detail']]);
        } else {
            return view('staff.hallpass.noclass');
        }

    }

    public function getStudentList(){

        $timenow = new DateTime('2020-09-25 12:00');
        $staff = "207";
        $class['students'] = DB::connection('seqta')
                    ->table('classinstance')
                    ->join('classinstanceStudent', 'classinstance.id','=','classinstanceStudent.classinstance')
                    ->join('student','classinstanceStudent.student','=','student.id')
                    ->join('rollgroup','student.rollgroup','=','rollgroup.id')
                    ->where('date', $timenow->format('Y-m-d'))
                    ->where('start', '<=', $timenow->format('H:i'))
                    ->where('end', '>=', $timenow->format('H:i'))
                    ->whereRaw("(classinstance.staff = $staff or staff_relieving = $staff)")
                    ->orderBy('student.firstname')
                    ->select('student.firstname','student.surname','rollgroup.code','student.id')
                    ->get();

        $class['detail'] = DB::connection('seqta')
                    ->table('classinstance')
                    ->join('classunit','classinstance.classunit','=','classunit.id')
                    ->join('subject','classunit.subject', 'subject.id')
                    ->where('classinstance.date', $timenow->format('Y-m-d'))
                    ->where('classinstance.start', '<=', $timenow->format('H:i'))
                    ->where('classinstance.end', '>=', $timenow->format('H:i'))
                    ->whereRaw("(classinstance.staff = $staff or staff_relieving = $staff)")
                    ->select('subject.description','classunit.metaclass')
                    ->get();

        return $class;       
    }

    public function startPass(){
        $timeNow = new DateTime;

        request()->validate(['id' => ['required'],
                'action' => ['required']]);

       $hallpass = Hallpass::create([
            'staffId' => session('seqtaId'),                       
            'studentId' => request('id'),                                           
            'class' => '1',
            'hallpass_action_id' => request('action'),
            'date' => $timeNow->format('Y-m-d'),
            'startTime' => $timeNow->format('H:i:s')
        ]);

        return $hallpass->id;

    }

    public function finishPass(){
        $timeNow = new DateTime;

        request()->validate(['id' => ['required']]);

        $pass = Hallpass::find(request('id'));
        if($pass){
            $pass->endTime = $timeNow->format('H:i:s');
            $pass->save();
            return true;
        } else {
            return false;
        }
    }
}
