<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FormPd;
use App\Timetable;
use App\FormPdRelief;
use App\FormPdReliefdate;
use App\FormPdExpense;
use App\FormPdLogistics;
use App\Mail\FormPDSubmit;
use App\Mail\FormPdApproved;
use App\Mail\FormPdFinance;
use App\Mail\FormPdBookings;
use Illuminate\Support\Facades\Mail;
use PDF;

class FormPdController extends Controller
{   

    function show(){
        $forms = FormPd::select('form_pds.*', 'form_status_types.name','form_status_types.colour')->join('form_status_types','form_pds.status', '=', 'form_status_types.id')->where('seqtaId',session('seqtaId'))->where('status', '!=', '5')->orderBy('form_pds.created_at')->get();           
        return view('staff.forms.index',['forms' => $forms]);
    }

//PD Form Actions
    
    function new(){
        //insert new form to database and display page 1
        $pdForm = FormPd::create([
            'firstname' => session('givenName'),
            'surname' => session('surname'),
            'email' => session('userEmail'),
            'seqtaId' => session('seqtaId'),            
            'currentpage' => 1
        ]);

        $id = $pdForm->id;
        //return 'fail';
        return $this->showPd($id,1);
    }

    function showPd($id,$page = false){ 
        
        $formStatus = FormPd::where('id',$id)->value('status');        

        if (!$page){
            $page = FormPd::where('id',$id)->value('currentpage');
        }

        $status = $this->getPdStatus($id, $page);

        if($page == 1){
            $form = FormPd::find($id);
            if($form->priorities){
                $priority = json_decode($form->priorities);
            } else {
                $priority = false;
            }            
            return view('staff.forms.pd.page1', ['form' => $form, 'status' => $status, 'priorities' => $priority]);
        }
        elseif($page == 2){
            $form = FormPd::find($id);
            $reliefDates = FormPdReliefdate::where('form_pd_id', $id)->first();            
            $reliefClasses = FormPdRelief::where('form_pd_id', $id)->get();            
            if ($reliefClasses->isNotEmpty()){
                $reliefView = view('staff.forms.relief', ['reliefTimetable' => $reliefClasses, 'status' => $status]);
            } else {
                $reliefView = false;
            }
            return view('staff.forms.pd.page2', ['form' => $form, 'dates' => $reliefDates, 'relief' => $reliefView, 'status' => $status]);
        }

        elseif($page == 3){
            $form = FormPd::find($id);
            $expenses = FormPdExpense::where('form_pd_id', $id)->first();
            return view('staff.forms.pd.page3', ['form' => $form, 'expenses' => $expenses, 'status' => $status]);
        }

        elseif($page == 4){
            $form = FormPd::find($id);
            $logistics = formPdLogistics::where('form_pd_id', $id)->first();            
            return view('staff.forms.pd.page4', ['form' => $form, 'logistics' => $logistics, 'status' => $status]);
        }

        elseif($page == 5){
            $data = $this->getAllFormData($id);      
            return view('staff.forms.pd.page5', $data);
        }

        
       
    }

    function setPd($id,$page){

        if ($page == 1){

            $start = request('startDate');
            $end = request('finishDate');

            request()->validate([
                'pdname' => ['required'],
                'venue' => ['required'],
                'startDate' => ['required',"before_or_equal:$end"],
                'finishDate'  => ['required', "after_or_equal:$start"],
                'startTime' => ['required'],
                'finishTime' => ['required'],
                'hours' => ['required', 'numeric','gt:0'],
                'outcome' => ['required'],                
                'priority' => ['required'], 
            ]);

            $priority = json_encode(request('priority'));

            $pdForm = FormPd::where('id',$id)->update([
                'pdname' => request('pdname'),
                'venue' => request('venue'),
                'startDate' => request('startDate'),
                'finishDate' => request('finishDate'),
                'startTime' => request('startTime'),
                'finishTime' => request('finishTime'),
                'hours' => request('hours'),
                'outcome' => request('outcome'),
                'currentpage' => 2,
                'status' => 1,
                'priorities' => $priority
            ]);

            return $this->showPd($id,2);

        } elseif($page == 2){

            request()->validate([
                'relief' => ['required']           
            ]);

            if(request('relief')){

                $start = request('reliefStartDate');
                $end = request('reliefFinishDate');

                request()->validate([
                    'reliefStartTime' => ['required'],
                    'reliefStartDate' => ['required',"before_or_equal:$end"],
                    'reliefFinishDate' => ['required', "after_or_equal:$start"],
                    'reliefFinishTime'  => ['required'],
                    'date' => ['required'],
                    'period' => ['required'],
                    'class' => ['required']                                                
                ]);

                //update relief dates
                FormPdReliefdate::updateOrCreate(
                    ['form_pd_id' => $id],
                    ['startDate' => request('reliefStartDate'),
                    'startTime' => request('reliefStartTime'),
                    'finishDate' => request('reliefFinishDate'),
                    'finishTime' => request('reliefFinishTime')]                    
                );
                
                //update relief classes
                $dates = request('date');
                $periods = request('period');
                $classes = request('class');
                $notes = request('note');
                $name = request('name');

                $count = count($dates);
                $count = $count - 1;
                $int = 0;

                FormPdRelief::where('form_pd_id', $id)->delete();

                while($int <= $count){
                    FormPdRelief::create([                        
                        'form_pd_id' => $id,
                        'name' => $name[$int],
                        'date' => $dates[$int],
                        'period' => $periods[$int],
                        'class' => $classes[$int],
                        'note' => $notes[$int]
                    ]);
                    $int++;
                }

                $pdForm = FormPd::where('id',$id)->update([
                    'relief' => 1, 
                    'currentpage' => 3                   
                ]);

                
            } else {
                FormPd::where('id',$id)->update([
                    'relief' => 0,
                    'currentpage' => 3                    
                ]);
                FormPdRelief::where('form_pd_id',$id)->delete();
                FormPdReliefdate::where('form_pd_id',$id)->delete();

            }

            return $this->showPd($id,3);

        } elseif( $page == 3){

            request()->validate([
                'fee' => ['required']           
            ]);

            if(request('fee') == 1){

                request()->validate([
                    'amount' => ['required'],
                    'invoiced' => ['required'],
                    'before' => ['required'],                                                           
                ]);            

            } 

            FormPdExpense::updateOrCreate(
                ['form_pd_id' => $id],
                ['fee' => request('fee'),
                'amount' => request('amount'),
                'invoiced' => request('invoiced'),
                'before' => request('before'),
                'claim' => request('claim')]
            );

            FormPd::where('id',$id)->update([                
                'currentpage' => 4                    
            ]);
            
            return $this->showPd($id,4);

        } elseif($page == 4){

            request()->validate([
                'car' => ['required'],
                'accommodation' => ['required']
            ]);

            if (request('car')){

                $start = request('carPickupDate');
                $end = request('carDropoffDate');

                request()->validate([
                    'carPickupDate' => ['required', "before_or_equal:$end"],
                    'carPickupTime' => ['required'],
                    'carDropoffDate' => ['required', "after_or_equal:$end"],
                    'carDropoffTime' => ['required']
                ]);
            }

            if (request('accommodation')){

                $start = request('arrival');
                $end = request('departure');

                request()->validate([
                    'arrival' => ['required', "before_or_equal:$end"],
                    'departure' => ['required', "after_or_equal:$end"]                   
                ]);
            }

            FormPdLogistics::updateOrCreate(
                ['form_pd_id' => $id],
                ['car' => request('car'),
                'pickupDate' => request('carPickupDate'),
                'pickupTime' => request('carPickupTime'),
                'dropoffDate' => request('carDropoffDate'),
                'dropoffTime' => request('carDropoffTime'),
                'accommodation' => request('accommodation'),
                'arrival' => request('arrival'),
                'departure' => request('departure')
                ]
            );       
                 
            
            return $this->showPd($id,4);
        
        } elseif($page == 5){

            FormPd::where('id',$id)->update([                                
                'status' => 2                    
            ]);
            
            $thisForm = FormPd::find($id);

            Mail::to('david.polette@cewa.edu.au')                
                ->queue(new FormPDSubmit($thisForm));              
            
        }

    }

    function getPdRelief($id){

        $start = request('reliefStartDate');
        $end = request('reliefFinishDate');

        request()->validate([
            'reliefStartTime' => ['required'],
            'reliefStartDate' => ['required',"before_or_equal:$end"],
            'reliefFinishDate' => ['required', "after_or_equal:$start"],
            'reliefFinishTime'  => ['required'],            
        ]);
        
        $start = request('reliefStartDate');
        $end = request('reliefFinishDate');
        $staffId = session('seqtaId');
        $staffId = 207;

        $timetable = new Timetable;
        $reliefTimetable =  $timetable->getrelief($staffId,request('reliefStartDate'),request('reliefStartTime'),request('reliefFinishDate'),request('reliefFinishTime'));        
        return view('staff.forms.pd.relief', ['reliefTimetable' => $reliefTimetable]);
    
    }

    function getPdStatus($id, $thisPage){

        //check form details
        $details = false;
        if ($formDetails = FormPd::find($id)){
            $formDetailsArray = $formDetails->toArray();
            unset($formDetailsArray['relief']);
            //dd($formDetailsArray);
            if (!in_array(null, $formDetailsArray)){             
                $details = true;
            }
        } 
        $thisPage == 1 ? $page = true : $page = false;
        $status[] = (object) ['name' => 'Details', 'status' => $details, 'page' => $page, 'number' => 1];

        
        //checkrelief
        $relief = false;
        if ($formDetails = FormPd::where('id', $id)->first()){
            if($formDetails->relief === 1){
                if ($relief = FormPdRelief::where('form_pd_id',$id)){
                    $relief = true;
                }
            } elseif($formDetails->relief === 0) {
                $relief = true;
            } 
        }
        $thisPage == 2 ? $page = true : $page = false;
        $status[] = (object) ['name' => 'Relief', 'status' => $relief, 'page' => $page, 'number' => 2];

        //checkexpenses
        if ($expenses = FormPdExpense::where('form_pd_id',$id)->first()){
            $expenses = true;
        } else {
            $expenses = false;
        }
        $thisPage == 3 ? $page = true : $page = false;
        $status[] = (object) ['name' => 'Expenses', 'status' => $expenses, 'page' => $page, 'number' => 3];


        //checklogistics
        if ($logistics = FormPdLogistics::where('form_pd_id',$id)->first()){
            $logistics = true;
        } else {
            $logistics = false;
        }
        $thisPage == 4 ? $page = true : $page = false;
        $status[] = (object) ['name' => 'Logistics', 'status' => $logistics, 'page' => $page, 'number' => 4];
    
        $totalStatus = true;
        foreach($status as $stat){
            if($stat->status == false){
                $totalStatus = false;
            }
        }

        $AllStatus[] = $totalStatus;
        $AllStatus[] = $status;        
        
        return $AllStatus;        
    }

    function archivePd($id){

        FormPd::where('id',$id)->update([                            
            'status' => 'archive'                    
        ]); 

    }

    function showAdminPdAll(){
        $forms = FormPd::select('form_pds.*', 'form_status_types.name','form_status_types.colour')->join('form_status_types','form_pds.status', '=', 'form_status_types.id')->where('status', '!=', '5')->where('status', '!=', '0')->orderBy('form_pds.created_at')->get();           
        return view('admin.forms.pd.index',['forms'=>$forms]);
    }

    function showAdmin($id){
        $data = $this->getAllFormData($id);           
        return view('admin.forms.pd.form', $data);
    }

    function getPDF($id){

        $data =  $this->getAllFormData($id); 
        $date = date_create()->format('dmYHis');        
        $pdf = PDF::loadView('staff.forms.pd._formdetails', $data);        
        return $pdf->download($data['form']->pdname.'-'.$date.'.pdf');         

    }

    function getAllFormData($id){

        $form = FormPd::find($id);
        if ($form->relief){
            $relief = FormPdRelief::where('form_pd_id', $id)->get();
        } else {
            $relief = false;               
        }      
        if($form->priorities){
            $priority = json_decode($form->priorities);
        } else {
            $priority = false;
        }            
        $expenses = FormPdExpense::where('form_pd_id', $id)->first();
        $logistics = formPdLogistics::where('form_pd_id', $id)->first();        
        $data = ['form' => $form, 
                'reliefs' => $relief,
                'logistics' => $logistics,
                'expenses' => $expenses,
                'priorities' => $priority];

        return $data;

    }

    function approveForm($id){

        FormPd::where('id',$id)->update(['status' => '4', 'currentpage' => '5']);
        $thisForm = FormPd::find($id);

        //Send Email to Form Owner - Form Approved
        Mail::to($thisForm->email)                
               ->queue(new FormPdApproved($thisForm));

        //Send Email to Business Manager - Expenses
        $expenses = FormPdExpense::where('form_pd_id',$id)->first();

        if($expenses->fee){
            $data['form'] = $thisForm;
            $data['expense'] = $expenses;

            Mail::to('david.polette@cewa.edu.au')                
            ->queue(new FormPdFinance($data));
        }
        //Send Email to Admin - Car/Bus Bookings
        $logistics = FormPdLogistics::where('form_pd_id',$id)->first();
        if($logistics->car or $logistics->accommodation){
            $data['form'] = $thisForm;
            $data['logistics'] = $logistics;

            Mail::to('david.polette@cewa.edu.au')                
            ->queue(new FormPdBookings($data));
        }


    }
}
