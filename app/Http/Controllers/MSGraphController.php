<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use App\TokenStore\TokenCache;
use carbon\carbon;
use Illuminate\Support\Facades\Http;

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
            $date = date('Y-m-d');
        } else {
            $date = date($date);
        }                

        $nextDate = Carbon::parse($date)->addDays(4)->format('Y-m-d');

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

          
        $int = 0;
        if ($events){
            foreach ($events as $event){
                $day = (Carbon::parse($event->getStart()->getDateTime(),'UTC')->setTimeZone('Australia/Perth')->format('N')) - 1;
               
                if(Carbon::parse($event->getStart()->getDateTime(),'UTC') <= Carbon::now() and Carbon::parse($event->getEnd()->getDateTime(),'UTC') >=  Carbon::now()){
                   $isNow = true;
                } else {
                    $isNow = false;
                }
                
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

    function getRecentFiles() {

        // Get the access token from the cache
        $tokenCache = new TokenCache();
        $accessToken = $tokenCache->getAccessToken();

        // Create a Graph client
        $graph = new Graph();
        $graph->setAccessToken($accessToken);

        $url = '/me/drive/recent?$top=5&$select=name,webUrl';

        $body = $graph->createRequest('GET', $url)
        ->setReturnType(Model\DriveItem::class)
        ->execute();   

        return view('staff._recentFiles',['files'=> $body]);
        //return $body;
    }

    function createEvent(){

        // Get the access token from the cache
        $tokenCache = new TokenCache();
        $accessToken = $tokenCache->getAccessToken();

        // Create a Graph client
        $graph = new Graph();
        $graph->setAccessToken($accessToken);

        $event1 = [
                    'subject' => "My Class1",
                    'body' =>   [
                                    'contentType' => 'HTML',
                                    'content' => 'My class on Monday Period 3'
                                ],
                    'start' =>  [    
                                    'dateTime' => '2020-09-24T12:00:00',
                                    'timeZone' => 'Australia/Perth'
                                ],
                    'end'   =>  [
                                    'dateTime' => '2020-09-24T13:00:00',
                                    'timeZone' => 'Australia/Perth'
                                ],
                    
                   'recurrence' => 
                                    [
                                        'pattern' =>    [
                                                            'type' => 'weekly', 'interval' => 1, 'daysOfWeek' =>    [
                                                                                                                        'Thursday'
                                                                                                                    ]
                                                        ],
                                        'range' => 
                                                        [
                                                            'type' => 'endDate','startDate' => '2020-09-24', 'endDate' => '2020-12-01'
                                                        ]
                                    ],
    
                    'location' => [
                                    'displayName' =>'My Room'
                                ]
                ];

                $event2 = [
                    'subject' => "My Class2",
                    'body' =>   [
                                    'contentType' => 'HTML',
                                    'content' => 'My class on Monday Period 3'
                                ],
                    'start' =>  [    
                                    'dateTime' => '2020-09-24T13:30:00',
                                    'timeZone' => 'Australia/Perth'
                                ],
                    'end'   =>  [
                                    'dateTime' => '2020-09-24T14:00:00',
                                    'timeZone' => 'Australia/Perth'
                                ],
                    
                   'recurrence' => 
                                    [
                                        'pattern' =>    [
                                                            'type' => 'weekly', 'interval' => 1, 'daysOfWeek' =>    [
                                                                                                                        'Thursday'
                                                                                                                    ]
                                                        ],
                                        'range' => 
                                                        [
                                                            'type' => 'endDate','startDate' => '2020-09-24', 'endDate' => '2020-12-01'
                                                        ]
                                    ],
    
                    'location' => [
                                    'displayName' =>'My Room'
                                ]
                ];

        $url = '/me/events';

        $request['requests'][] = ['id' => 1, 'method' => 'POST', 'url' => $url, 'body' => $event1, 'headers' => ["Content-Type" => "application/json"]];
                                
        $request['requests'][] = ['id' => 2, 'method' => 'POST', 'url' => $url, 'body' => $event2, 'headers' => ["Content-Type" => "application/json"]];
                                
        //return json_encode($request);
        
                    $graph->setProxyPort("localhost:8866"); //capture request and response
        $response = $graph->createRequest("POST", '/$batch')
                    ->attachBody($request)                    
                    ->execute();
        
        dd($response);
    }

    public function calendarAuth(){ 

    

        $response = Http::asForm()->post(env('OAUTH_AUTHORITY').'/oauth2/v2.0/token HTTP/1.1', [
            'client_id' => env('OAUTH_APP_ID'),
            'scope' => 'https://graph.microsoft.com/.default',           
            'client_secret' => env('OAUTH_APP_PASSWORD'),
            'grant_type' => 'client_credentials',
            'prompt' => 'admin_consent'            
        ]);

        return $response;
            
    }

}
