<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\Link;
use App\Timetable;

class StudentHomeController extends Controller
{
    public function show()
    {
        $studentId = session('seqtaId');
        $thisDate = '2020-04-08';
        $isToday = true;
        
        //Get Student Home Links
        $links = Link::where('linktype_id', 3)
        ->orderBy('sort', 'asc')
        ->get();

        //Get Timetable for student
        $timetablestudent = new Timetable;
        $timetable = $timetablestudent->getStudentTimetable($studentId,$thisDate);
        
        //Get Notices from SEQTA
        $notices = DB::connection('seqta')
        ->select('SELECT notice.id, notice.title, notice.contents, staff.title as stitle, staff.surname, staff.code
                    FROM notice
                    JOIN staff ON notice.staff = staff.id
                    WHERE notice.show_from <= ? AND notice.show_until >= ?
                    ORDER BY notice.show_from', [$thisDate,$thisDate]);
        
        //Get SEQTA Notifications
        $seqtaNote = DB::connection('seqta')->table('notification')->where('student', $studentId)->where('read', '0')->count();

        //Get unread mail since 7 days.
        $mailNotifications = new MSGraphController;
        $mailNote = $mailNotifications->getMailNotifications();
    
        //Return links, timetable and notices to student home view
        return view('student.home', ['links' => $links, 'timetable' => $timetable, 'notices' => $notices
        , 'isToday' => $isToday, 'mailNote' => $mailNote, 'seqtaNote' => $seqtaNote]);
        
    }

    public function timetable(Request $request, $date)
    {
        $studentId = session('seqtaId');        
        $isToday = false;

        if ($date == '2020-04-08'){
            $isToday = true;
        }
        //Get Timetable for staff
        $timetablestudent = new Timetable;
        $timetable = $timetablestudent->getStudentTimetable($studentId,$date);

        return  view('student._timetable', ['timetable' => $timetable, 'isToday' => $isToday]);
                
    }
       
}
