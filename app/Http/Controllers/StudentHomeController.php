<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\Link;
use App\Timetable;
use DateInterval;
use App\Quicklink;
use DateTime;
use App\Housepoint;

class StudentHomeController extends Controller
{
    public function show()
    {
        $studentId = session('seqtaId');        
        $isToday = true;
        
        //Get Student Home Links
        $links = Link::where('linktype_id', 3)
        ->orderBy('sort', 'asc')
        ->get();

        //Get Timetable for student        
        $timetable = $this->getTimetable(date('Y-m-d'));        
        /*
        $notices = DB::connection('seqta')
        ->select('SELECT notice.id, notice.title, notice.contents, staff.title as stitle, staff.surname, staff.code
                    FROM notice
                    JOIN staff ON notice.staff = staff.id
                    WHERE notice.show_from <= ? AND notice.show_until >= ?
                    ORDER BY notice.show_from', [$thisDate,$thisDate]);
        */
        //Get SEQTA Notifications
        $seqtaNote = DB::connection('seqta')->table('notification')->where('student', $studentId)->where('read', '0')->count();

        //Get unread mail since 7 days.
        
        $mailNotifications = new MSGraphController;
        $mailNote = $mailNotifications->getMailNotifications();       

        //Get papercut balance
        $papercut = new PapercutController;
        $balance = $papercut->getUserBalance();       

        //Get homework
        $homework = $this->getHomework();
        
        //Get House Points
        $housepoints = $this->getHousepoints();

        //Get Assessments 
        $assessments = $this->getAssessments();

        //Return links, timetable and notices to student home view
        return view('student.home', ['links' => $links, 
                                    'timetable' => $timetable,
                                    'isToday' => $isToday,
                                    'mailNote' => $mailNote,
                                    'seqtaNote' => $seqtaNote,
                                    'balance' => $balance,
                                    'homework' => $homework,
                                    'housepoint' => $housepoints,
                                    'assessments' => $assessments]);
        
    }

    public function getTimetable($date)
    {        
        $studentId = session('seqtaId');        
        $isToday = false;

        if ($date == date('Y-m-d')){
            $isToday = true;
        }

        //Get Timetable for staff
        $timetablestudent = new Timetable;
        $timetable = $timetablestudent->getStudentTimetable($studentId,$date);

        //Get Next and Previous Date
        $thisDate = date_create($date);
        $nextDate = date_create($date);
        $prevDate = date_create($date);

        $day = new DateInterval('P01D');
        $weekend = new DateInterval('P03D');

        if ($nextDate->format('l') == 'Friday'){
            $nextDate->add($weekend);
        } else {
            $nextDate->add($day);
        }
        
        if ($isToday){
            $prevDate = false;
        } else {
            if ($prevDate->format('l') == 'Monday'){
                $prevDate->sub($weekend);
            } else {
                $prevDate->sub($day);
            }
        }    

        return view('student._timetable', ['timetable' => $timetable, 
                'isToday' => $isToday,
                'prevDate' => $prevDate,
                'nextDate' => $nextDate,
                'thisDate' => $thisDate]);
                
    }

    public function getCurrentClass(){
        $student = session('seqtaId');
        
        $class = DB::connection('seqta')                            
                    ->table('classinstance')                    
                    ->select('classunit.metaclass','classinstance.term')   
                    ->join('classinstanceStudent','classinstanceStudent.classinstance', '=', 'classinstance.id')
                    ->join('classunit','classunit.id','=','classinstance.classunit')
                    ->where('date',date('Y-m-d'))
                    ->where('start','<=', '09:29')                       
                    ->where('end','>=', '09:29') 
                    ->where('student', $student)
                    ->first();
        
        //Calculate Week Number
        $startdate = date('Y').'-01-01';
        $week =  DB::connection('seqta')
                    ->table('calendarTerm')
                    ->selectRaw('COUNT(DISTINCT CONCAT(week_of_term,term_number))')
                    ->where('date', '>=',  $startdate)
                    ->where('date', '<=', date('Y-m-d'))
                    ->where('term', $class->term)
                    ->first();
        $weekInt = $week->count - 1; 

        //Calculate Period Number (for metaclass)
        $monday = date('Y-m-d',strtotime("monday this week"));       
        $periodCount = DB::connection('seqta')
                    ->table('classinstance')                   
                    ->join('classunit', 'classunit.id', '=', 'classinstance.classunit')
                    ->selectRaw('COUNT(classinstance.id)')
                    ->where('classunit.metaclass', $class->metaclass)
                    ->where('classinstance.date', '<=', date('Y-m-d'))
                    ->where('classinstance.date', '>=', $monday)
                    ->first();
                    
        $periodInt = $periodCount->count - 1;

        //Get Class Details        
        $details = DB::connection('seqta')
                    ->table('metaclassProgramme')
                    ->join('programme', 'metaclassProgramme.programme', '=', 'programme.id')
                    ->join('programmedetail', 'programme.id', '=', 'programmedetail.programme')
                    ->join('programmesequence_item', function($join)
                    {
                        $join->on('metaclassProgramme.programmesequence', '=', 'programmesequence_item.programmesequence')
                                ->on('programmedetail.id', '=', 'programmesequence_item.programmedetail');
                    })
                    ->where('metaclassProgramme.metaclass', 7422)
                    ->where('programmesequence_item.week', $weekInt)
                    ->where('programmesequence_item.number', 0)
                    ->select('programmesequence_item.homework','programmedetail.topic','programmedetail.outline')
                    ->first();
                           
                  
                    
        $links = Quicklink::where('class', $class->metaclass)->get();

        $assessments = DB::connection('seqta')
                        ->table('metaclassProgramme')
                        ->select('assessment.title','due')
                        ->join('programme','metaclassProgramme.programme','=','programme.id')
                        ->join('assessment','assessment.programme','=','programme.id')
                        ->join('assessmentMetaclass','assessment.id','=','assessmentMetaclass.assessment')
                        ->where('metaclassProgramme.metaclass',$class->metaclass)
                        ->where('due','>',date('Y-m-d'))
                        ->get();


        //$homework = DB::connection('seqta')
        //return view('student.classdetails',['links' => $links, 'assessments' => $assessments]);
        return $periodInt . ' ' . $class->metaclass. '<br><br>'. $details->outline;

    }
     
    public function getHomework(){
        
        $timeNow = new DateTime();
        $homework = false;

        //Get distinct metaclasses this week
        $monday = date('Y-m-d',strtotime("monday this week"));

        $classes = DB::connection('seqta')                            
                    ->table('classinstance')                    
                    ->select('classunit.metaclass','classinstance.term', 'subject.description','subject.priority')   
                    ->join('classinstanceStudent','classinstanceStudent.classinstance', '=', 'classinstance.id')
                    ->join('classunit','classunit.id','=','classinstance.classunit')
                    ->join('subject','classunit.subject', '=','subject.id')
                    ->where('date','>=', $monday)
                    ->where('date','<=', $timeNow->format('Y-m-d'))                                                       
                    ->where('student', session('seqtaId'))
                    ->orderBy('subject.priority')
                    ->distinct()
                    ->get();        

        $int = 0;
        foreach($classes as $class){           
            
           $firstweek = DB::connection('seqta')                            
            ->table('classinstance')                                            
            ->join('classunit','classunit.id','=','classinstance.classunit')
            ->where('classunit.metaclass', $class->metaclass)
            ->orderBy('date')
            ->select('date')
            ->first();
                                   
            //Get Classes so far this week            
            $week =  DB::connection('seqta')
                        ->table('calendarTerm')
                        ->selectRaw('COUNT(DISTINCT CONCAT(week_of_term,term_number))')
                        ->where('date', '>=',  $firstweek->date)
                        ->where('date', '<=', $timeNow->format('Y-m-d'))
                        ->where('term', $class->term)
                        ->first();
            $weekInt = $week->count - 1; 

             //Calculate Period Number (for metaclass)
            $monday = date('Y-m-d',strtotime("monday this week"));       
            $periodCount = DB::connection('seqta')
                        ->table('classinstance')                   
                        ->join('classunit', 'classunit.id', '=', 'classinstance.classunit')
                        ->selectRaw('COUNT(classinstance.id)')
                        ->where('classunit.metaclass', $class->metaclass)
                        ->where('classinstance.date', '>=', $monday)
                        ->whereRaw("classinstance.date + classinstance.end <= '". $timeNow->format('Y-m-d H:i'). "'")                       
                        ->first();
                        
            $periodInt = $periodCount->count - 1;

            //Get Programme Items
            $details = DB::connection('seqta')
            ->table('metaclassProgramme')
            ->join('programme', 'metaclassProgramme.programme', '=', 'programme.id')
            ->join('programmedetail', 'programme.id', '=', 'programmedetail.programme')
            ->join('programmesequence_item', function($join)
            {
                $join->on('metaclassProgramme.programmesequence', '=', 'programmesequence_item.programmesequence')
                        ->on('programmedetail.id', '=', 'programmesequence_item.programmedetail');
            })
            ->where('metaclassProgramme.metaclass', $class->metaclass)
            ->where('programmesequence_item.week', $weekInt)
            ->where('programmesequence_item.number', '<=', $periodInt)
            ->where('programmesequence_item.homework', '!=', '')
            ->whereNotNull('programmesequence_item.homework')
            ->select('programmesequence_item.homework','programmedetail.topic','programmesequence_item.programmedetail', 'programmesequence_item.programmesequence')
            ->get();

            if($details->isNotEmpty()){
                $homework[$int]['title'] = $class->description;
                $homework[$int]['homework'] = $details;
                $int++;
            }
            
        }        
        //return $classes;
        return $homework;
    }

    public function getHousepoints(){

        $section =  DB::connection('seqta')
                ->table('student')
                ->join('schoolyear','student.schoolyear','=','schoolyear.id')
                ->join('house', 'student.house','=','house.id')
                ->where('student.id', session('seqtaId'))
                ->select('schoolyear.section','house.name')
                ->first();        
        
        $pointsA = DB::connection('seqta')
                    ->table('pastoralcare')
                    ->join('student', 'pastoralcare.student','=','student.id')
                    ->join('house','student.house','=','house.id')
                    ->join('schoolyear','student.schoolyear','=','schoolyear.id')
                    ->selectRaw('house.name, COUNT(pastoralcare.points) as count')
                    ->where('schoolyear.section', $section->section)
                    ->groupBy('house.name')
                    ->orderBy('count','desc')
                    ->get();                
        
        foreach($pointsA as $point){
            $pointsB = Housepoint::where('house', $point->name)
                                    ->where('section', $section->section)
                                    ->selectRaw('sum(points) as count')
                                    ->value('count');

            $house[$point->name] = $point->count + $pointsB;
        }
        
        arsort($house);
        $position = array_search($section->name,array_keys($house));

        

        if($position == 0){
            $pointdetails['pos'] = "1st";
        } elseif($position == 1){
            $pointdetails['pos'] = "2nd";
        } elseif($position == 2){
            $pointdetails['pos'] = "3rd";
        } elseif($position == 3){
            $pointdetails['pos'] = "4th";
        }
        
        $pointdetails['points'] = $house[$section->name] ;
        $pointdetails['house'] = $section->name;

        return $pointdetails;

        
    }

    public function getAssessments(){

        $timeNow = new DateTime();

        //Get Classes
        $classes = DB::connection('seqta')                            
                    ->table('classinstance')                    
                    ->select('classunit.metaclass','classinstance.term', 'subject.description','subject.priority')   
                    ->join('classinstanceStudent','classinstanceStudent.classinstance', '=', 'classinstance.id')
                    ->join('classunit','classunit.id','=','classinstance.classunit')
                    ->join('subject','classunit.subject', '=','subject.id')
                    ->join('term', 'classunit.term', '=','term.id')
                    ->where('term.start','<=', $timeNow->format('Y-m-d'))
                    ->where('term.end','>=', $timeNow->format('Y-m-d'))                                                       
                    ->where('student', session('seqtaId'))
                    ->orderBy('subject.priority')
                    ->distinct()
                    ->get();
        $int = 0;
        foreach($classes as $class){

            //get Assessments for classes            
            $assess = DB::connection('seqta')
                        ->table('assessmentMetaclass')
                        ->select('assessment.title','due','assessment.id')                  
                        ->join('assessment','assessmentMetaclass.assessment','=','assessment.id')                 
                        ->where('assessmentMetaclass.metaclass',$class->metaclass)
                        ->where('assessmentMetaclass.availability', 'details')
                        ->where('due','>=',date('Y-m-d'))
                        ->get();
            if($assess->isNotEmpty()){
                $assessments[$int]['title'] = $class->description;
                $assessments[$int]['assess'] = $assess;
                $int++;
            }
            
            
        }
        return $assessments;

    }
}
