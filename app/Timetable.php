<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Quicklink;

class Timetable extends Model

{
    protected $connection = 'seqta';
    
    public function getStaffTimetable($staffId,$thisDate)
    {
        
        $thisDate = '2020-09-25';
        $fullTimeNow = date_create(date('H:i:s'));
        $dayToday = date('l', strToTime($thisDate));

        //$timePeriods = array('Before School', 'Home Room','Period 1','Period 2','Recess','Period 3','Period 4', 'Lunch','Lunch Matheu', 'Period 5', 'Period 6', 'After School');
        $timePeriods = Period::select('name','start','end')
                                ->orderBy('sort')
                                ->get();

        foreach ($timePeriods as $period){
            if (strpos($period->name, 'Period') !== false or strpos($period->name, 'Home') !== false) {

                $thisperiod = DB::connection('wisdomold')
                ->select("SELECT 'Exam Supervision' as description, '' as id, '' as metaclass, startTime as start
                , endTime as 'end', periods.name as period, room
                , '' as programme, 'orange' as value, examTimetable.name as code, '' as staff_relieving  
                FROM examTimetable
                JOIN periods on periods.name = examTimetable.period                
                WHERE email = ? AND examDate = ? AND period = ?", ['Graeme.Rutherford@cewa.edu.au',$thisDate,$period->name]);

                if($thisperiod){
                    $thisperiod[0]->booking = false;

                } else {
                
                    $thisperiod = DB::connection('seqta')
                    ->select('SELECT subject.code, subject.description, classunit.id, classunit.metaclass, cycleperiod.start,
                    cycleperiod.end, period.name as period, room.code as room, metaclass.id as metaclass, "metaclassProgramme".programme,
                    staffpreference.value, classinstance.staff_relieving
                                FROM classinstance
                                JOIN period ON classinstance.period = period.id                    
                                JOIN staff on classinstance.staff = staff.id OR classinstance.staff_relieving = staff.id
                                JOIN classunit on classinstance.classunit = classunit.id
                                JOIN subject on classunit.subject = subject.id
                                JOIN metaclass ON classunit.metaclass = metaclass.id
                                JOIN cycleperiod ON classinstance.cycleperiod = cycleperiod.id
                                JOIN term ON cycleperiod.term = term.id
                                LEFT JOIN room on classinstance.room = room.id
                                LEFT JOIN "metaclassProgramme" ON metaclass.id = "metaclassProgramme".metaclass
                                LEFT JOIN staffpreference ON staffpreference.staff = staff.id AND staffpreference.name = \'timetable.class.colour.\' || subject.code
                                WHERE staff.id = ? AND classinstance.date = ? AND period.name = ?
                                Order By period.name', [$staffId,$thisDate,$period->name]);

                    $booking = Booking::getUserBooking($thisDate, $period->name);                            

                    if($thisperiod){
                        if($booking->isNotEmpty()){
                            $thisperiod[0]->booking = $booking;
                        } else {
                            $thisperiod[0]->booking = false; 
                        }
                    } else {
                        if($booking->isNotEmpty()){
                            $thisperiod = DB::connection('seqta')
                            ->select("SELECT '' as code, '' as description, '' as id, '' as metaclass, cycleperiod.start,
                            cycleperiod.end, period.name as period, '' as room, '' as metaclass, '' as programme,
                            '' as value, '' as staff_relieving
                                        FROM cycleperiod
                                        JOIN period ON cycleperiod.period = period.id
                                        JOIN cycle ON cycleperiod.cycle = cycle.id
                                        JOIN term on cycle.term = term.id                                                     
                                        WHERE period.name = ? AND term.start <= ? AND term.end >= ?
                                        Order By term.id LIMIT 1", [$period->name,$thisDate,$thisDate]);
                            $thisperiod[0]->booking = $booking;                        
                        }
                    }                                
                    
                }

            } 
            
            else
            
            {
                $thisperiod = DB::connection('wisdomold')
                ->select("SELECT 'Duty' as description, '' as id, '' as metaclass, slotStart as start
                , slotFinish as 'end', slotName as period, dutyLocation.locationName as room
                , '' as programme, 'cyan' as value, '' as code, '' as staff_relieving  
                FROM duty
                JOIN dutySlot on duty.dutySlot = dutySlot.slotID
                JOIN dutyLocation on duty.dutyLocation = dutyLocation.locationID
                WHERE dutyHolder = 'Nathan.Farley@cewa.edu.au' AND slotDay = ? AND slotName = ?", [$dayToday,$period->name]);
                
                if($thisperiod){
                    $thisperiod[0]->booking = false;
                }
                
                
            }

             //Get append current period as $thisPeriod
             if(isset($thisperiod)){
                foreach ($thisperiod as $tt){
                    $start = date_create($tt->start);
                    $end = date_create($tt->end);

                    if ($start <= $fullTimeNow & $end >= $fullTimeNow){
                        $tt->thisPeriod = true;
                    } else {
                        $tt->thisPeriod = false;
                    }

                    $tt->quicklinks = false;
                    if($tt->metaclass){
                        $links = Quicklink::where('class', $tt->metaclass)->get();
                        if($links->isNotEmpty()){
                            $tt->quicklinks = $links;
                        }
                        $year = DB::connection('seqta')->table('classinstance')
                                                ->join('classunit','classinstance.classunit', '=', 'classunit.id')
                                                ->join('classinstanceStudent','classinstanceStudent.classinstance','=','classinstance.id')
                                                ->join('student','classinstanceStudent.student','=','student.id')
                                                ->where('classunit.metaclass', $tt->metaclass)
                                                ->where('classinstance.date', $thisDate)
                                                ->value('student.schoolyear');
                        $tt->year = $year;

                    }

                
                }
            }
            if ($thisperiod){
                $timetable[$period->name] = $thisperiod; 
            }
           
        }                     
        return $timetable;
    }

    public function getStudentTimetable($studentId,$thisDate)
    {

        $fullTimeNow = date_create(date('H:i:s'));

        $timetable =  DB::connection('seqta')
        ->select('SELECT subject.code, subject.name, subject.description, room.code as room, cycleperiod.start, cycleperiod.end, period.name as period, metaclass.id as metaclass, "metaclassProgramme".programme, staff.title, staff.surname, studentpreference.value                  
                    FROM classinstance
                    JOIN period ON classinstance.period = period.id
                    JOIN classunit ON classinstance.classunit = classunit.id
                    JOIN subject ON classunit.subject = subject.id
                    JOIN cycleperiod ON classinstance.cycleperiod = cycleperiod.id
                    JOIN "classinstanceStudent" ON "classinstanceStudent".classinstance = classinstance.id
                    JOIN student ON "classinstanceStudent".student = student.id
                    JOIN room ON classinstance.room = room.id
                    JOIN metaclass ON classunit.metaclass = metaclass.id
                    JOIN staff ON classinstance.staff = staff.id
                    LEFT JOIN studentpreference ON studentpreference.student = student.id AND studentpreference.name = \'timetable.subject.colour.\' || subject.code
                    LEFT JOIN "metaclassProgramme" ON metaclass.id = "metaclassProgramme".metaclass
                    WHERE student.id = ? AND classinstance.date = ?
                    ORDER BY classinstance.period', [$studentId,$thisDate]);

        //Get append current period as $thisPeriod
        foreach ($timetable as $tt){
            $start = date_create($tt->start);
            $end = date_create($tt->end);

            if ($start <= $fullTimeNow & $end >= $fullTimeNow){
               $tt->thisPeriod = true;               
            } else {
                $tt->thisPeriod = false;
            }

            $tt->quicklinks = false;
            if($tt->metaclass){
                $links = Quicklink::where('class', $tt->metaclass)->get();
                if($links->isNotEmpty()){
                    $tt->quicklinks = $links;
                }               
            }
        }


        return $timetable;
    }

    public function getRelief($staffId,$startDate,$startTime,$finishDate,$finishTime){

        $start = $startDate . ' ' .$startTime;
        $finish = $finishDate . ' ' . $finishTime;       

        $reliefTimetable =  DB::connection('seqta')
        ->select("SELECT staff.id as seqtaId, firstname || ' ' || surname as name, staff.email, classinstance.date, period.name as period, subject.description as class FROM classinstance
                    JOIN period ON classinstance.period = period.id
                    JOIN classunit ON classinstance.classunit = classunit.id
                    JOIN subject ON classunit.subject = subject.id
                    JOIN staff ON classinstance.staff = staff.id
                    WHERE date + \"end\" >
                    ? AND date + start < ? AND classinstance.staff IN ($staffId)
                    Order By staff.surname, date, start", [$start,$finish]);
                
        return $reliefTimetable;

        
    }
}
