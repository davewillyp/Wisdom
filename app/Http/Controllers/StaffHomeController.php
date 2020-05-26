<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\Link;
use App\Timetable;
use App\Termweek;
use DateInterval;
use App\Event;
use Carbon\Carbon;

class StaffHomeController extends Controller
{
    public function show()
    {
        $staffId = '217';
        $thisDate = date('Y-m-d');
        $isToday = true;    

         //Get Staff Home Links
         $links = Link::where('linktype_id', 5)
         ->orderBy('sort', 'asc')
         ->get();
                 
        //Get SEQTA Notifications
        $seqtaNote = DB::connection('seqta')->table('notification')->where('staff', $staffId)->where('read', '0')->count();

        //Get unread mail since 7 days.
        $mailNotifications = new MSGraphController;
        $mailNote = $mailNotifications->getMailNotifications();

        $timetable = StaffHomeController::getTimetable(date('Y-m-d'));
        $events = StaffHomeController::getEvents();
       
        //Return links
       return view('staff.home', ['links' => $links, 'mailNote' => $mailNote,
                     'seqtaNote' => $seqtaNote, 'timetable' => $timetable,
                     'events' => $events]);        
       
    }

    public function getTimetable($date)
    {
        $staffId = '217';
        $isToday = false;

        if ($date == date('Y-m-d')){
            $isToday = true;
        }
        //Get Timetable for staff
        $timetablestaff = new Timetable;
        $timetable = $timetablestaff->getStaffTimetable($staffId,$date);

        return  view('staff._timetable', ['timetable' => $timetable, 'isToday' => $isToday]);                
    }

    public function getNotices()
    {
        $thisDate = date('Y-m-d');

        $notices = DB::connection('seqta')
         ->select('SELECT notice.id, notice.title, notice.contents, staff.title as stitle, staff.surname, staff.code
                     FROM notice
                     JOIN staff ON notice.staff = staff.id
                     WHERE notice.show_from <= ? AND notice.show_until >= ?
                     ORDER BY notice.show_from', [$thisDate,$thisDate]);
        
        return view('staff._notices', ['notices' => $notices]);
    }

    public function getEvents($weekid = false)
    {        
        if($weekid){
            $week = Termweek::find($weekid);
        } else {
            $week = Termweek::currentweek(date('Y-m-d'));            
        }

        $date = date_create($week->start);
        $dateend = date_create($week->end);
        $add = new DateInterval('P01D');

        $intDay = 0;
        do {
            $events[$intDay]['day'] = $date->format('l');
            $events[$intDay]['date'] = $date->format('j F');
            if ($date->format('Y-m-d') == date('Y-m-d')){
                $events[$intDay]['isToday'] = true;
            } else {
                $events[$intDay]['isToday'] = false; 
            }
            

            $myEvents = Event::select('events.name', 'eventtypes.colour','events.starttime','events.endtime','events.location', 'eventtypes.name as type')
                            ->join('eventtypes', 'events.eventtype_id', 'eventtypes.id')
                            ->where('startdate', $date->format('Y-m-d'))
                            ->orderByRaw('starttime NULLS FIRST')
                            ->get();
            $intEvents = 0;
            foreach ($myEvents as $event)
            {
                $events[$intDay]['events'][$intEvents]['name'] = $event->name;
                $events[$intDay]['events'][$intEvents]['type'] = $event->type;
                $events[$intDay]['events'][$intEvents]['colour'] = $event->colour;
                $events[$intDay]['events'][$intEvents]['starttime'] = $event->starttime; 
                $events[$intDay]['events'][$intEvents]['endtime'] = $event->endtime; 
                $events[$intDay]['events'][$intEvents]['location'] = $event->location;

                if(date_create($date->format('Y-m-d').$event->starttime) <= Carbon::now() and date_create($date->format('Y-m-d').$event->endtime) >=  Carbon::now()){
                    $events[$intDay]['events'][$intEvents]['isNow'] = true;
                } else {
                    $events[$intDay]['events'][$intEvents]['isNow'] = false;
                }

                               
                $intEvents++;
            }
                       
            $intDay++;
            $date->add($add);
        } while ($date <= $dateend);

        $userGraph = new MSGraphController;
        $userEvents = $userGraph->getUserEvents();

        $eachDay = [0,1,2,3,4];

        foreach($eachDay as $intDay){
            if (isset($userEvents[$intDay])){
                foreach($userEvents[$intDay] as $userEvent){
                    $events[$intDay]['events'][] = $userEvent;
                }
                usort($events[$intDay]['events'], 'self::date_compare');
            }            
        }

        //return $events;
        return view('staff._events', ['events' => $events]);

    }

    function date_compare($element1, $element2) { 
        $datetime1 = strtotime($element1['starttime']); 
        $datetime2 = strtotime($element2['starttime']); 
        return $datetime1 - $datetime2; 
    }
}
