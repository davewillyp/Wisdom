<div class='row mb-3'>
    <div class="col-5">
        <div class='row'>
            <div class='col-auto'>
                <div class='mb-3' style='font-size:20px;color:#003366;font-weight:bold'>Print Balance</div>       
            </div>    
        </div>
        <div class='row'>         
            <div class='col-auto'>
                <div class='shadow-sm rounded' style="background-color:whitesmoke;">
                    <div class='container' style='color:#003366'>                        
                        <div class='row p-2'>
                            <div class='col-auto'>
                                <img src="/images/papercut.png" height='35px' alt="">
                            </div>
                            <div class="col my-auto">
                                <span style='font-size:18px;font-weight:bold; @if($balance < 1) color:red @endif'>${{ $balance }}</span>
                            </div>                                                       
                        </div>                        
                    </div>
                </div>
            </div>      
        </div>
    </div>
    <div class="col-7">
    <div class='row'>
            <div class='col-auto'>
                <div class='mb-3' style='font-size:20px;color:#003366;font-weight:bold'>House Points</div>       
            </div>    
        </div>
        <div class='row'>         
            <div class='col'>
                <div class='shadow-sm rounded' style="background-color:whitesmoke;">
                    <div class='container' style='color:#003366'>                        
                        <div class='row p-2'>
                            <div class='col-auto'>
                                <img src="/images/house/{{ $housepoint['house'] }}.png" height='35px' alt="">
                            </div>
                            <div class="col my-auto">
                                <span style='font-size:18px;font-weight:bold;'>{{ $housepoint['pos'] }} - {{ $housepoint['points'] }} points</span>
                            </div>                                                       
                        </div>                        
                    </div>
                </div>
            </div>      
        </div>
    </div>
</div>
@if($homework)
<div class="mb-3">
    <div class='row'>
        <div class='col-auto'>
            <div class='mb-3' style='font-size:20px;color:#003366;font-weight:bold'>Homework this Week</div>       
        </div>    
    </div>
    @foreach($homework as $work)
    <div class='row mb-2'>         
        <div class='col'>
            <div class='shadow-sm rounded' style="background-color:whitesmoke;">
                <div class='container p-2' style='color:#003366'>                        
                    <div class='row pb-1'>
                        <div class='col-auto' style='font-weight:bold'>
                            {{ $work['title'] }}
                        </div>                                                                  
                    </div>
                    @foreach($work['homework'] as $hwItem)
                    <div class='row'>
                        <div class='col'>
                            {!! $hwItem->homework !!}
                        </div>                                                                     
                    </div>
                    @endforeach                        
                </div>
            </div>
        </div>      
    </div>
    @endforeach
</div>
@endif
@if($assessments)
<div class='row'>
    <div class='col-auto'>
        <div class='mb-3' style='font-size:20px;color:#003366;font-weight:bold'>Assessments</div>       
    </div>    
</div>
@endif
@foreach($assessments as $assess)
<div class='row mb-2'>         
    <div class='col'>
        <div class='shadow-sm rounded' style="background-color:whitesmoke;">
            <div class='container p-2' style='color:#003366'>                        
                <div class='row pb-1'>
                    <div class='col-auto' style='font-weight:bold'>
                        {{ $assess['title'] }}
                    </div>                                                                  
                </div>
                @foreach($assess['assess'] as $assessItem)
                <div class='row'>
                    <div class='col-8'>
                        {{ $assessItem->title }}
                    </div>
                    <div class='col'>
                    <?php $today = \Carbon\Carbon::parse(date('Y-m-d'));
                            $due = \Carbon\Carbon::parse($assessItem->due);
                            $length = $today->diffInDays($due);?>
                        Due:<span @if($length <= 5) style='color:red' @endif> {{ \Carbon\Carbon::parse($assessItem->due)->format('l j M') }}</span>
                    </div>                                                                                 
                </div>
                @endforeach                        
            </div>
        </div>
    </div>      
</div>
@endforeach
