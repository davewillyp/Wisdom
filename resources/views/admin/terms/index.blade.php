@extends ('staff.layout')
@section ('body')
<div class='row justify-content-between'>
    <div class='col-auto'>
        <div style='margin-bottom:10px'>
            <span style='font-size:20px;color:#003366;'>Create Term</span>
        </div>     
        <div class='container' style='background:whitesmoke;padding:20px;color:#003366;font-size:16px;border-left: 3px solid #003366'>
            <form method='post' action='/admin/terms' id='createTerm'>
                @csrf
                <div class="form-row">
                    <div class="col-auto my-1">
                        <label class="mr-sm-2" for="inlineFormCustomSelect">Term Number</label>
                        <input name='number' type="number" class="form-control @error('number') is-invalid @enderror" maxlength="1" size="5" value="{{ old('number') }}">                           
                    </div>
                    <div class="col-auto my-1">
                        <label class="mr-sm-2" for="customControlAutosizing">Term Start</label>      
                        <input name='start' type="date" class="form-control @error('start') is-invalid @enderror" value="{{ old('start') }}">        
                    </div>
                    <div class="col-auto my-1">
                        <label class="mr-sm-2" for="customControlAutosizing">Term End</label>      
                        <input name='end' type="date" class="form-control @error('end') is-invalid @enderror" value="{{ old('end') }}">        
                    </div>
                    <div class="col-auto mt-auto">                        
                        <i class="fal fa-plus-circle addplus" style='font-size:20px;margin-bottom:12px' onclick='submitTerm()'></i></button>
                    </div>
                </div>
            </form>
        </div>        
    </div>
</div>
<br>
<div class='row justify-content-between'>
    <div class='col'>
        <div style='margin-bottom:10px'>
            <span style='font-size:20px;color:#003366;'>Terms</span>
        </div>
            <form id='deleteTerm' method='post' action='/admin/terms'>
            @csrf
            @method('delete')
            <input type='hidden' id='termid' name='id' value=''>            
            </form>
            
            @foreach ($terms as $term)
            <div class='row'>
                <div class='col-5' >   
                    <div class='container my-auto' style='background:whitesmoke;color:#003366;
                    border-left: 3px solid #003366;padding-top:5px;padding-bottom:5px;border-bottom:1px solid gainsboro;'>
                        <div class='row'>
                            <div class='col-10'>
                                <span style='font-weight:bold;font-size:17px'>{{ $term->name}}</span><br>
                                <span style='font-weight:bold;padding-right:5px'>Start:</span>{{ \Carbon\Carbon::parse($term->start)->format('j F Y')}} <br>
                                <span style='font-weight:bold;padding-right:5px'>End:</span>{{ \Carbon\Carbon::parse($term->end)->format('j F Y')}}<br>
                            </div>
                            <div class='col my-auto'>
                            <i class="fal fa-trash-alt deletebin" style='font-size:18px' onclick='deleteTerm({{ $term->id }})'></i>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
            @endforeach   
        <br><br>
    </div>     
<div>

<script>
    function submitTerm(){
        $('#createTerm').submit();
    }
    function deleteTerm(id){
        $('#termid').val(id);
        $('#deleteTerm').submit();
    }
</script>
@endsection