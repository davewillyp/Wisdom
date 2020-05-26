<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Laminas\XmlRpc\Client;
use App\Termweek;

class TestController extends Controller
{
    public function test(Request $request) {
        return true;
            
    }

    public function papercut(Request $request) {
        
        $client = new Client('http://8010vpr001:9191/rpc/api/xmlrpc');
        $authtoken = env("PAPERCUT_AUTH_TOKEN");
             
        $result = $client->call('api.listUserSharedAccounts', [$authtoken, "david.polette",0,100]);

        return $result;

    }

    public function info(){

        return phpinfo();
        
    }
}
