<form id='newEvent'>
    @CSRF
    <input type='hidden' name='term' value='{{ $term->id }}'>    
    <div class='row justify-content-center'>
        <div class='col-9 mb-4'>
            <div class='row justify-content-center'>
                <div class='col text-center mb-3'>
                    <span style='font-size:18px;font-weight:bold'>Create Event</span>
                </div>
            </div>    
            <div class="form-row">
                <div class="col">
                    <label for="eventDate">Event Date</label>
                    <input type="date" class="form-control" value='{{ $date }}' name='date' id='eventDate'>
                </div>        
                <div class="col">
                    <label for="eventType">Event Category</label>
                    <select name='type' class='form-control' id='eventType'>
                        @foreach($cats as $cat)
                            <option value='{{ $cat->id }}'>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>  
            </div>
            <div class="form-row mt-2">
                <div class="col">
                    <label for="eventName">Event Name</label>
                    <input type="text" class="form-control" name='name' id='eventName'>
                </div>        
            </div>
            <div class="form-row mt-2">
                <div class="col">
                    <label for="eventStart">Event Start Time</label>
                    <input type="time" class="form-control" name='start' id='eventStart'>
                </div>    
                <div class="col">
                    <label for="eventEnd">Event Finish Time</label>
                    <input type="time" class="form-control" name='end' id='eventEnd'>            
                </div>        
            </div>
            <div class="form-row mt-2">
                <div class="col">
                    <label for="eventLocation">Location</label>
                    <input type="text" class="form-control" name='location' id='eventLocation'>
                </div>        
            </div>
            <div class="form-row mt-3 justify-content-end">
                <div class="col-auto">
                    <div class='p-2 wisbutton' style='font-size:16px' onclick='createCalEvent()'>Create Event</div>
                </div>        
            </div>

        </div>
    </div>
</form>