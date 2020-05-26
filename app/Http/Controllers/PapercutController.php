<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laminas\XmlRpc\Client;

class PapercutController extends Controller
{
    public function getAccountBalances(){

        $username = strstr(session('userEmail'), '@', true);
        
        $client = new Client('http://8010vpr001:9191/rpc/api/xmlrpc');
        $authtoken = env("PAPERCUT_AUTH_TOKEN");
             
        $accounts = $client->call('api.listUserSharedAccounts', [$authtoken, $username,0,100]);

        $int = 0;
        foreach ($accounts as $account){
            $client = new Client('http://8010vpr001:9191/rpc/api/xmlrpc');
            $printaccounts[$int]['name'] = $account;
            $printaccounts[$int]['balance'] = $client->call('api.getSharedAccountAccountBalance', [$authtoken, $account]);
            $int++;
        }
        //return $printaccounts;

        return view('staff._papercut',['accounts'=> $printaccounts]);

    }
}
