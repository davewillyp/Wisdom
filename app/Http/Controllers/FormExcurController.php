<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FormExcur;
use App\FormExcurStaff;
use App\FormExcurRelief;
use App\FormExcurExpense;
use App\FormExcurExpenseItem;
use App\FormExcurLogistic;
use App\FormExcurStudent;
use App\FormExcurFile;
use App\FormExcurRisk;
use App\FormExcurRa;
use App\FormExcurCare;
use App\FormExcurErp;
use App\FormExcurForm;
use Illuminate\Support\Facades\Mail;
use App\Mail\FormExcurSubmit;
use App\Mail\FormExcurApproved;
use App\Mail\FormExcurCanteen;
use App\Mail\FormExcurFinance;
use App\Mail\FormExcurBookings;
use PDF;
use DB;
use Illuminate\Support\Facades\Storage;

class FormExcurController extends Controller
{   

    function new(){
        //insert new form to database and display page 1
        $excurForm = FormExcur::create([
            'firstname' => session('givenName'),
            'surname' => session('surname'),
            'email' => session('userEmail'),
            'seqtaId' => session('seqtaId'),            
            'currentpage' => 1
        ]);

        $id = $excurForm->id;
        //return 'fail';
        return $this->show($id,1);
    }

    function show($id,$page = false){ 
        
        //$formStatus = FormExcur::where('id',$id)->value('status');        

        if (!$page){
            $page = FormExcur::where('id',$id)->value('currentpage');
        }

        //$status = $this->getPdStatus($id, $page);
        $status = "";

        $form = FormExcur::find($id);
        $status = $this->getStatus($id,$page);       
       

        if($page == 1){          
            
            return view('staff.forms.excur.page1', ['form' => $form, 'status' => $status]);

        }

        if($page == 2){
           
            $dates = false;
            $reliefTable = FormExcurRelief::where('form_excur_id', $id)->get();
            $attending = FormExcurStaff::where('form_excur_id', $id)->where('seqtaId','!=', $form->seqtaId)->get();

            if(count($attending) > 0){
                $staffattending = true;
            } else {
                $staffattending = false;
            }

                                              
            $intRow = 0;
            $staffrow = false;

            $staff =  DB::connection('seqta')
            ->select("SELECT DISTINCT id, firstname, salutation, email, surname
            FROM staff
            JOIN \"staffCampus\" on staff.id = \"staffCampus\".staff
            WHERE \"staffCampus\".campus = 1 AND staff.email != ''
            ORDER BY surname");

            foreach($attending as $attend){
                if($attend->seqtaId != $form->seqtaId){
                
                $staffrow = $staffrow.view('staff.forms.excur.reliefrow', ['row' => $intRow,
                                                                            'staffmembers' => $staff,
                                                                             'reliefReq' => $attend->reliefrequired,
                                                                             'selected' => $attend->seqtaId ]);
                
                $intRow++;
                }
            }

            
            $reliefTable = FormExcurRelief::where('form_excur_id', $id)->get();
            
            if(count($reliefTable) > 0){
                $relief = view('staff.forms.relief',['reliefTimetable' => $reliefTable]);
            } else {
                $relief = false;
            }

            return view('staff.forms.excur.page2', ['form' => $form,
                                                 'status' => $status,
                                                  'relief' => $relief,
                                                   'staffrow' => $staffrow,
                                                    'youRelief' => $form->reliefReq,
                                                    'staffattending' => $staffattending]);
        } elseif($page == 3){

            
            $expense = FormExcurExpense::where('form_excur_id',$id)->first();
            $items = FormExcurExpenseItem::where('form_excur_id',$id)->get();
            $expenseItems = false;

            foreach($items as $item){
                $expenseItems = $expenseItems . view('staff.forms.excur.expenserow', ['required' => $item->required,
                'expense' => $item->expense,
                'amount' => $item->amount,
                'cheque' => $item->cheque]);
            }
            return view('staff.forms.excur.page3',['form' => $form, 'expense' => $expense, 'expenses' => $expenseItems, 'status' => $status]);        

        } elseif($page == 4){

          
            $logistics = FormExcurLogistic::where('form_excur_id',$id)->first();          
            return view('staff.forms.excur.page4',['form' => $form, 'logistics' => $logistics, 'status' => $status]);            

        } elseif($page == 5){

           
            $students = FormExcurStudent::where('form_excur_id',$id)->get();
            $studentsView = view('staff.forms.excur.students', ['students' => $students]);
            return view('staff.forms.excur.page5',['form' => $form, 'students' => $studentsView, 'status' => $status]);
            
        }  elseif($page == 6){

            $risk = FormExcurRisk::where('form_excur_id',$id)->first();

            if($risk){
                $firstAid = $risk->firstaid;
                $firstAidStaff = $risk->firstaidStaff;
                $bronze = $risk->bronze;
                $bronzeStaff = $risk->bronzeStaff;
                $attending = $risk->attending;
                $instructors = $risk->instructors;                
                $firstAidStaffView ="";
                $bronzeStaffView = "";
                
                //Get firstaid Staff                
                if ($firstAid){
                    $firstAidStaff = json_decode($firstAidStaff, true);

                    foreach($firstAidStaff as $thisstaff){
                        $staff = FormExcurStaff::where('form_excur_id', $id)->get();                    
                        $firstAidStaffView = $firstAidStaffView. view('staff.forms.excur.staffrow', ['staffs'=> $staff, 'type' => 'FirstAid', 'name' => $thisstaff]);
                    }
                } else {
                    $firstAidStaffView = false;
                }

                if ($bronze){
                //Get Bronze Staff
                $bronzeStaff = json_decode($bronzeStaff, true);

                foreach($bronzeStaff as $thisstaff){
                    $staff = FormExcurStaff::where('form_excur_id', $id)->get();                    
                    $bronzeStaffView = $bronzeStaffView. view('staff.forms.excur.staffrow', ['staffs'=> $staff, 'type' => 'Bronze', 'name' => $thisstaff]);
                }
                } else {
                    $bronzeStaffView = false;
                }

            } else {
                $firstAid = false;
                $firstAidStaffView = false;
                $bronze = false;
                $bronzeStaffView = false;
                $attending = false;
                $instructors = false;
            }
            
            //Get Files
            $epFilesView = "";
            $cocFilesView = "";

            $epFiles = FormExcurRisk::where('form_excur_id', $id)->value('ep');
            if($epFiles){
                $files = FormExcurFile::find($epFiles);
                $epFilesView = $epFilesView. view('staff.forms.excur.file', ['fileId' => $files->id, 'name' => $files->name, 'type' => 'ep']);
            }

            $cocFiles = FormExcurRisk::where('form_excur_id', $id)->value('coc');
            if($cocFiles){
                $files = FormExcurFile::find($cocFiles);
                $cocFilesView = $cocFilesView. view('staff.forms.excur.file', ['fileId' => $files->id, 'name' => $files->name, 'type' => 'coc']);
            }

            return view('staff.forms.excur.page6',['form' => $form, 'cocFiles' => $cocFilesView, 'epFiles' => $epFilesView,
                                                    'firstAid' => $firstAid, 'firstAidStaff' => $firstAidStaffView,
                                                    'bronze' => $bronze, 'bronzeStaff' => $bronzeStaffView,
                                                    'attending' => $attending, 'instructors' => $instructors, 'status' => $status]);
            
        } elseif($page == 7){
          
            $risks = FormExcurRa::where('form_excur_id', $id)->get();
                 
            if($risks->isNotEmpty()){
                $risksView = "";
                foreach($risks as $risk){
                    $risksView = $risksView. view('staff.forms.excur.riskrow',[ 'activity' => $risk->activity,
                                                                                'hazard' => $risk->hazard,
                                                                                'risk' => $risk->risk,
                                                                                'control' => $risk->control]);
                }
            } else {
                $risksView = view('staff.forms.excur.riskrow',['activity' => false,
                'hazard' => false,
                'risk' => false,
                'control' => false]);
            }          

            return view('staff.forms.excur.page7',['risks' => $risksView, 'form' =>$form, 'status' => $status]);

        } elseif ($page == 8){

            $cares = FormExcurCare::where('form_excur_id',$id)->get();
            $careView = "";
            if($cares->isNotEmpty()){
                foreach($cares as $care){
                    if($care->form_excur_file_id){
                        $file = FormExcurFile::find($care->form_excur_file_id);

                        $fileView = view('staff.forms.excur.file',['type' => 'care',
                                                                    'fileId' => $file->id,
                                                                    'name' => $file->name]);
                       
                    } else {
                        $fileView = 0;
                    }
                    $careView = $careView. view('staff.forms.excur.carerow',['student' => $care->student,
                                                                            'concern' => $care->concern,
                                                                            'risk' => $care->risk,
                                                                            'control' => $care->control,
                                                                            'file' => $fileView
                                                                            ]);
                }


            } else {
                $students = FormExcurStudent::where('form_excur_id',$id)->where('alert_medical', 1)->get();
                foreach($students as $student){
                    $careView = $careView. view('staff.forms.excur.carerow',['student' => $student->firstname.' '.$student->surname,
                                                                            'concern' => false,
                                                                            'risk' => false,
                                                                            'control' => false,
                                                                            'file' => false]);
                }
            }
            $risksView = false;
            return view('staff.forms.excur.page8',['cares' => $careView, 'form' =>$form, 'status' => $status]);
        
        } elseif($page == 9){
            
            $erp = FormExcurErp::where('form_excur_id',$id)->first();

            if($erp){
                $erpText = $erp->erpText;
                if($erp->form_excur_file_id){
                    $file = FormExcurFile::find($erp->form_excur_file_id);
                    $fileView = view('staff.forms.excur.file',['type' => $file->type,
                                                    'fileId' => $file->id,
                                                    'name' => $file->name]);
                } else {
                    $fileView = false;
                }

            } else {                
                $fileView = false;
                $erpText = false;
            }
            return view('staff.forms.excur.page9',['form' => $form, 'file' => $fileView, 'text' => $erpText, 'status' => $status]);

        } elseif($page == 10){

            $files = FormExcurForm::where('form_excur_id',$id)->first();
            if($files){
                if ($files->letter){
                    $letter = FormExcurFile::find($files->letter);
                    $letterView = $fileView = view('staff.forms.excur.file',['type' => $letter->type,
                    'fileId' => $letter->id,
                    'name' => $letter->name]);

                } else {
                    $letterView = null;
                }

                if ($files->perm){
                    $perm = FormExcurFile::find($files->perm);
                    $permView = $permView = view('staff.forms.excur.file',['type' => $perm->type,
                    'fileId' => $perm->id,
                    'name' => $perm->name]);

                } else {
                    $permView = null;
                }
            } else {
                $letterView = null;
                $permView = null;
            }

            return view('staff.forms.excur.page10', ['form' => $form, 'letter' => $letterView, 'perm' => $permView, 'status' => $status]);
        
        } elseif($page = 11){

            $data = $this->getAllFormData($id);           
            return view ('staff.forms.excur.page11', $data);

        }


    }

    function set($id,$page, Request $request){

        if ($page == 1){

            $start = request('startDate');
            $end = request('finishDate');

            request()->validate([
                'excurname' => ['required'],               
                'startDate' => ['required',"before_or_equal:$end"],
                'finishDate'  => ['required', "after_or_equal:$start"],
                'startTime' => ['required'],
                'finishTime' => ['required'],
                'aim' => ['required'],
                'location' => ['required'],                
                'activities' => ['required'], 
            ]);

            FormExcur::where('id',$id)->update([
                'excurname' => request('excurname'),                
                'startDate' => request('startDate'),
                'finishDate' => request('finishDate'),
                'startTime' => request('startTime'),
                'finishTime' => request('finishTime'),
                'aim' => request('aim'),
                'location' => request('location'),
                'activities' => request('activities'),
                'currentpage' => 2,
                'status' => 1,                
            ]);

            return $this->show($id,2);

        } elseif($page == 2){
            
            //Empty Attending Staff & Relief
            FormExcurStaff::where('form_excur_id', $id)->delete();
            FormExcurRelief::where('form_excur_id', $id)->delete();

            //Insert Attending Staff
            $staff1 = request('staff');
            $staff2 = request('staffRelief');

            if (is_array($staff1)){
                $this->updateStafflist($staff1, 0, $id);
            }

            if (is_array($staff2)){
                $this->updateStafflist($staff2, 1, $id);
            }
            
            if($staff2){                         

                request()->validate(['name.*' => ['required'],
                'period.*' => ['required'],
                'class.*' => ['required']]);
                //Insert Relief
                
                $dates = request('date');
                $periods = request('period');
                $classes = request('class');
                $notes = request('note');
                $names = request('name');
              ;
                
                $count = count($names);
                $count = $count - 1;
                $int = 0;
                
                while($int <= $count){                   

                    FormExcurRelief::create([
                        'form_excur_id' => $id,                       
                        'name' => $names[$int],                                           
                        'date' => $dates[$int],
                        'period' => $periods[$int],
                        'class' => $classes[$int],
                        'note' => $notes[$int]
                    ]);
                    $int++;
                }
            }

            if(!request('attending')){
                FormExcur::where('id',$id)->update(['attending' => 0]);
            } 
            
            FormExcur::where('id',$id)->update(['currentpage' => 3]);
            
            
            return $this->show($id,3);

        } elseif($page == 3){
            FormExcurExpense::updateOrCreate(['form_excur_id' => $id],
                                            ['expenses' => request('expenses'),
                                            'invoiced' => request('invoiced'),
                                            'before' => request('before')]);
            
            FormExcurExpenseItem::where('form_excur_id', $id)->delete();
            
            if(request('expenses')){

                request()->validate([ 'expense.*' => ['required'],
                                        'cheque.*' => ['required'],
                                        'amount.*' => ['required'],
                                        ]);

                $expense = request('expense');
                $cheque = request('cheque');
                $amount = request('amount');
                $required = request('required');

                $count = count($expense);
                $count = $count - 1;
                $int = 0;
                
                while($int <= $count){                   

                    FormExcurExpenseItem::create([
                        'form_excur_id' => $id,
                        'expense' => $expense[$int],
                        'cheque' => $cheque[$int],
                        'amount' => $amount[$int],
                        'required' => $required[$int],                                                
                    ]);
                    $int++;
                }
                  
            }

            FormExcur::where('id',$id)->update(['currentpage' => 4]);           
            return $this->show($id,4);           
            
        } elseif($page == 4){
            FormExcurLogistic::updateOrCreate(['form_excur_id' => $id],
                                            ['bus' => request('bus'),
                                            'car' => request('car'),
                                            'transport' => request('transport'),
                                            'phone' => request('phone'),
                                            'epirb' => request('epirb')]);
            
            FormExcur::where('id',$id)->update(['currentpage' => 5]);           
            return $this->show($id,5);

        } elseif($page == 5){
            request()->validate(['firstname.*' => ['required'],
            'surname.*' => ['required'],
            'year.*' => ['required'],
            'roll.*' => ['required']
            ]);

            FormExcurStudent::where('form_excur_id', $id)->delete();

                $firstname = request('firstname');
                $surname = request('surname');
                $year = request('year');
                $code = request('roll');
                $alert = request('medical');

                $count = count($firstname);
                $count = $count - 1;
                $int = 0;
                
                while($int <= $count){                   

                    FormExcurStudent::create([
                        'form_excur_id' => $id,
                        'firstname' => $firstname[$int],
                        'surname' => $surname[$int],
                        'year' => $year[$int],
                        'code' => $code[$int],                                                
                        'alert_medical' => $alert[$int]
                    ]);
                    $int++;
                }

                FormExcur::where('id',$id)->update(['currentpage' => 6]);           
                return $this->show($id,6);

        } elseif($page == 6){
            
            $firstAid = request('firstaid');
            if($firstAid){
                $firstAidStaff = json_encode(request('staffFirstAid'));
            } else {
                $firstAidStaff = null;
            }
            
            $bronze = request('bronze');
            if($bronze){
                $bronzeStaff = json_encode(request('staffBronze'));
            } else {
                $bronzeStaff = null;
            }

            $attending = request('attending');
            $instructors = request('instructors');

            FormExcurRisk::updateOrCreate(['form_excur_id' => $id],[
                                        'firstaid' =>$firstAid,
                                        'firstaidStaff' => $firstAidStaff,
                                        'bronze' => $bronze,
                                        'bronzeStaff' => $bronzeStaff,
                                        'attending' => $attending,
                                        'instructors' => $instructors,
                                        'coc' => request('cocFile'),
                                        'ep' => request('epFile')
            ]);
            
            FormExcur::where('id',$id)->update(['currentpage' => 7]);           
            return $this->show($id,7);

        } elseif($page == 7){

            request()->validate(['activity.*' => ['required'],
            'hazard.*' => ['required'],
            'risk.*' => ['required'],
            'control.*' => ['required']
            ]);

            FormExcurRa::where('form_excur_id', $id)->delete();
            
            $activity = request('activity');
            $hazard = request('hazard');
            $risk = request('risk');
            $control = request('control');

            $count = count($activity);
            $count = $count - 1;
            $int = 0;
            
            while($int <= $count){                   

                FormExcurRa::create([
                    'form_excur_id' => $id,
                    'activity' => $activity[$int],
                    'hazard' => $hazard[$int],
                    'risk' => $risk[$int],
                    'control' => $control[$int]                                                
                ]);
                $int++;
            }             

            FormExcur::where('id',$id)->update(['currentpage' => 8]);           
            return $this->show($id,8);

        } elseif($page == 8){
            request()->validate([
                'student.*' => ['required'],
                'concern.*' => ['required'],
                'risk.*' => ['required'],
                'control.*' => ['required']
            ]);

            FormExcurCare::where('form_excur_id', $id)->delete();
                        
            $students = request('student');
            $concerns = request('concern');
            $risks = request('risk');
            $controls = request('control');
            $files = request('file');

            $count = count($students);
            $count = $count - 1;
            $int = 0;
            
            while($int <= $count){                   

                FormExcurCare::create([
                    'form_excur_id' => $id,
                    'student' => $students[$int],
                    'concern' => $concerns[$int],
                    'risk' => $risks[$int],
                    'control' => $controls[$int], 
                    'form_excur_file_id' => $files[$int]                                               
                ]);
                $int++;
            }             

            FormExcur::where('id',$id)->update(['currentpage' => 9]);           
            return $this->show($id,9);

        } elseif($page == 9){
            request()->validate([
                'erpText' => ['required_without:file']
            ]);

            FormExcurErp::updateOrCreate(['form_excur_id' => $id],[
                'erpText' => request('erpText'),
                'form_excur_file_id' => request('file')
            ]);
            
            FormExcur::where('id',$id)->update(['currentpage' => 10]);           
            return $this->show($id,10);
        
        } elseif($page == 10){
            request()->validate([
                'fileLetter' => ['required_without:filePerm']
            ]);

            FormExcurForm::updateOrCreate(['form_excur_id' => $id],[
                'letter' => request('fileLetter'),
                'perm' => request('filePerm')
            ]);

            
            return $this->show($id,10);

        } elseif($page == 11){
            $status = $this->getStatus($id,$page);   
            if($status[0]){                
                FormExcur::where('id',$id)->update(['status' => '2', 'currentpage' => '1']);
                $thisForm = FormExcur::find($id);
                
                //Send notification email
                Mail::to('david.polette@cewa.edu.au')                
                ->queue(new FormExcurSubmit($thisForm));
            }

        }

    }

    function updateStafflist($staffmembers, $reliefReq, $id){
        foreach($staffmembers as $staff){

            $staffdetails = DB::connection('seqta')
                ->table('staff')
                ->select('firstname','surname','email')
                ->where('id',$staff)
                ->first();

                FormExcurStaff::create([
                'form_excur_id' => $id,
                'seqtaId' => $staff,
                'firstname' => $staffdetails->firstname,
                'surname' => $staffdetails->surname,
                'email' => $staffdetails->email,
                'reliefrequired' =>$reliefReq
                ]);  
        }
    }
    
    function getStatus($id, $thisPage){
        
        //Form Details
        $formdetails = FormExcur::find($id);
        $formDetailsArray = $formdetails->toArray();
        unset($formDetailsArray['attending']);
        unset($formDetailsArray['reliefReq']);

        if (in_array(null, $formDetailsArray)){
            $details = false;
        } else {
            $details = true;
        }
        $thisPage == 1 ? $page = true : $page = false;

        $status[] = (object) ['name' => 'Details', 'status' => $details, 'page' => $page, 'number' => 1];

        //Staff-Relief     
        if(FormExcurStaff::where('form_excur_id', $id)->count()){
            if (FormExcurStaff::where('form_excur_id', $id)->where('reliefrequired', 1)->count()){
                if(FormExcurRelief::where('form_excur_id', $id)->count()){
                    $staffResult = true;
                } else {
                    $staffResult = false;
                }
            } else {
                $staffResult = true;
            }

        } else {
            $staffResult = false;
        }
      
        $thisPage == 2 ? $page = true : $page = false;

        $status[] = (object) ['name' => 'Staff', 'status' => $staffResult, 'page' => $page, 'number' => 2];
        
        //Expenses
        $expense = FormExcurExpense::where('form_excur_id', $id)->first();
        if($expense){
            if($expense->expenses){
                if(FormExcurExpenseItem::where('form_excur_id', $id)->count()){
                    $expenseResult = true;
                } else {
                    $expenseResult = false;
                }
            } else {
                $expenseResult = true;
            }
        } else {
            $expenseResult = false;
        }

        $thisPage == 3 ? $page = true : $page = false;

        $status[] = (object) ['name' => 'Expenses', 'status' => $expenseResult, 'page' => $page, 'number' => 3];

        //Logistics
        $logisticResult = false;
        if(FormExcurLogistic::where('form_excur_id', $id)->count()){
            $logisticResult = true;
        }
        $thisPage == 4 ? $page = true : $page = false;

        $status[] = (object) ['name' => 'Logistics', 'status' => $logisticResult, 'page' => $page, 'number' => 4];

        //Students
        $studentResult = false;
        if(FormExcurStudent::where('form_excur_id', $id)->count()){
            $studentResult = true;
        }
        $thisPage == 5 ? $page = true : $page = false;

        $status[] = (object) ['name' => 'Students', 'status' => $studentResult, 'page' => $page, 'number' => 5];


        //Risk Management
        $riskman = FormExcurRisk::where('form_excur_id', $id)->first();
        $riskmanResult = true;
        if($riskman){
            if($riskman->firstaid){
                if(!$riskman->firstaidStaff){
                    $riskmanResult = false;
                }
            }
            if($riskman->bronze){
                if(!$riskman->bronzeStaff){
                    $riskmanResult = false;
                }
            }
        } else {
            $riskmanResult = false;
        }
        
        $thisPage == 6 ? $page = true : $page = false;

        $status[] = (object) ['name' => 'Risk Management', 'status' => $riskmanResult, 'page' => $page, 'number' => 6];

        //Risks
        $riskResult = false;
        if(FormExcurRa::where('form_excur_id', $id)->count()){
            $riskResult = true;
        }
        $thisPage == 7 ? $page = true : $page = false;

        $status[] = (object) ['name' => 'Risk Assessment', 'status' => $riskResult, 'page' => $page, 'number' => 7];

        //Care Plans
        $careResult = false;
        if($studentResult){                            
            if($alertCount = FormExcurStudent::where('form_excur_id', $id)->where('alert_medical', 1)->count()){
                if($alertCount <= FormExcurCare::where('form_excur_id', $id)->count()){
                    $careResult = true;
                }
            } else {
                $careResult = true;
            }
        }
        $thisPage == 8 ? $page = true : $page = false;

        $status[] = (object) ['name' => 'Care Plans', 'status' => $careResult, 'page' => $page, 'number' => 8];

        //Emergency Response
        $erpResult = false;
        if(FormExcurErp::where('form_excur_id', $id)->count()){
            $erp = FormExcurErp::where('form_excur_id', $id)->first();

            if($erp->erpText or $erp->form_excur_file_id){
                $erpResult = true;
            }
        }
        $thisPage == 9 ? $page = true : $page = false;

        $status[] = (object) ['name' => 'Emergency Response', 'status' => $erpResult, 'page' => $page, 'number' => 9];

        //Forms
        $formResult = false;     
        if(FormExcurForm::where('form_excur_id', $id)->count()){
            $forms = FormExcurForm::where('form_excur_id', $id)->first();

            if($forms->letter or $forms->perm){
                $formResult = true;
            }
        }
        $thisPage == 10 ? $page = true : $page = false;

        $status[] = (object) ['name' => 'Parent Forms', 'status' => $formResult, 'page' => $page, 'number' => 10];

        $totalStatus = true;
        foreach($status as $stat){
            if($stat->status == false){
                $totalStatus = false;
            }
        }

        $AllStatus[] = $totalStatus;
        $AllStatus[] = $status;
        
        //Return Status
        return $AllStatus;

    }

    function getAllFormData($id){
        $form = FormExcur::find($id);
        $staff = FormExcurStaff::where('form_excur_id',$id)->get();
        $relief = FormExcurRelief::where('form_excur_id',$id)->get();
        $expense = FormExcurExpense::where('form_excur_id',$id)->first();
        $items = FormExcurExpenseItem::where('form_excur_id',$id)->get();
        $logistics = FormExcurLogistic::where('form_excur_id',$id)->first();
        $students = FormExcurStudent::where('form_excur_id',$id)->get();
        $riskMan = FormExcurRisk::where('form_excur_id',$id)->first();
        $risks = FormExcurRa::where('form_excur_id',$id)->join('form_risks', 'form_excur_ras.risk', '=', 'form_risks.id' )->get();
        $care = FormExcurCare::where('form_excur_id',$id)->join('form_risks', 'form_excur_cares.risk', '=', 'form_risks.id' )->get();
        $erp = FormExcurErp::where('form_excur_id',$id)->first();
           
          
        $data = ['form' => $form,
        'reliefs' => $relief,
         'staffs' => $staff,
         'expense' => $expense,
         'items' => $items,
         'logistics' => $logistics,
         'students' => $students,
         'riskman' => $riskMan,
         'risks' => $risks,
         'cares' => $care,
         'erp' => $erp
        ];

        return $data;
    }

    function getPDF($id){

        $data = $this->getAllFormData($id);
        $date = date_create()->format('dmYHis');
        $pdf = PDF::loadView('staff.forms.excur._formdetails', $data);
        return $pdf->download($data['form']->excurname.'-'.$date.'.pdf');
    }

    function showAdmin($id){

        $data = $this->getAllFormData($id);
        
        //get all attachments
        //Risk Management
        $cocId = FormExcurRisk::where('form_excur_id', $id)->value('coc');
        if($cocId){
            $cocFile = FormExcurFile::find($cocId);
            $cocView = view('admin.forms.excur.file', ['type' => $cocFile->type, 'fileId' => $cocFile->id, 'name' => $cocFile->name]);            
        } else {
            $cocView = "";
        }

        $epId = FormExcurRisk::where('form_excur_id', $id)->value('ep');
        if($epId){
            $epFile = FormExcurFile::find($epId);
            $epView = view('admin.forms.excur.file', ['type' => $epFile->type, 'fileId' => $epFile->id, 'name' => $epFile->name]);            
        } else {
            $epView = "";
        }

        //Care Plans
        $careplans = FormExcurCare::where('form_excur_id', $id)->where('form_excur_file_id', '!=', 0)->get();
        $careView = "";
        foreach($careplans as $care){
            $careFile = FormExcurFile::find($care->form_excur_file_id);
            $careView = $careView . view('admin.forms.excur.file', ['type' => $careFile->type, 'fileId' => $careFile->id, 'name' => $careFile->name]);            
        }

        //Erp
        $erp = formExcurErp::where('form_excur_id', $id)->value('form_excur_file_id');
        if($erp){
            $erpFile = FormExcurFile::find($erp);
            $erpView = view('admin.forms.excur.file', ['type' => $erpFile->type, 'fileId' => $erpFile->id, 'name' => $erpFile->name]);
        } else {
            $erpView = "";
        }    

        //Letter & Form
        $forms = formExcurForm::where('form_excur_id', $id)->first();
        if($forms->letter){
            $letterFile = FormExcurFile::find($forms->letter);
            $letterView = view('admin.forms.excur.file', ['type' => $letterFile->type, 'fileId' => $letterFile->id, 'name' => $letterFile->name]);
        }else{
            $letterView = "";
        }

        if($forms->perm){
            $permFile = FormExcurFile::find($forms->perm);
            $permView = view('admin.forms.excur.file', ['type' => $permFile->type, 'fileId' => $permFile->id, 'name' => $permFile->name]);
        }else{
            $permView = "";
        }

        //Assign Files to View 
        $data['fileCoc'] = $cocView;
        $data['fileEp']  = $epView;
        $data['fileCare'] = $careView;
        $data['fileErp'] = $erpView;
        $data['fileLetter'] = $letterView;
        $data['filePerm'] = $permView;


        //dd($data);
        return view('admin.forms.excur.form',$data);

    }

    function approveForm($id){

        FormExcur::where('id',$id)->update(['status' => '4', 'currentpage' => '11']);
        $thisForm = FormExcur::find($id);

        //Send Email to Form Owner - Form Approved
        Mail::to('david.polette@cewa.edu.au')                
              ->queue(new FormExcurApproved($thisForm));

        //Send Email to Canteen - Students Missing
        $students = FormExcurStudent::where('form_excur_id', $id)->count();
        $data['form'] = $thisForm;
        $data['students'] = $students;
        Mail::to('david.polette@cewa.edu.au')
            ->queue(new FormExcurCanteen($data));


        //Send Email to Business Manager - Expenses
        $expense = FormExcurExpense::where('form_excur_id', $id)->first();
        if($expense->expenses){
            $data['expense'] = $expense;
            $data['form'] = $thisForm;
            $data['expenses'] = FormExcurExpenseItem::where('form_excur_id', $id)->get();       
            
           Mail::to('david.polette@cewa.edu.au')
              ->queue(new FormExcurFinance($data));
        }
        //Send Email to Admin - Car/Bus Bookings
        $logistics = FormExcurLogistic::where('form_excur_id', $id)->first();
       
        if($logistics->car or $logistics->phone or $logistics->epirb or $logistics->bus){
        $data['logistics'] = $logistics;
        $data['form'] = $thisForm;

        Mail::to('david.polette@cewa.edu.au')
               ->queue(new FormExcurBookings($data));
        }
    }

    function getFile($id){

        $file = FormExcurFile::find($id);
        if($file->seqtaId == session('seqtaId') or in_array('0ac21a47-b78d-4bb1-9531-096efee2a4af',session('groups')) or in_array('b2a1b59a-efe1-46cb-a1fb-21e26a5723f7', session('groups'))){
            return Storage::download($file->path);
        } else {
            abort(403, 'Access denied');
        }

    }

    function deleteFile($id,$fileId){
        $seqtaId = FormExcur::where('id',$id)->value('seqtaId');
        if($seqtaId == session('seqtaId')){
           
            $file = FormExcurFile::find($fileId);
            $path = $file->path;

            $deleted = Storage::delete($path);

            if($deleted){
                if($file->type == 'coc'){
                    FormExcurRisk::where('form_excur_id',$id)->update(['coc' => null]);
                }elseif($file->type == 'ep'){
                    FormExcurRisk::where('form_excur_id',$id)->update(['ep' => null]);
                }elseif($file->type == 'erp'){
                    FormExcurErp::where('form_excur_id',$id)->update(['form_excur_file_id' => null]);
                }elseif($file->type == 'letter'){
                    FormExcurForm::where('form_excur_id',$id)->update(['letter' => null]);
                }elseif($file->type == 'form'){
                    FormExcurForm::where('form_excur_id',$id)->update(['form' => null]);
                }elseif($file->type == 'care'){
                    FormExcurCare::where('form_excur_file_id',$fileId)->update(['form_excur_file_id' => 0]);
                }
                $file->delete();
            }
        }
    }
}
