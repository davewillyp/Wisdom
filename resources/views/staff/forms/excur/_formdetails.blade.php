<style>
        body {
	        font-family: 'FreeSans', sans-serif;
        }
        h1{
            margin-bottom:0px;
        }
        h4{
            margin-bottom:5px;
        }
        h3{
            margin-top:5px;
        }
        th{
            text-align: left;
        }     
        @media print
        {
            .page-break  { display:block; page-break-inside: avoid; }

        }
        .printOnly {
            display : none;
        }

        @media print {
            .printOnly {
            display : block;
            }
        }
        .padding-table-columns th, td
        {
            padding:0 15px 0 0; /* Only right padding*/
        }      
</style>
    <h1>Excursion Form</h1>
    <h3>{{ $form->excurname }} - {{ $form->firstname.' '.$form->surname }}</h3>
    <br>
<div>
    <h4>1. Course Details</h4>
    <table class='padding-table-columns'>                
        <tr>
            <td>
                Excursion Start Date/Time:
            </td>
            <td>  
                @if($form->startDate && $form->startTime) {{ $form->startDate->format('j F') }} {{ $form->startTime->format('g:ia') }} @endif                               
            </td>
        </tr>
        <tr>
            <td>
                Excursion Finish Date/Time:
            </td>
            <td > 
            @if($form->finishDate && $form->finishTime) {{ $form->finishDate->format('j F') }} {{ $form->finishTime->format('g:ia') }} @endif                                                        
            </td>
        </tr>
        <tr>
            <td valign='top'>
                Aim:
            </td>
            <td> 
                {{ $form->aim }}                               
            </td>
        </tr>
        <tr>
            <td valign='top'>
           Location:
            </td>
            <td > 
                {{ $form->location }}                               
            </td>
        </tr>
        <tr>
            <td valign='top'>
            Activities:
            </td>
            <td >
                {{ $form->activities }}                                      
            </td>
        </tr>
    </table>
</div>
<br>                
<div class="page-break">
    <h4>2. Staff</h4>    
    <table class='padding-table-columns'>        
        <tr>
            <td valign='top'>Staff Attending:</td>
            <td>
            @foreach($staffs as $staff)
                {{$staff->firstname.' '.$staff->surname}}<br>
            @endforeach
            </td>            
        </tr>       
    </table>
    @if($reliefs)
    <br>
    Relief Required:
    <table cellspacing="0" class='padding-table-columns'> 
        <tr>
            <th style='border-bottom:1px solid black'>Teacher</th>
            <th style='border-bottom:1px solid black'>Date</th>
            <th style='border-bottom:1px solid black'>Period</th>
            <th style='border-bottom:1px solid black'>Class</th>
            <th style='border-bottom:1px solid black'>Note</th>
        </tr>
        @foreach($reliefs as $relief)
        <tr>
            <td style='border-bottom:1px solid black'>{{ $relief->name }}</td>
            <td style='border-bottom:1px solid black'>{{ \Carbon\Carbon::parse($relief->date )->format('D j M Y')}}</td>
            <td style='border-bottom:1px solid black'>{{ $relief->period }}</td>
            <td style='border-bottom:1px solid black'>{{ $relief->class }}</td>
            <td style='border-bottom:1px solid black'>{{ $relief->note }}</td>
        </tr>
        @endforeach
    </table>
    @endif                    
</div>
<br>
<div class="page-break">
    <h4>3. Expenses</h4>
    <table class='padding-table-columns'>
        <tr>
            <td > 
                Does the excursion have expenses:
            </td>
            <td >  
            @if($expense->expenses) Yes @else No @endif                         
            </td>
        </tr>
        @if($expense->expenses)
        <tr>
            <td >
                Excursion Fee to be Invoiced:
            </td>
            <td >  
                @if($expense->invoiced) Yes @else No @endif                               
            </td>
        </tr>
        <tr>
            <td >
                Payment Required Before Start:
            </td>
            <td >  
                @if($expense->before) Yes @else No @endif
            </td>
        </tr>
        @endif
    </table>
    <br>
    Expense Items:
    <table cellspacing="0" class='padding-table-columns'>
        <tr>
            <th style='border-bottom:1px solid black'>Expense Name</th>
            <th style='border-bottom:1px solid black'>Cheque Text</th>
            <th style='border-bottom:1px solid black'>Amount</th>
            <th style='border-bottom:1px solid black'>Payment Required</th>
        </tr>
        @foreach ($items as $item)        
        <tr>
            <td style='border-bottom:1px solid black'>{{ $item->expense }}</td>
            <td style='border-bottom:1px solid black'>{{ $item->cheque }}</td>
            <td style='border-bottom:1px solid black'>${{ $item->amount }}</td>
            <td style='border-bottom:1px solid black'>{{ \Carbon\Carbon::parse($item->required)->format('D j M Y') }}</td>
        </tr>
        @endforeach
    </table>
</div>
<br>
<div>
   <h4>4. Logistics</h4> 
    <table class='padding-table-columns'>
        <tr>
            <td>College Bus Required:</td>
            <td>@if($logistics->bus) Yes @else No @endif</td>
        </tr>
        <tr>
            <td>College Car Required:</td>
            <td>@if($logistics->car) Yes @else No @endif</td>
        </tr>
        <tr>
            <td>Transport Plan:</td>
            <td>{{ $logistics->transport }}</td>
        </tr>
        <tr>
            <td>Excursion Phone Required:</td>
            <td>@if($logistics->phone) Yes @else No @endif</td>
        </tr>
        <tr>
            <td>Epirb Required:</td>
            <td>@if($logistics->epirb) Yes @else No @endif</td>
        </tr>
    </table>
</div>
<br>
<div class="page-break">
    <h4>5. Student List</h4>
    <table cellspacing="0" class='padding-table-columns'>
        <tr>
            <th style='border-bottom:1px solid black'>First Name</th>
            <th style='border-bottom:1px solid black'>Surname</th>
            <th style='border-bottom:1px solid black'>Year</th>
            <th style='border-bottom:1px solid black'>Roll</th>
            <th style='border-bottom:1px solid black'>Medical Alert</th>
        </tr>
    @foreach($students as $student)
        <tr>
           <td style='border-bottom:1px solid black'>{{ $student->firstname }}</td>
           <td style='border-bottom:1px solid black'>{{ $student->surname }}</td>
           <td style='border-bottom:1px solid black'>{{ $student->year }}</td>
           <td style='border-bottom:1px solid black'>{{ $student->code }}</td>
           <td style='border-bottom:1px solid black'>@if($student->alert_medical) Yes @endif</td>
        </tr>
    @endforeach
    </table>
</div> 
<br>       
<div>
    <h4>6. Risk Management</h4>
    <table class='padding-table-columns'>   
        <tr>
           <td>Staff Attending with First Aid</td>
           <td>@if($riskman->firstaid) Yes @else No @endif</td>
        </tr>
        @if($riskman->firstaid)
        <?php $staffFA = json_decode($riskman->firstaidStaff); ?>
        <tr>
            <td valign='top'>Staff with First Aid:</td>
            <td>
        @foreach($staffFA as $staff)
                {{$staff}}<br>    
        @endforeach
            </td>
        </tr>
        @endif
        <tr>
           <td>Does the Excursion Contain Water Activities Managed by the College</td>
           <td>@if($riskman->bronze) Yes @else No @endif</td>           
        </tr> 
        @if($riskman->bronze)
        <?php $staffBronze = json_decode($riskman->bronzeStaff); ?>
        <tr>
            <td valign='top'>Staff with Bronze:</td>
            <td>
        @foreach($staffBronze as $staff)
                {{$staff}}<br>    
        @endforeach
            </td>
        </tr>
        @endif   
    </table>
</div>
<br>
<div class="page-break">
<h4>7. Risk Assessment</h4>
<table cellspacing="0" class='padding-table-columns'>
    <tr>
        <th style='border-bottom:1px solid black'>Activity</th>
        <th style='border-bottom:1px solid black'>Hazard</th>
        <th style='border-bottom:1px solid black'>Risk</th>
        <th style='border-bottom:1px solid black'>Control</th>
    </tr>
@foreach($risks as $risk)
    <tr>
        <td style='border-bottom:1px solid black'>{{ $risk->activity }}</td>
        <td style='border-bottom:1px solid black'>{{ $risk->hazard }}</td>
        <td style='border-bottom:1px solid black'>{{ $risk->name }}</td>
        <td style='border-bottom:1px solid black'>{{ $risk->control }}</td>
    </tr>
@endforeach
</table>
</div> 
<br>
<div class="page-break">
<h4>8. Student Care Plans</h4>
<table cellspacing="0" class='padding-table-columns'>
    <tr>
        <th style='border-bottom:1px solid black'>Student</th>
        <th style='border-bottom:1px solid black'>Concern</th>
        <th style='border-bottom:1px solid black'>Risk</th>
        <th style='border-bottom:1px solid black'>Control</th>
        <th style='border-bottom:1px solid black'>Care Plan</th>
    </tr>
@foreach($cares as $care)
    <tr>
        <td style='border-bottom:1px solid black'>{{ $care->student }}</td>
        <td style='border-bottom:1px solid black'>{{ $care->concern }}</td>
        <td style='border-bottom:1px solid black'>{{ $care->name }}</td>
        <td style='border-bottom:1px solid black'>{{ $care->control }}</td>
        <td style='border-bottom:1px solid black'>@if($care->form_excur_file_id) Yes @endif</td>
    </tr>
@endforeach
</table>
</div> 
<br>
<div>
<h4>9. Emergency Response Plan</h4>
<table class='padding-table-columns'>
    <tr>
        <td>Plan:</td>
        <td>{{ $erp->erpText }}</td>
    </tr>   
</table>
</div>
<br><br><br>
<div class="page-break printOnly">
<table style='border-spacing: 10px;border:1px solid black'>
        <tr>
            <td style='padding-top:5px'>Signature:______________________________</td>
            <td style='padding-top:5px'>Signature:______________________________ </td>
        </tr>
        <tr>
            <td>Date:________________</td>
            <td>Date:________________</td>
        </tr>
        <tr>
            <td><b>Head of School</b></td>
            <td><b>Principal</b></td>
        </tr>
    </table>
</div> 
