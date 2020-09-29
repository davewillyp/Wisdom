<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Term;
use App\Termweek;
use DateInterval;
use App\Event;
use App\Eventtype;

class CalendarController extends Controller
{

  public function show($thisTerm = false)
  {
    //get term
    $thisDate = date('Y-m-d');
    $view = false;

    if (!$thisTerm){
      $term = Term::where('start', '<=', $thisDate)
                  ->orderby('start','desc')                  
                  ->first();    
    } else {
      $term = Term::where('id',$thisTerm)
                    ->first();  
    }

    $weeks = Termweek::where('term_id', $term->id)
                      ->select('start','end','name')
                      ->get();

    $addDay = new DateInterval('P01D');
    $intWeek = 0;
    foreach($weeks as $week){
      $thisDate = date_create($week->start);
      $endDate = date_create($week->end);
      $intDay = 0;
      do{
        $calendar[$intWeek]['weekname'] = $week->name;        
        $calendar[$intWeek]['day'][$intDay]['dayname'] = $thisDate->format('l');

        if ($intDay == 5)
        {
          $nextDay = clone $thisDate;
          $nextDay->add($addDay);
          $calendar[$intWeek]['day'][$intDay]['date'] = $thisDate->format('Y-m-d');
          $calendar[$intWeek]['day'][$intDay]['datetext'] = $thisDate->format('j M') . '/' .$nextDay->format('j M');
          $events = Event::join('eventtypes', 'events.eventtype_id', 'eventtypes.id')
                          ->where('events.startdate', '>=', $thisDate->format('Y-m-d'))
                          ->where('events.startdate', '<=', $nextDay->format('Y-m-d'))
                          ->select('events.id','events.name','eventtypes.colour', 'eventtypes.name as type')
                          ->orderby('eventtypes.sort')
                          ->get();

        } else { 
          $calendar[$intWeek]['day'][$intDay]['date'] = $thisDate->format('Y-m-d');
          $calendar[$intWeek]['day'][$intDay]['datetext'] = $thisDate->format('j M');       
          $events = Event::join('eventtypes', 'events.eventtype_id', 'eventtypes.id')
                          ->where('events.startdate', $thisDate->format('Y-m-d'))
                          ->select('events.id','events.name','eventtypes.colour', 'eventtypes.name as type')
                          ->orderby('eventtypes.sort')
                          ->get();
        }
        $intEvent = 0;
        foreach ($events as $event)
        {
          $calendar[$intWeek]['day'][$intDay]['events'][$intEvent]['id'] = $event->id;         
          $calendar[$intWeek]['day'][$intDay]['events'][$intEvent]['name'] = $event->name;          
          $calendar[$intWeek]['day'][$intDay]['events'][$intEvent]['typename'] = $event->type;
          $calendar[$intWeek]['day'][$intDay]['events'][$intEvent]['colour'] = $event->colour;
          $intEvent++;      
        }
        $thisDate->add($addDay);
        $intDay++;        
      } while ($intDay < 6);
      $intWeek++;    
    }
    $termStart = date_create($term->start);
    $termEnd = date_create($term->end);

    //return $calendar;
    $days = array ('Monday','Tuesday','Wednesday','Thursday','Friday','Sat/Sun');

    
    if (!$thisTerm){
      return view('staff.calendar.calendar', ['calendar' => $calendar, 'days' => $days, 'term' => $term]);
    } else {
      return view('staff.calendar._cal', ['calendar' => $calendar, 'days' => $days, 'term' => $term]);
    }
    
    
  }

  public function create($date) 
  { 
    $term = Term::where('start', '<=', $date)
                  ->orderby('start','desc')
                  ->select('id')
                  ->first();             
    
    $cats = Eventtype::orderby('id')->get();
    return view('staff.calendar.createcal', ['date' => $date, 'cats' => $cats, 'term' => $term]);

  }

  public function store(Request $request)
  {
    request()->validate([
      'name' => ['required'],
      'type' => ['required'],            
  ]);
  
  //write to database        
  $term = Event::create([
      'name' => request('name'),
      'startdate' => request('date'),
      'starttime' => request('start'),     
      'endtime' => request('end'),
      'location' => request('location'),
      'eventtype_id' => request('type'),
      'term_id' => request('term')
  ]);

  return redirect('/staff/calendar/'.request('term'));

  }


  public function destroy(Request $request)
  {
    Event::destroy(request('id'));
    return redirect('/staff/calendar/'.request('termid'));
  }

  public function calendar()
  {
    $viewData = $this->loadViewData();

    // Get the access token from the cache
    $tokenCache = new TokenCache();
    $accessToken = $tokenCache->getAccessToken();

    // Create a Graph client
    $graph = new Graph();
    $graph->setAccessToken($accessToken);

    /*
    $queryParams = array(
      '$select' => 'subject,organizer,start,end',
      '$orderby' => 'createdDateTime DESC'
    );

    // Append query parameters to the '/me/events' url
    $getEventsUrl = '/me/events?'.http_build_query($queryParams);

    $events = $graph->createRequest('GET', $getEventsUrl)
      ->setReturnType(Model\Event::class)
      ->execute();

    $viewData['events'] = $events;
    return view('calendar', $viewData);

    */
    $getGroupsUrl = 'https://graph.microsoft.com/v1.0/me/';

    $newgroups = $graph->createRequest('GET', $getGroupsUrl)
      ->setReturnType('')
      ->execute();

    //return response()->json($newgroups);
    $groups = collect($newgroups);
        
    //return print_r($newgroups);
    
    /*
    $member=false;
    foreach ($groups['value'] as $group){
        if ($group['id'] == '3d6ede5e-901a-4c5c-ac85-d83a2efa07aa'){
            $member = true;
        }
    }
    */
    return $groups;
    
  }
}