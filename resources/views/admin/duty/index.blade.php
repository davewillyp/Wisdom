@extends ('staff.layout')
@section ('body')
<div class='row justify-content-between'>
    <div class='col-auto'>
        <div style='margin-bottom:10px'>
            <span style='font-size:20px;color:#003366;'>Create Duty Location</span>
        </div>     
        <div class='container pb-1' style='background:whitesmoke;color:#003366;font-size:16px;border-left: 3px solid #003366'>
            <form method='post' action='/admin/duty' id='createLocation'>
                @csrf
                <div class="form-row">
                    <div class="col-auto my-1">
                        <label class="mr-sm-2" for="locationName">Location Name</label>
                        <input id='locationName' name='name' type="text" class="form-control form-control-sm @error('name') is-invalid @enderror" value="{{ old('name') }}">                           
                    </div>                    
                    <div class="col-auto mt-auto">                        
                        <i class="fal fa-plus-circle addplus" style='font-size:20px;margin-bottom:12px' onclick='submitLocation()'></i></button>
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
            <span style='font-size:20px;color:#003366;'>Duty Locations</span>
        </div>
            <form id='deleteLocation' method='post' action='/admin/terms'>
            @csrf
            @method('delete')
            <input type='hidden' id='locationid' name='id' value=''>            
            </form>
            
            @foreach ($locations as $location)
            <div class='row'>
                <div class='col-5' >   
                    <div class='container my-auto' style='background:whitesmoke;color:#003366;
                    border-left: 3px solid #003366;padding-top:5px;padding-bottom:5px;border-bottom:1px solid gainsboro;'>
                        <div class='row'>
                            <div class='col-10'>
                                <span style='font-weight:bold;font-size:17px'>{{ $location->name}}</span><br>                             
                            </div>
                            <div class='col my-auto'>
                            <i class="fal fa-trash-alt deletebin" style='font-size:18px' onclick='deleteLocation({{ $location->id }})'></i>
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
    function submitLocation(){
        $('#createLocation').submit();
    }
    function deleteLocation(id){
        $('#locationid').val(id);
        $('#deleteLocation').submit();
    }
</script>
@endsection