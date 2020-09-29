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
<h1>Professional Development Form</h1>
    <h3>{{ $form->pdname }} - {{ $form->firstname.' '.$form->surname }}</h3>
    <br>
<div>
    <h4>1. Course Details</h4>
    <table class='padding-table-columns'>
        <tr>
            <td> 
                Course Name:
            </td>
            <td>  
                {{ $form->pdname }}                              
            </td>
        </tr>
        <tr>
            <td>
                Course Venue:
            </td>
            <td >  
                {{ $form->venue }}                              
            </td>
        </tr>
        <tr>
            <td>
                Course Start Date/Time:
            </td>
            <td>  
                @if($form->startDate && $form->startTime) {{ $form->startDate->format('j F') }} {{ $form->startTime->format('g:ia') }} @endif                               
            </td>
        </tr>
        <tr>
            <td >
                Course Finish Date/Time:
            </td>
            <td> 
            @if($form->finishDate && $form->finishTime) {{ $form->finishDate->format('j F') }} {{ $form->finishTime->format('g:ia') }} @endif                                                        
            </td>
        </tr>
        <tr>
            <td>
                Hours in attendance:
            </td>
            <td > 
                {{ $form->hours }}                               
            </td>
        </tr>
        <tr>
            <td valign='top'>
            Desirable Outcome of Professional Development:
            </td>
            <td> 
                {{ $form->outcome }}                               
            </td>
        </tr>
        <tr>
            <td valign='top'>
            College Priorities:
            </td>
            <td>
            @if($priorities)
            @foreach($priorities as $priority)
                {{ $priority }} <br>
            @endforeach     
            @endif                                                           
            </td>
        </tr>
    </table>
</div>
<br>                
<div>
    <h4>2. Relief</h4>
    <table class='padding-table-columns'>
        <tr>
            <td> 
                Relief Required:
            </td>
            <td>  
                @if($form->relief) Yes @else No @endif
            </td>
        </tr>
    </table>
    @if($form->relief && $reliefs)
    <br>
    <table cellspacing="0" class='padding-table-columns'> 
        <tr>
            <th style='border-bottom:1px solid black'>Date</th>
            <th style='border-bottom:1px solid black'>Period</th>
            <th style='border-bottom:1px solid black'>Class</th>
            <th style='border-bottom:1px solid black'>Note</th>
        </tr>
        @foreach($reliefs as $relief)
        <tr>
            <td style='border-bottom:1px solid black'>{{ $relief->date->format('D j F') }}</td>
            <td style='border-bottom:1px solid black'>{{ $relief->period }}</td>
            <td style='border-bottom:1px solid black'>{{ $relief->class }}</td>
            <td style='border-bottom:1px solid black'>{{ $relief->note }}</td>
        </tr>
        @endforeach
    </table>
    @endif                    
</div>
<br>            
@if($expenses)
<div>
    <h4>3. Expenses</h4>
    <table class='padding-table-columns'>
        <tr>
            <td > 
                Course Fee:
            </td>
            <td >  
                @if($expenses->fee) Yes @else No @endif
            </td>
        </tr>
        @if($expenses->fee)
        <tr>
            <td > 
                Fee Amount:
            </td>
            <td >  
                ${{ $expenses->amount }}
            </td>
        </tr>
        <tr>
            <td > 
                Course Fee Invoiced:
            </td>
            <td >  
                @if($expenses->invoiced) Yes @else No @endif
            </td>
        </tr>   
        <tr>
            <td > 
                Payment Required Before Course:
            </td>
            <td >  
                @if($expenses->before) Yes @else No @endif
            </td>
        </tr>     
        @endif
        <tr>
            <td > 
                Expenses to Claim:
            </td>
            <td >  
                {{ $expenses->claim }}
            </td>
        </tr>   
    </table>                         
</div>     
<br>
@endif
@if($logistics)
<div>
    <h4>4. Logistics</h4>
    <table class='padding-table-columns'>
        <tr>
            <td > 
                College Car Required:
            </td>
            <td >  
                @if($logistics->car) Yes @else No @endif
            </td>
        </tr>
        @if($logistics->car)
        <tr>
            <td > 
                Car Pickup Date/Time:
            </td>
            <td >  
            {{ $logistics->pickupDate->format('j F') }} {{ $logistics->pickupTime->format('g:ia') }}
            </td>
        </tr>
        <tr>
            <td > 
                Car Dropoff Date/Time:
            </td>
            <td >  
            {{ $logistics->dropoffDate->format('j F') }} {{ $logistics->dropoffTime->format('g:ia') }}
            </td>
        </tr>
        @endif   
        <tr>
            <td > 
                Accommodation Required:
            </td>
            <td >  
                @if($logistics->accommodation) Yes @else No @endif
            </td>
        </tr>     
        @if($logistics->accommodation)
        <tr>
            <td > 
                Arrival Date:
            </td>
            <td >  
            {{ $logistics->arrival->format('j F') }}
            </td>
        </tr>
        <tr>
            <td > 
                Departure Date:
            </td>
            <td >  
            {{ $logistics->departure->format('j F') }}
            </td>
        </tr>
        @endif   
    </table>                         
</div>                          
@endif
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