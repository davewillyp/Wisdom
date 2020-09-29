<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $form->pdname }} </title>
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
        table{
            width:100%;                            
        }
        th{
            text-align: left;
        }
        .row{
            border-bottom: 1px solid black;
        }      
    </style>
</head>
<body>
    
    <h1>PD Application Form</h1>
    <h3>{{ $form->pdname }} - {{ $form->firstname.' '.$form->surname }}</h3>
    <div class='container mt-3'>
        <h4>1. Course Details</h4>       
        <table>            
            <tr>
                <td style='width:350px;'>
                    Course Venue:
                </td>
                <td class='wis-3-text'>  
                    {{ $form->venue }}                              
                </td>
            </tr>
            <tr>
                <td class='wis-h3-text'>
                    Course Start Date/Time:
                </td>
                <td class='wis-3-text'>  
                    @if($form->startDate && $form->startTime) {{ $form->startDate->format('j F') }} {{ $form->startTime->format('g:ia') }} @endif                               
                </td>
            </tr>
            <tr>
                <td class='wis-h3-text'>
                    Course Finish Date/Time:
                </td>
                <td class='wis-3-text'> 
                @if($form->finishDate && $form->finishTime) {{ $form->finishDate->format('j F') }} {{ $form->finishTime->format('g:ia') }} @endif                                                        
                </td>
            </tr>
            <tr>
                <td class='wis-h3-text'>
                    Hours in attendance:
                </td>
                <td class='wis-3-text'> 
                    {{ $form->hours }}                               
                </td>
            </tr>
            <tr>
                <td class='wis-h3-text'>
                Desirable Outcome of Professional Development:
                </td>
                <td class='wis-3-text'> 
                    {{ $form->outcome }}                               
                </td>
            </tr>
            <tr>
                <td class='wis-h3-text' valign='top'>
                College Priorities:
                </td>
                <td class='wis-3-text'>
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
    <div class='container'>
        <h4>2. Relief</h4>
        <table>
            <tr>
                <td style='width:350px;'> 
                    Relief Required:
                </td>
                <td class='wis-3-text'>  
                    @if($form->relief) Yes @else No @endif
                </td>
            </tr>
        </table>
        @if($form->relief && $reliefs)
        <br>
        <table cellspacing="0"> 
            <tr>
                <th>Date</th>
                <th>Period</th>
                <th>Class</th>
                <th>Note</th>
            </tr>
            @foreach($reliefs as $relief)
            <tr>
                <td style='border-bottom:1px solid black'>{{ $relief->date->format('D j M') }}</td>
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
    <div class='container'>
        <h4>3. Expenses</h4>
        <table>
            <tr>
                <td style='width:350px;'> 
                    Course Fee:
                </td>
                <td class='wis-3-text'>  
                    @if($expenses->fee) Yes @else No @endif
                </td>
            </tr>
            @if($expenses->fee)
            <tr>
                <td class='wis-h3-text'> 
                    Fee Amount:
                </td>
                <td class='wis-3-text'>  
                    ${{ $expenses->amount }}
                </td>
            </tr>
            <tr>
                <td class='wis-h3-text'> 
                    Course Fee Invoiced:
                </td>
                <td class='wis-3-text'>  
                    @if($expenses->invoiced) Yes @else No @endif
                </td>
            </tr>   
            <tr>
                <td class='wis-h3-text'> 
                    Payment Required Before Course:
                </td>
                <td class='wis-3-text'>  
                    @if($expenses->before) Yes @else No @endif
                </td>
            </tr>     
            @endif
            <tr>
                <td class='wis-h3-text'> 
                    Expenses to Claim:
                </td>
                <td class='wis-3-text'>  
                    {{ $expenses->claim }}
                </td>
            </tr>   
        </table>                         
    </div>     
    <br>
    @endif
    @if($logistics)
    <div class='container mb-3'>
        <h4 style='margin-bottom:5px'>4. Logistics</h4>
        <table>
            <tr>
                <td style='width:350px;'> 
                    College Car Required:
                </td>
                <td class='wis-3-text'>  
                    @if($logistics->car) Yes @else No @endif
                </td>
            </tr>
            @if($logistics->car)
            <tr>
                <td class='wis-h3-text'> 
                    Car Pickup Date/Time:
                </td>
                <td class='wis-3-text'>  
                {{ $logistics->pickupDate->format('j F') }} {{ $logistics->pickupTime->format('g:ia') }}
                </td>
            </tr>
            <tr>
                <td class='wis-h3-text'> 
                    Car Dropoff Date/Time:
                </td>
                <td class='wis-3-text'>  
                {{ $logistics->dropoffDate->format('j F') }} {{ $logistics->dropoffTime->format('g:ia') }}
                </td>
            </tr>
            @endif   
            <tr>
                <td class='wis-h3-text'> 
                    Accommodation Required:
                </td>
                <td class='wis-3-text'>  
                    @if($logistics->accommodation) Yes @else No @endif
                </td>
            </tr>     
            @if($logistics->accommodation)
            <tr>
                <td class='wis-h3-text'> 
                    Arrival Date:
                </td>
                <td class='wis-3-text'>  
                {{ $logistics->arrival->format('j F') }}
                </td>
            </tr>
            <tr>
                <td class='wis-h3-text'> 
                    Departure Date:
                </td>
                <td class='wis-3-text'>  
                {{ $logistics->departure->format('j F') }}
                </td>
            </tr>
            @endif   
        </table>                                 
    </div>                          
    @endif 
    <br><br><br>
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
</body>
</html>

