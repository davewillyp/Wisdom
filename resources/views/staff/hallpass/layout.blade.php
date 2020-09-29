<!doctype html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">		
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="/css/bootstrap.min.css">	
        <link rel="stylesheet" href="/css/all.css">	
        <link rel="stylesheet" href="/css/wisdom.css">	        
        <link href="https://fonts.googleapis.com/css?family=Cardo:400,700|Oswald" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">

		<title>Wisdom - Hall Pass</title>
		<style>
		body {
			background-color:#003366;                       
            font-size:13px;                        
        }                
        a {
            color: blue;
        }
        </style>
        <script src="/js/jquery-3.4.1.min.js"></script>
        <script src="/js/popper.min.js"></script>
        <script src="/js/bootstrap.min.js"></script>
	</head>
    <body>
        <div class="container text-center p-4">
        <h3 style='color:whitesmoke'><i class="fal fa-ticket-alt fa-fw mr-2" style='color:red'></i>Hall Pass</h3>
        </div>
        <form action="" id="hallStart">
        @CSRF        
        <input type="hidden" name="id" id="studentId">
        <input type="hidden" name="action" id="studentActionId">        
        </form>
        <form action="" id="hallFinish">
        @CSRF        
        <input type="hidden" name="id" id="passId">
        <input type="hidden" name="_method" value="PUT">        
        </form>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-6">
                    <div class='rounded shadow-sm p-4 text-center' style='background:whitesmoke;'>
                    @yield('students')
                    </div>                    
                </div>
            </div>
        </div> 
        <script>
        function selectStudent(id,student){

            $('#studentId').val(id);
            $('#studentList').hide("fast");
            $('#studentText').html($(student).html());
            document.title = $(student).html();
            $('#studentText').show();
            $('#studentAction').fadeIn("fast")
            

        }

        function selectAction(action, name){
            $('#studentActionId').val(action);
            $('#studentAction').hide("fast")
            $('#actionText').html($(name).html());
            $('#actionText').show();

            startCount();
                       
        }

        function startCount(){
            $('#studentCounter').fadeIn("fast");
            form = $('#hallStart').serialize();
            $.post('/staff/hallpass', form, function(data){
                $('#passId').val(data);
                set_timer(); 
            });           
        }

        function endCount(){            
            form = $('#hallFinish').serialize();
            $.post('/staff/hallpass', form, function(data){                
                stop_timer();
                $('#finishButton').hide('fast');
                $('#finishMessage').fadeIn("fast");
                setTimeout(function(){window.close()},2000)               
            });
        }

        function setTime(minutesLabel, secondsLabel) {
                totalSeconds++;
                secondsLabel.innerHTML = pad(totalSeconds%60);
                minutesLabel.innerHTML = pad(parseInt(totalSeconds/60));
                }

        function set_timer() {
            minutesLabel = document.getElementById("minutes");
            secondsLabel = document.getElementById("seconds");
            my_int = setInterval(function() { setTime(minutesLabel, secondsLabel)}, 1000);
        }

        function stop_timer() {
            clearInterval(my_int);
        }

        function pad(val) {
        valString = val + "";
        if(valString.length < 2) {
            return "0" + valString;
            } else {
            return valString;
            }
        }

        totalSeconds = 0;

        </script>   
    </body>
</html>