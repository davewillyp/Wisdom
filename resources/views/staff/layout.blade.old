
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

		<title>Wisdom - Staff Home</title>
		<style>
		body {
			background-color:#003366;           
            font-family: 'Lato', sans-serif;
            font-size:13px;            
		}

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Oswald', sans-serif;
            text-transform: uppercase;
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
    <div id="wait" style="display:none;position:absolute;top:25%;left:50%;padding:2px;font-size:60px;color:#003366;z-index:2;"><i class="fad fa-spinner fa-pulse"></i></div>
        <div class='container-fluid'>       
        <div class='row' style="color:white;padding: 5px 0px 5px 0px;">
            <div class='col-auto my-auto'>
                <a class="" href="/{{ session('userType') }}" style='display:inline-block;text-decoration:none;color:white;font-size:20px;'><img src="/images/SJCLogoNew.png" style="height:40px;"><span style='margin-left:10px'>Wisdom</span></a>
            </div> 
            <div class="col text-center my-auto">
                <span class="fa-stack wisduty" style='font-size:20px;' onclick="window.open('/staff/duty','_self')">
                    <i class="fal fa-circle fa-stack-2x" @if(\Route::current()->getName() == 'duty') style='color:cyan' @endif></i>
                    <i class="fal fa-user-secret fa-fw fa-stack-1x" style='color:cyan'></i>
                </span>
                <span class="fa-stack wiscalendaredit" style='font-size:20px' onclick="window.open('/staff/bookings','_self')">
                    <i class="fal fa-circle fa-stack-2x" @if(\Route::current()->getName() == 'bookings') style='color:lime' @endif></i>
                    <i class="far fa-calendar-edit fa-fw fa-stack-1x" style='color:lime'></i>
                </span>
                <span class="fa-stack wiscalendar" style='font-size:20px;'>
                    <i class="fal fa-circle fa-stack-2x" @if(\Route::current()->getName() == 'calendar') style='color:fuchsia' @endif></i>
                    <i class="far fa-calendar fa-fw fa-stack-1x" style='color:fuchsia' onclick="window.open('/staff/calendar','_self')"></i>
                </span>
                <span class="fa-stack wiscog" style='font-size:20px;'>
                    <i class="fal fa-circle fa-stack-2x"></i>
                    <i class="far fa-user-cog fa-fw fa-stack-1x" style='color:red' onclick="window.open('/admin','_self')"></i>
                </span>
                   
            </div>
            <div class='col-auto text-right my-auto'>
                <span class="ml-auto">
                    @include ('profile')
                </span>
            </div>   
        </div>              
        </div>
        <div id='welcomehead'>
        @yield ('welcometext')
        @yield ('links')
        </div>        
        <div style='background-color:gainsboro;'>
            <div class='container'>
                <br>
                <div id='body'>                 
                @yield ('body')
                </div>
            </div>
        </div>
    <script>
    $(document).ready(function(){
        $(document).ajaxStart(function(){
             $("#wait").css("display", "block");
        });
        $(document).ajaxComplete(function(){
            $("#wait").css("display", "none");
        }); 
             
    });
    function closeWelcome(){
        $("#welcomehead").slideUp("fast");
        $("#openWelcome").fadeIn("slow");
        $("#closeWelcome").fadeOut("slow");        
    }
    function openWelcome(){
        $("#welcomehead").slideDown("fast");
        $("#openWelcome").fadeOut("slow");    
        $("#closeWelcome").fadeIn("slow");
    }
    </script>
    </body>
</html>
        
           