<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dutylocation;
use App\Period;
use App\Duty;
use DB;

class DutyController extends Controller
{
    public function locationIndex()
    {

        $locations = Dutylocation::select('name','sort')->get();
        return view('admin.duty.index', ['locations' => $locations]);

    }

    public function storeLocation(Request $request)
    {
        request()->validate([
            'name' => ['required'],            
        ]);
           
        //write to database        
        $term = Dutylocation::create([
            'name' => request('name'),
            'sort' => 1,            
        ]);

        return DutyController::locationIndex();
    }

    public function deleteLocation(Request $request)
    {
        Dutylocation::destroy(request('id'));
        return DutyController::locationIndex();
    }

    public function show($day = false)
    {
        if ($day){
            $thisday = $day;
        } else {
            $thisday = 1;
        }

        $locations = Dutylocation::select('name','id')->orderBy('sort')->orderBy('id')->get();        
        $periods = Period::select('name','id')->where('type',2)->orWhere('type', 3)->orderBy('sort')->get();

        $intLoc = 0;
        foreach($locations as $location){
            $duty[$intLoc]['locationid'] = $location->id;
            $duty[$intLoc]['locationname'] = $location->name;
            
            $intPeriod = 0;
            foreach ($periods as $period){

                $duty[$intLoc]['periods'][$intPeriod]['periodid'] = $period->id;
                $duty[$intLoc]['periods'][$intPeriod]['periodname'] = $period->name;

                $dutyholder = Duty::select('user_id','name')
                                    ->where('dutylocation_id', $location->id)
                                    ->where('period_id',$period->id)
                                    ->where('day', $thisday)
                                    ->first();

                if ($dutyholder){
                    $duty[$intLoc]['periods'][$intPeriod]['periodholderid'] = $dutyholder->user_id;
                    $duty[$intLoc]['periods'][$intPeriod]['periodholdername'] = $dutyholder->name;
                }
            
                $intPeriod++;

            }
            $intLoc++;
        }

        //return $thisday;
      
        if (!$day){        
            return view('staff.duty.index', ['duties' => $duty, 'periods' => $periods, 'day' => $thisday]);
        } else {
            return view('staff.duty._duty', ['duties' => $duty, 'periods' => $periods, 'day' => $thisday]);
        }

    }

    public function userSearch($name)
    {
        
        $users = DB::connection('seqta')->select("SELECT DISTINCT id, email, surname, firstname
        FROM staff
        JOIN \"staffCampus\" on staff.id = \"staffCampus\".staff
        WHERE \"staffCampus\".campus = 1 AND staff.email != ''
        AND (firstname ILIKE '$name%' or surname ILIKE '$name%')
        ORDER BY surname
        LIMIT 5");
       
        //return $staff;
        
        return view('staff.duty._userlist',['users' => $users]);
    }

    public function storeUser(Request $request)
    {   
        if (request('user') == 'delete' & request('name') == 'delete'){

            $thisduty = Duty::where('period_id',request('period'))
                        ->where('dutylocation_id', request('location'))
                        ->where('day',request('day'));                        

            $thisduty->delete();

        } else {
        
            Duty::updateOrCreate(
                [                
                    'period_id' => request('period'),
                    'dutylocation_id' => request('location'),
                    'day' => request('day'),                
                ],
                [
                    'user_id' => request('user'),
                    'name' => request('name'),
                    'updated_by'=> session('seqtaId'),
                ]
                );
        }
        
        return DutyController::show(request('day'));
    }

    
}
