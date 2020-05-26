<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Timetable extends Model

{
    protected $connection = 'seqta';
    
    public function getStaffTimetable($staffId,$thisDate)
    {
        $fullTimeNow = date_create(date('H:i:s'));
        $dayToday = date('l', strToTime($thisDate));

        $timePeriods = array('Before School', 'Home Room','Period 1','Period 2','Recess','Period 3','Period 4', 'Lunch','Lunch Matheu', 'Period 5', 'Period 6', 'After School');

        foreach ($timePeriods as $period){
            if (strpos($period, 'Period') !== false or strpos($period, 'Home') !== false) {
                
                $thisperiod = DB::connection('seqta')
                ->select('SELECT subject.code, subject.description, classunit.id, classunit.metaclass, cycleperiod.start, cycleperiod.end, period.name as period, room.code as room, metaclass.id as metaclass, "metaclassProgramme".programme, staffpreference.value
                            FROM classinstance
                            JOIN period ON classinstance.period = period.id                    
                            JOIN staff on classinstance.staff = staff.id OR classinstance.staff_relieving = staff.id
                            JOIN classunit on classinstance.classunit = classunit.id
                            JOIN subject on classunit.subject = subject.id
                            JOIN metaclass ON classunit.metaclass = metaclass.id
                            JOIN cycleperiod ON classinstance.cycleperiod = cycleperiod.id
                            LEFT JOIN room on classinstance.room = room.id
                            LEFT JOIN "metaclassProgramme" ON metaclass.id = "metaclassProgramme".metaclass
                            LEFT JOIN staffpreference ON staffpreference.staff = staff.id AND staffpreference.name = \'timetable.class.colour.\' || subject.code
                            WHERE staff.id = ? AND classinstance.date = ? AND period.name = ?
                            Order By period.name', [$staffId,$thisDate,$period]);
                if (!$thisperiod){
                    $thisperiod = DB::connection('seqta')
                    ->select('SELECT DISTINCT \'\' as code, \'\' as description, \'\' as id, \'\' as metaclass,
                     cycleperiod.start, cycleperiod.end, period.name as period,
                     \'\' as room, \'\' as programme, \'\' as value 
                    FROM classinstance
                    JOIN period ON classinstance.period = period.id
                    JOIN cycleperiod ON classinstance.cycleperiod = cycleperiod.id
                    JOIN term ON classinstance.term = term.id
                    WHERE term.start <= ? AND term.end >= ? AND period.name = ?', [$thisDate,$thisDate,$period]);             

                }                  
                  

            } 
            
            else
            
            {
                $thisperiod = DB::connection('wisdomold')
                ->select("SELECT 'Duty' as description, '' as id, '' as metaclass, slotStart as start
                , slotFinish as 'end', slotName as period, dutyLocation.locationName as room
                , '' as programme, 'cyan' as value, '' as code  
                FROM duty
                JOIN dutySlot on duty.dutySlot = dutySlot.slotID
                JOIN dutyLocation on duty.dutyLocation = dutyLocation.locationID
                WHERE dutyHolder = 'Nathan.Farley@cewa.edu.au' AND slotDay = ? AND slotName = ?", [$dayToday,$period]);

                if (!$thisperiod){
                    $thisperiod = DB::connection('wisdomold')
                    ->select("SELECT TOP(1) '' as description, '' as id, '' as metaclass, slotStart as start
                    , slotFinish as 'end', slotName as period, '' as room, '' as code
                    , '' as programme, '' as value  
                    FROM duty
                    JOIN dutySlot on duty.dutySlot = dutySlot.slotID
                    JOIN dutyLocation on duty.dutyLocation = dutyLocation.locationID
                    WHERE slotDay = ? AND slotName = ? ", [$dayToday,$period]);
                }
                
            }

             //Get append current period as $thisPeriod
             foreach ($thisperiod as $tt){
                $start = date_create($tt->start);
                $end = date_create($tt->end);

                if ($start <= $fullTimeNow & $end >= $fullTimeNow){
                $tt->thisPeriod = true;
                } else {
                    $tt->thisPeriod = false;
                }
            }

            $timetable[$period] = $thisperiod;  
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
        }


        return $timetable;
    }

}
