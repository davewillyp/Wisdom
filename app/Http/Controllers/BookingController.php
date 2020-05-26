<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Booking;
use App\Termweek;
use DateInterval;
use App\Period;
use App\Bookingitem;
use App\Bookingcategory;
use App\Term;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($date = false)
    {
        
        if (!$date){
            $thisdate = date('Y-m-d');
        } else {
            $thisdate = date('Y-m-d',strtotime($date));
        }
        // return $date;
        $days = BookingController::generateBookingDays($thisdate);   
        $bookings = BookingController::generateBookingTable($days);
        $weeks = BookingController::checkBookingWeeks($thisdate);

        //return $bookings;

        if (!$date){
            return view('staff.bookings.index', ['bookings' => $bookings, 'days' => $days, 'weeks' => $weeks]);
        } else {
            return view('staff.bookings.calendar', ['bookings' => $bookings, 'days' => $days, 'weeks' => $weeks]);   
        }
        
    }

    public function getitems(Request $request)        
    {

        $date = $request->query('date',date('Y-m-d'));
        $period = $request->query('period');

        $cats = Bookingcategory::select('name','colour','icon','id')->get();
        $booked = Booking::bookeditems($date,$period);

        $intCat = 0;
        foreach ($cats as $cat){            
            $items[$intCat]['id'] = $cat->id;
            $items[$intCat]['name'] = $cat->name;
            $items[$intCat]['colour'] = $cat->colour;
            $items[$intCat]['icon'] = $cat->icon;
            
            $allitems = Bookingitem::where('bookingcategory_id', $cat->id)->get();
            $intItems = 0;
            foreach ($allitems as $item){
                $items[$intCat]['items'][$intItems]['id'] = $item->id;
                $items[$intCat]['items'][$intItems]['name'] = $item->name;
                if ($booked->contains($item->id)){
                    $items[$intCat]['items'][$intItems]['booked'] = true;
                } else {
                    $items[$intCat]['items'][$intItems]['booked'] = false;
                }              
                $intItems++;
            }
            $intCat++;
        }
              
        //return $items;     
        return view('staff.bookings.items', ['items' => $items, 'date' => $date, 'period' => $period]);       
        
    }   

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $date = $request->input('date');
        $period = $request->input('period');
        $items = $request->input('items'); 

        $endofterm = date_create(BookingController::getEndTerm($date));
        
        $recurr = null;

        if ($request->input('repeat')){
            $intDate =  date_create($date);
            $addWeek = new DateInterval('P07D');            
            $recurr = BookingController::makeBooking($intDate->format('Y-m-d'),$period,$items,$recurr);
            if ($recurr){
                Booking::where('id', $recurr)
                        ->update(['recurr_id'=> $recurr]);                
                $intDate->add($addWeek);
                do {
                    BookingController::makeBooking($intDate->format('Y-m-d'),$period,$items,$recurr);                
                    $intDate->add($addWeek);
                } while ($intDate < $endofterm);
            }
        } else {
            BookingController::makeBooking($date,$period,$items,$recurr);
        }
               
        return redirect('/staff/bookings/'.$request->input('date'));
   }

   public function storeCategory(Request $request)
   {
        request()->validate([
            'name' => ['required'],
            'icon' => ['required'],
            'colour'  => ['required']
        ]);
        
        //write to database        
        $term = BookingCategory::create([
            'name' => request('name'),
            'icon' => request('icon'),
            'colour' => request('colour')       
        ]);

        return redirect('/admin/bookings');
   }

   public function storeItem(Request $request)
   {
        request()->validate([
            'name' => ['required'],
            'category' => ['required'],                      
        ]);
        
        //write to database        
        $term = Bookingitem::create([
            'name' => request('name'),
            'bookingcategory_id' => request('category'),
            'user_id' => session('seqtaId')
        ]);

        return redirect('/admin/bookings');
   }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
       $items = Bookingitem::orderby('bookingcategory_id')
            ->orderby('bookingitems.id')
            ->join('bookingcategories','bookingcategories.id', '=', 'bookingitems.bookingcategory_id')
            ->select('bookingitems.id','bookingitems.name', 'bookingcategories.colour', 'bookingcategories.icon')
            ->get();

       $cats = Bookingcategory::orderby('id')->get();
                            
       return view('admin.bookings.index', ['items' => $items, 'cats' => $cats]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {         
        $recurrid = $request->input('recurrid',false);
        if ($recurrid){
            Booking::where('recurr_id', $recurrid)->delete();
        } else {
            Booking::destroy($request->input('id'));    
        }           
        return true;
    }

    public function destroyCategory(Request $request)
    {
        Bookingcategory::destroy(request('id'));
        return redirect('/admin/bookings');
    }

    public function destroyItem(Request $request)
    {
        Bookingitem::destroy(request('id'));
        return redirect('/admin/bookings');
    }

    public function recurr($id, $recurr)
    {   
        return view('staff.bookings.delete', ['id' => $id, 'recurr' => $recurr]);
    }

    public function generateBookingTable($days){

         //Create Array for Periods
         $periods = Period::select('id','name','start','end')->where('type','1')->orderBy('sort')->get();
 
        //$booked = Booking::where('date_of','2020-04-28')->where('period_id', '1')->get('displayname');
        //return $booked;
         $intPeriod = 0;
         foreach ($periods as $period){            
             $bookings['periods'][$intPeriod]['periodname'] = $period->name;
             $bookings['periods'][$intPeriod]['periodstart'] = $period->start;
             $bookings['periods'][$intPeriod]['periodend'] = $period->end;
             $bookings['periods'][$intPeriod]['periodid'] = $period->id;
             $intDay = 0;
             foreach ($days as $day){                
                 $bookings['periods'][$intPeriod]['days'][$intDay]['dayname'] = $day;
                 $booked = Booking::select('displayname', 'id', 'recurr_id', 'user_id')->where('date_of',$day)->where('period_id', $period->id)->get();
                 if (!$booked->isEmpty()){
                     $intBookings = 0;                    
                     foreach ($booked as $book){
                         $bookings['periods'][$intPeriod]['days'][$intDay]['bookings'][$intBookings]['bookingid'] = $book->id;
                         $bookings['periods'][$intPeriod]['days'][$intDay]['bookings'][$intBookings]['displayname'] = $book->displayname;
                         $bookings['periods'][$intPeriod]['days'][$intDay]['bookings'][$intBookings]['recurrid'] = $book->recurr_id;
                         $bookings['periods'][$intPeriod]['days'][$intDay]['bookings'][$intBookings]['userid'] = $book->user_id;
                         $items = Booking::join('booking_bookingitem', 'bookings.id', '=', 'booking_bookingitem.booking_id')
                                         ->join('bookingitems', 'booking_bookingitem.bookingitem_id', '=', 'bookingitems.id')
                                         ->join('bookingcategories','bookingitems.bookingcategory_id','=','bookingcategories.id')
                                         ->select('bookingitems.name','bookingcategories.icon','bookingcategories.colour')
                                         ->where('bookings.id',$book->id)
                                         ->get();
                         $intItem = 0;
                         foreach ($items as $item) {                           
                             $bookings['periods'][$intPeriod]['days'][$intDay]['bookings'][$intBookings]['items'][$intItem]['name'] = $item->name;
                             $bookings['periods'][$intPeriod]['days'][$intDay]['bookings'][$intBookings]['items'][$intItem]['icon'] = $item->icon;
                             $bookings['periods'][$intPeriod]['days'][$intDay]['bookings'][$intBookings]['items'][$intItem]['colour'] = $item->colour;
                      
                             $intItem ++;
                         } 
                         $intBookings++;                       
                     }
                 } else {
                     $bookings['periods'][$intPeriod]['days'][$intDay]['bookings'] =  null;
                 }
                 $intDay++;
             }
             $intPeriod++;
         }
         
        return $bookings;

    }

    public function generateBookingDays($thisDate)
    {
         //Get Current Week
         $currentweek = Termweek::currentweek($thisDate);
         $start = $currentweek->start;
         $end = $currentweek->end;
         $weekid = $currentweek->id;         
 
         //Create Date Objects
         $itDate = date_create($start);
         $endDate = date_create($end);
         $addDay = new DateInterval('P01D');
 
         //Create Array for Days of the Week
         do {        
             $days[] = $itDate->format('Y-m-d');
             $itDate->add($addDay);
         } while ($itDate <= $endDate);

         return $days;
    }

    public function checkBookingWeeks($thisDate)
    {
        //Get Current Week
        $currentweek = Termweek::currentweek($thisDate);       
        $weekid = $currentweek->id;         

        $nextweek = $weekid + 1;
        $prevweek = $weekid - 1;

        $weeks['next'] = Termweek::where('id' , $nextweek)->value('start');
        $weeks['prev'] = Termweek::where('id' , $prevweek)->value('start');
        $weeks['name'] = $currentweek->name;

        return $weeks;
    }

    public function makeBooking($date,$period,$items,$recurr)
    {   
        $booked = Booking::select('bookings.id')
                ->join('booking_bookingitem', 'bookings.id', 'booking_bookingitem.booking_id') 
                ->where('bookings.period_id', $period)
                ->where('bookings.date_of', $date)
                ->whereIn('booking_bookingitem.bookingitem_id', $items)
                ->exists();

        if (!$booked){
                        
            $booked =  Booking::create([
                'user_id' => session('seqtaId'),
                'period_id' => $period,
                'displayname' => session('givenName') . ' ' .session('surname'),
                'date_of' => $date,
                'recurr_id' => ($recurr) ? $recurr : NULL
            ]);
            
            $booked->items()->attach($items);

            return $booked->id;
        } else {
            return false;
        }
    }

    public function getEndTerm($date){
        $endDate = Term::where('start','<=',$date)
                        ->where('end','>=',$date)
                        ->value('end');
        return $endDate;
    }
}
