<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Term;
use App\Termweek;
use DateInterval;

class TermController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $terms = Term::orderby('start', 'desc')->get();
        return view('admin.terms.index', ['terms' => $terms]);
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate (Only single int for number and start must be before end)
        $start = request('start');
        $end = request('end');
        
        request()->validate([
            'number' => ['required', 'max:1'],
            'start' => ['required',"before:$end"],
            'end'  => ['required', "after:$start"],
        ]);
        
        $year = date('Y',strtotime($end));
        $name = "Term " . request('number');

        //write to database        
        $term = Term::create([
            'number' => request('number'),
            'start' => request('start'),
            'end' => request('end'),
            'name' => $name,
            'year' => $year,
        ]);

        $termId = $term->id;
        
        //generate term weeks
        $countWeek = 1;
        $date =  date_create($start);
        $finish = date_create($end);
        $addWeek = new DateInterval('P04D');
        $addWeekend = new DateInterval('P03D');

        do {
            $weekName = "Week " . $countWeek;
            $weekStart = $date->format('Y-m-d');
            $date->add($addWeek);
            $weekFinish = $date->format('Y-m-d');
            $date->add($addWeekend);
        
            Termweek::create([
                'name' => $weekName,
                'number' => $countWeek,
                'start' => $weekStart,
                'end' => $weekFinish,
                'term_id' => $termId
            ]);
        
            $countWeek++;
        } while ($date < $finish);
        
        return redirect('/admin/terms');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        Term::destroy(request('id'));
        Termweek::where('term_id', request('id'))->delete();
        return redirect('/admin/terms');
    }
}
