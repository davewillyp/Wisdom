<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Quicklink;
use App\Link;
use DB;
use Illuminate\Support\Facades\Storage;

class JuniorController extends Controller
{
    public function home(){

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

        $teachers = $this->getJuniorTeachers();

        $links = Link::where('linktype_id', 7)->orderBy('sort')->get();

        return view('junior.index',['greeting' => $greeting, 'teachers' => $teachers, 'links' => $links]);
    }

    public function getJuniorTeachers(){

        $teachers = Quicklink::where('year', '<', '11')
                                ->select('seqtaId')                        
                                ->distinct()
                                ->pluck('seqtaId')
                                ->toArray();       
       
        $teacherdetails = DB::connection('seqta')
                            ->table('staff')
                            ->whereIn('id', $teachers)
                            ->select('code','title','surname','id')
                            ->get();
        
        return $teacherdetails;

    }

    public function getImage($code){

        $filePath = "/mazeimage/$code.bmp";

        if (!Storage::exists($filePath)) {
            abort(404);
        }

        $fullPath = config('filesystems.disks.local.root') . DIRECTORY_SEPARATOR . $filePath;

        return response()->file($fullPath);

    }

    public function getLinks($id){
        $links = Quicklink::where('seqtaId',$id)->get();

        return view('junior.links',['links' => $links]);
    }
}
