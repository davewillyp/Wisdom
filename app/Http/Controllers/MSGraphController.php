<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use App\TokenStore\TokenCache;
use carbon\carbon;

class MSGraphController extends Controller
{
    public function getMailNotifications(){
                
        // Get the access token from the cache
        $tokenCache = new TokenCache();
        $accessToken = $tokenCache->getAccessToken();

        // Create a Graph client
        $graph = new Graph();
        $graph->setAccessToken($accessToken);

        $theDate = date("Y-m-d", strtotime("-1 week"));
        $url = '/me/mailFolders/inbox/messages?$select=subject&$filter=isRead ne true and receivedDateTime ge ' . $theDate . '&$count=true';

        $body = $graph->createRequest('GET', $url)
        ->setReturnType('')
        ->execute();

        $count = $body['@odata.count'];

        return $count;
        
    }

    public function getUserPhoto(){
        $tokenCache = new TokenCache();
        $accessToken = $tokenCache->getAccessToken();

        // Create a Graph client
        $graph = new Graph();
        $graph->setAccessToken($accessToken);
        
        $url = '/me/photos/48x48/$value';

        $body = $graph->createRequest('GET', $url)
        ->setReturnType('photo')
        ->execute();
      
        if (!$body){
            return false;
        } else {
            return $body;
        }        
    }

    public function getUserEvents($date = false){
    
        if (!$date){
            $date = date('2020-05-18');
        }                

        $nextDate = Carbon::parse($date)->addDay()->format('Y-m-d');
        $nextDate = '2020-05-23';        

        $tokenCache = new TokenCache();
        $accessToken = $tokenCache->getAccessToken();

        // Create a Graph client
        $graph = new Graph();
        $graph->setAccessToken($accessToken);

    
        $queryParams = array(
            '$select' => 'subject,start,end',
            '$orderby' => 'start/DateTime',
            '$filter' => "start/DateTime ge '$date' and start/DateTime lt '$nextDate'"
        );

    
        // Append query parameters to the '/me/events' url
        $getEventsUrl = '/me/events?'.http_build_query($queryParams);

        
        $events = $graph->createRequest('GET', $getEventsUrl)
        ->setReturnType(Model\Event::class)
        ->execute();

        //return $events;
        
        $int = 0;
        if ($events){
            foreach ($events as $event){
                $day = (Carbon::parse($event->getStart()->getDateTime(),'UTC')->setTimeZone('Australia/Perth')->format('N')) - 1;
               
                if(date_create($event->getStart()->getDateTime()) <= Carbon::now() and date_create($event->getEnd()->getDateTime()) >=  Carbon::now()){
                   $isNow = true;
                } else {
                    $isNow = false;
                }
                // $userevents = [$day];
                $event = array(
                    'name' => $event->getSubject(),
                    'type' => 'user',
                    'colour' => 'purple',
                    'starttime' => Carbon::parse($event->getStart()->getDateTime(),'UTC')->setTimeZone('Australia/Perth')->format('H:i:s'),
                    'endtime' => Carbon::parse($event->getEnd()->getDateTime(),'UTC')->setTimeZone('Australia/Perth')->format('H:i:s'),
                    'location' => '',
                    'isNow' => $isNow
                );
                
                $userevents[$day][] = $event; 
                              
                $int++;
            }
            return $userevents;
        } else {
            return false;
        }
    }
}
