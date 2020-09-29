<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FormPd;
use App\FormExcur;
use App\FormExcurStaff;
use App\FormExcurFile;
use App\Timetable;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class FormController extends Controller
{
    function show(){
        $pdForms = FormPd::select('form_pds.id', 'form_pds.pdname as formname', 'form_pds.created_at', 'form_pds.startDate','form_status_types.name','form_status_types.colour', FormPd::raw("'pd' as type"))                        
                        ->join('form_status_types','form_pds.status', '=', 'form_status_types.id')
                        ->where('seqtaId',session('seqtaId'))
                        ->where('status', '!=', '5');
                          

        $forms = FormExcur::select('form_excurs.id', 'form_excurs.excurname as formname', 'form_excurs.created_at', 'form_excurs.startDate', 'form_status_types.name','form_status_types.colour', FormExcur::raw("'excur' as type"))                        
                        ->join('form_status_types','form_excurs.status', '=', 'form_status_types.id')
                        ->where('seqtaId',session('seqtaId'))
                        ->where('status', '!=', '5')
                        ->union($pdForms)
                        ->orderBy('created_at')->get();   
                        
        return view('staff.forms.index',['forms' => $forms]);
    }

    function showAdmin(){

        $pdForms = FormPd::select('form_pds.firstname','form_pds.surname','form_pds.id', 'form_pds.pdname as formname', 'form_pds.created_at', 'form_pds.startDate','form_status_types.name','form_status_types.colour', FormPd::raw("'pd' as type"))                        
                        ->join('form_status_types','form_pds.status', '=', 'form_status_types.id')                        
                        ->where('status', '!=', '5')
                        ->orderBy('created_at')->get();
                          

        $excurForms = FormExcur::select('form_excurs.firstname','form_excurs.surname','form_excurs.id', 'form_excurs.excurname as formname', 'form_excurs.created_at', 'form_excurs.startDate', 'form_status_types.name','form_status_types.colour', FormExcur::raw("'excur' as type"))                        
                        ->join('form_status_types','form_excurs.status', '=', 'form_status_types.id')                        
                        ->where('status', '!=', '5')                        
                        ->orderBy('created_at')->get();   
               
        return view('admin.forms.index',['pdForms' => $pdForms, 'excurForms' => $excurForms]);

    }

    function getRelief($staffId = false){

        $start = request('reliefStartDate');
        $end = request('reliefFinishDate');

        request()->validate([
            'reliefStartTime' => ['required'],
            'reliefStartDate' => ['required',"before_or_equal:$end"],
            'reliefFinishDate' => ['required', "after_or_equal:$start"],
            'reliefFinishTime'  => ['required'],            
        ]);
        
        $start = request('reliefStartDate');
        $end = request('reliefFinishDate');
        if (!$staffId){
            $staffId = 207;
        }        

        $timetable = new Timetable;
        $reliefTimetable =  $timetable->getrelief($staffId,request('reliefStartDate'),request('reliefStartTime'),request('reliefFinishDate'),request('reliefFinishTime'));        
        return view('staff.forms.relief', ['reliefTimetable' => $reliefTimetable]);
    
    }

    function getAllRelief(){

        $start = request('startDate');
        $end = request('finishDate');

        request()->validate([
            'startTime' => ['required'],
            'startDate' => ['required',"before_or_equal:$end"],
            'finishDate' => ['required', "after_or_equal:$start"],
            'finishTime'  => ['required'],            
        ]);
        
        $start = request('startDate');
        $end = request('finishDate');
        
        
        $staff = request('staffRelief');
       //$staff = [207,217];        
            //dd($staff);
        //$staff = request('staff');
        if ($staff){
            $staff = array_filter($staff);

            $staffId = implode(',',$staff);
            
            $timetable = new Timetable;
            $reliefTimetable =  $timetable->getrelief($staffId,request('startDate'),request('startTime'),request('finishDate'),request('finishTime'));                    
            
        
            return view('staff.forms.relief', ['reliefTimetable' => $reliefTimetable]);
        } else {
            return "false";
        }
        
    }

    function getReliefRow($rownum, $reliefReq = false, $selected = false){

        $row = Carbon::now()->format('dHis');
        $staff =  DB::connection('seqta')
            ->select("SELECT DISTINCT id, firstname, salutation, email, surname
            FROM staff
            JOIN \"staffCampus\" on staff.id = \"staffCampus\".staff
            WHERE \"staffCampus\".campus = 1 AND staff.email != ''
            ORDER BY surname");
        
        return view('staff.forms.excur.reliefrow', ['staffmembers' => $staff, 'row' => $row, 'reliefReq' => $reliefReq, 'selected' => $selected]);

    }

    function getExpenseRow($id){

        $date = FormExcur::find($id)->value('startDate');
        return view('staff.forms.excur.expenserow', ['required' => $date,
                                                     'expense' => false,
                                                     'amount' => 0,
                                                     'cheque' => false]);

    }

    function getStudentRow($type){

        if($type == 1){

            $today = date('Y-m-d');

            $classes = DB::connection('seqta')->select("SELECT DISTINCT classunit.id, subject.description, subject.name, classunit.class_number
                        FROM classinstance
                        JOIN classunit ON classinstance.classunit = classunit.id
                        JOIN subject ON classunit.subject = subject.id
                        JOIN term ON classinstance.term = term.id
                        WHERE term.start <= '$today' AND term.end >= '$today' AND subject.description NOT LIKE 'Study%' AND subject.description NOT LIKE 'study%'
                        ORDER BY subject.name");

            return view('staff.forms.excur.classrow',['classes' => $classes]);

        } elseif($type == 2){

            $years = DB::connection('seqta')->select("SELECT id, name, code
            FROM schoolyear
            WHERE section IS NOT NULL
            ORDER BY code");

            return view('staff.forms.excur.schoolyearrow',['years' => $years]);
        }       

    }

    function getStudents($type){

        if($type == 1){
            $classes = request('class');
            if(is_array($classes)){
                $classes = implode(',',$classes);
            }
            $students = DB::connection('seqta')->select("SELECT DISTINCT student.firstname, student.surname, student.alert_medical, rollgroup.code, schoolyear.code as year, student.id
            FROM \"classunitStudent\"
            JOIN student on \"classunitStudent\".student = student.id
            JOIN rollgroup on student.rollgroup = rollgroup.id
            JOIN schoolyear on student.schoolyear = schoolyear.id
            WHERE classunit IN ($classes) 
            ORDER BY year, student.surname");           

        } elseif($type == 2){

            $years = request('year');
            if(is_array($years)){
                $years = implode(',',$years);
            }
            $students = DB::connection('seqta')->select("SELECT DISTINCT student.firstname, student.surname, student.alert_medical, rollgroup.code, schoolyear.code as year, student.id 
            FROM student
            JOIN rollgroup on student.rollgroup = rollgroup.id
            JOIN schoolyear on student.schoolyear = schoolyear.id
            WHERE schoolyear IN ($years) AND status = 'FULL'
            ORDER BY year, student.surname");
           
        } elseif($type == 3){

            $student = request('student');
           
            $students = DB::connection('seqta')->select("SELECT student.firstname, student.surname, student.alert_medical, rollgroup.code, schoolyear.code as year, student.id 
            FROM student
            JOIN rollgroup on student.rollgroup = rollgroup.id
            JOIN schoolyear on student.schoolyear = schoolyear.id
            WHERE student.id = '$student'");

        }

        return view('staff.forms.excur.students',['students' => $students]);

    }
    function getStudentlist(){

        $student = request('student');

        $students = DB::connection('seqta')->select("SELECT student.id, student.firstname, student.surname, student.alert_medical, rollgroup.code, schoolyear.code as year 
        FROM student
        JOIN rollgroup on student.rollgroup = rollgroup.id
        JOIN schoolyear on student.schoolyear = schoolyear.id
        WHERE student.status = 'FULL' AND (student.firstname iLIKE '$student%' or student.surname iLIKE '$student%')
        ORDER BY student.firstname
        LIMIT(10)");

        return view('staff.forms.excur.studentlist', ['students' => $students]);

    }

    function getStaffRow($id,$type){
        $staff = FormExcurStaff::where('form_excur_id', $id)->get();
        return view('staff.forms.excur.staffrow', ['staffs'=> $staff, 'type' => $type, 'name' => false]);
    }

    function uploadFile($id, request $request){

        $name = pathinfo($request->file->getClientOriginalName(), PATHINFO_FILENAME);
        $type = $request->type;
        $path = $request->file->store('forms/excur/'.$id);
        
        $fileId = FormExcurFile::create(['form_excur_id' => $id,
                                'seqtaId' => session('seqtaId'),
                                'name' => $name,
                                'path' => $path,
                                'type' => $type]);
        
        return view('staff.forms.excur.file',['name' => $name, 'fileId' => $fileId->id, 'type' => $type]);

    }

    function getRiskRow(){
        
        return view('staff.forms.excur.riskrow',['activity' => false,
        'hazard' => false,
        'risk' => false,
        'control' => false]);
        
    }

    function getCareRow(){
        return view('staff.forms.excur.carerow',['student' => false,
        'concern' => false,
        'risk' => false,
        'control' => false,
        'file' => false]);
    }

    function archive($type,$id){

        if($type == 'pd'){
            FormPd::where('id',$id)->update([                            
                'status' => 5 ]);
        }elseif($type == 'excur')
            FormExcur::where('id',$id)->update([                            
                'status' => 5    ]);                
        }
    }
