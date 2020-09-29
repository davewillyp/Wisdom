@extends ('staff.layout')

@section ('welcometext')
<div style='color:whitesmoke;'>
    <div class='container'>
        <div class='row justify-content-center justify-content-md-between'>
            <div class='col-sm-auto'>
                <p class='text-center' style='font-size:22px;'><b>{{ $greeting }} {{ session('givenName') }}</b></p>
            </div>
            <div class='col-sm-auto'>
                <p class='text-center' style='font-size:22px'><b><?=date('l j F')?></b></p>
            </div>
        </div>
    </div>
</div>
@endsection

@section ('links')
<div class='container mb-3'>
    <div class="row justify-content-center" style='background-color:#003366;color:white'>
    @foreach ($links as $link)
        <div class="col-sm-2">
            <div class="card top-link text-center item" style='width:110px;background-color:#003366' onclick="window.open('{{ $link->url }}')">                           
                @if ($mailNote > 0 & $link->name == 'Email')
                <span class="notify-badge"><strong>{{ $mailNote }}</strong></span>
                @endif
                @if ($seqtaNote > 0 & $link->name == 'SEQTA')
                <span class="notify-badge"><strong>{{ $seqtaNote }}</strong></span>
                @endif
                {!! $link->icon !!}
                <p class="card-text">{{ $link->name }}</p>
            </div>
        </div>
    @endforeach
        <div class="col-sm-2">
            <div class="card top-link text-center item" style='width:110px;background-color:#003366' onclick="openMore()">                           
                <i class="fal fa-arrow-square-down" style='color:whitesmoke;font-size:45px;padding-bottom:5px'></i>
                <p class="card-text">More..</p>
            </div>
        </div>
    </div>
    <div class="row justify-content-center mt-3" style='background-color:#003366;color:white;display:none' id='sublinks'>
    @foreach ($subLinks as $subLink)
        <div class="col-sm-2">
            <div class="card top-link text-center item" style='width:110px;background-color:#003366' onclick="window.open('{{ $subLink->url }}')">                                          
                {!! $subLink->icon !!}
                <p class="card-text">{{ $subLink->name }}</p>
            </div>
        </div>
    @endforeach        
    </div>
</div>
@endsection

@section ('body') 
<div class="container">
    <div class='row justify-content-between'>
        <div class='col-6' style='' id='timetable'>
            {!! $timetable !!}    
        </div>     
        <div class='col-6' style='border-left:1px solid silver'>            
            <div id="calendar">
                {!! $events !!}
            </div>
            <div>
                {!! $notices !!}
            </div>
        </div>    
    </div>
</div>
<div class="modal fade" id="quicklinkModal" tabindex="-1" role="dialog" aria-labelledby="quicklinkModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="quicklinkModalLabel">Add QuickLink</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <form id='quicklinkForm'>
             @CSRF
            <input type="hidden" name="metaclass" id="metaclass">
            <input type="hidden" name="year" id="year">
            <div class="form-group mb-2">
                <label for="quicklinkName">Name:</label>
                <input type="text" class="form-control" id="quicklinkName" placeholder="Name of the link" name="name">
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-group mb-2">
                <label for="quicklinkURL">URL:</label>
                <input type="text" class="form-control" id="quicklinkURL" placeholder="Website Address" name="url">
                <div class="invalid-feedback"></div>
            </div>
            <button type="button" class="btn btn-outline-primary mt-2 float-right" onclick="addLink()">Add</button>
        </form>            
      </div>      
    </div>
  </div>
</div>
<script>   
    function getPapercut()
    {                    
        $.get("/staff/papercut",{},function(data){                
            $('#sidebar').html(data);                                       
        },"html");
    }

    function getNotices()
    {               
        $.get("/staff/notices",{},function(data){                
            $('#sidebar').html(data);                                        
        },"html");
    }

    function getTimetable(date)
    {            
        $.get("/staff/timetable/"+date,{},function(data){                
            $('#timetable').html(data);                                     
        },"html");
    }

    function getEvents(date)
    {               
        $.get("/staff/events/"+date,{},function(data){                
            $('#calendar').html(data);                                        
        },"html");
    }

    function openMore()
    {
        if($("#sublinks").is(":visible")){
            $('#sublinks').hide('fast');
        } else {
            $('#sublinks').show('slow');
        }
    }

    function openAddLink(meta,year)
    {
        $('#quicklinkModal').modal('toggle');
        $('#metaclass').val(meta);
        $('#year').val(year);
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').html("");
        $('#quicklinkName').val("");
        $('#quicklinkURL').val("");
    }

    function addLink()
    {
        thisDate = $('#timetableDate').val()
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').html("");
        form = $('#quicklinkForm').serialize();
        $.ajax({
            type: "POST",
            url: "/staff/quicklink",
            data: form,
            success: function(data) {                
                $('#quicklinkModal').modal('toggle');
                getTimetable(thisDate);               
            },
            beforeSend: function(request) {
                request.setRequestHeader("X-Requested-With", "XMLHttpRequest");
            },
            error: function(data) {
                var errormsg = data.responseJSON;                
                $.each(errormsg.errors, function( key, value ) {
                    row = parseInt(key.substr(key.indexOf('.')+1)) + 1;
                    name = key.substr(0, key.indexOf('.'));                    
                    if ($('input[name*="' + key +'"]').length){
                    $('input[name*="' + key +'"]').addClass('is-invalid');
                    $('input[name*="' + key +'"]').next('.invalid-feedback').html(value);
                    }                   
                });
            }
    });       
    }

    function deleteLink(button,id)
    {
        token = $('input[name="_token"]').val()
        method = "delete"
        $.post('/staff/quicklink', {_token: token, _method: method, id: id}, function(data){
            $(button).parent().fadeOut(function(){
                $(button).parent().remove();
            })
        });
    }
</script>
@endsection


