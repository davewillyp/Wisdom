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
use App\Quicklink;

class StaffHomeController extends Controller
{
    public function show()
    {
        $staffId = '207';
        $thisDate = date('Y-m-d');
        $isToday = true;    

        //Get Staff Home Links
        $links = Link::where('linktype_id', 5)
        ->orderBy('sort', 'asc')
        ->get();

        $subLinks = Link::where('linktype_id', 6)
        ->orderBy('sort', 'asc')
        ->get();

        $greeting = "";
        date_default_timezone_set('Australia/Perth');       
        $Hour = date('G');
        if ( $Hour >= 1 && $Hour <= 11 ) {
            $greeting = "Good Morning";
        } else if ( $Hour >= 12 && $Hour <= 18 ) {
            $greeting =  "Good Afternoon";
        } else if ( $Hour >= 17 || $Hour <= 0 ) {
            $greeting =  "Good Evening";
        }

                 
        //Get SEQTA Notifications
        $seqtaNote = DB::connection('seqta')->table('notification')->where('staff', $staffId)->where('read', '0')->count();

        //Get unread mail since 7 days.
        $mailNotifications = new MSGraphController;
        $mailNote = $mailNotifications->getMailNotifications();

        $timetable = $this->getTimetable(date('Y-m-d'));
        //$events = StaffHomeController::getEvents();
        $events = $this->getEvent(date('Y-m-d'));
        $notices = StaffHomeController::getNotices();
       
        //return $events;
        //return links
       return view('staff.home', ['links' => $links, 'mailNote' => $mailNote,
                     'seqtaNote' => $seqtaNote, 'timetable' => $timetable,
                     'events' => $events, 'notices' => $notices, 'greeting' => $greeting, 'subLinks' => $subLinks]);
       
    }

    public function getTimetable($date)
    {
        $staffId = '207';
        $isToday = false;

        if ($date == date('Y-m-d')){
            $isToday = true;
        }

        $thisDate = date_create($date);
        
        //Get Timetable for staff
        $timetablestaff = new Timetable;
        $timetable = $timetablestaff->getStaffTimetable($staffId,$date);

        //Get Next and Previous Date
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

        //return $timetable;
        return  view('staff._timetable', ['timetable' => $timetable, 'isToday' => $isToday, 'prevDate' => $prevDate,
                        'nextDate' => $nextDate, 'thisDate' => $thisDate]);                
    }

    public function getNotices()
    {
        $thisDate = date('Y-m-d');

        $notices = DB::connection('seqta')
         ->select('SELECT notice.id, notice.title, notice.contents, staff.title as stitle, staff.surname, staff.code, notice_label.colour
                     FROM notice
                     JOIN staff ON notice.staff = staff.id
                     JOIN notice_label on notice.notice_label = notice_label.id
                     WHERE notice.show_from <= ? AND notice.show_until >= ?
                     ORDER BY notice_label.id desc, notice.show_from', [$thisDate,$thisDate]);
        
        return view('staff._notices', ['notices' => $notices]);
    }

    public function getEvents($weekid = false)
    {        
        //$weekid = 32;
        if($weekid){
            $week = Termweek::find($weekid);
        } else {
            $week = Termweek::currentweek(date('Y-m-d'));  
            $weekid = $week->id;          
        }

        $weekName = $week->name;

        $prevWeek = $weekid - 1;
        $nextWeek = $weekid + 1;

        if (!Termweek::find($prevWeek)){
            $prevWeek = false;
        }

        if (!Termweek::find($nextWeek)){
            $nextWeek = false;
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
        /*
        $userGraph = new MSGraphController;
        $userEvents = $userGraph->getUserEvents($week->start);

        $eachDay = [0,1,2,3,4];

        foreach($eachDay as $intDay){
            if (isset($userEvents[$intDay])){
                foreach($userEvents[$intDay] as $userEvent){
                    $events[$intDay]['events'][] = $userEvent;
                }
                usort($events[$intDay]['events'], 'self::date_compare');
            }            
        }
        */
        //return $events;
        return view('staff._events', ['events' => $events, 'weekName' => $weekName, 'prevWeek' => $prevWeek, 'nextWeek' => $nextWeek]);

    }

    function getEvent($date){
        $myEvents = Event::select('events.name', 'eventtypes.colour','events.starttime','events.endtime','events.location', 'eventtypes.name as type')
        ->join('eventtypes', 'events.eventtype_id', 'eventtypes.id')
        ->where('startdate', $date)
        ->orderByRaw('starttime NULLS FIRST')
        ->get();

        if($myEvents->isNotEmpty()){
            $int = 0;
            foreach($myEvents as $event){
                $events[$int]['name'] = $event->name;
                $events[$int]['type'] = $event->type;
                $events[$int]['colour'] = $event->colour;
                $events[$int]['starttime'] = $event->starttime; 
                $events[$int]['endtime'] = $event->endtime; 
                $events[$int]['location'] = $event->location;
                if(date_create($date.' '.$event->starttime) <= Carbon::now() and date_create($date.' '.$event->endtime) >=  Carbon::now()){
                    $events[$int]['isNow'] = true;
                } else {
                    $events[$int]['isNow'] = false;
                }
            $int++;
            }
        } else {
            $events = false;
        }

        if ($date == date('Y-m-d')){
            $isToday = true;
        } else {
            $isToday = false;
        }

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

        
       
        return view('staff._event', ['events' => $events, 'nextDate' => $nextDate, 'prevDate' => $prevDate, 'thisDate' => $thisDate]); 

    }

    function date_compare($element1, $element2) { 
        $datetime1 = strtotime($element1['starttime']); 
        $datetime2 = strtotime($element2['starttime']); 
        return $datetime1 - $datetime2; 
    }

    function addLink(){

        request()->validate([
            'metaclass' => ['required'],
            'name' => ['required'],
            'url'  => ['required','url'],
            'year' => ['required']            
        ]);

        $class = request('metaclass');
        $name = request('name');
        $url = request('url');
        $year = request('year');

        Quicklink::create(['seqtaId' => session('seqtaId'),
                            'class' => $class,
                            'name' => $name,
                            'url' => $url,
                            'year' => $year]);     

    }

    function deleteLink(){
        $id = request('id');
        Quicklink::find($id)->delete();
    }
}
