
<!doctype html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">		
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="/css/bootstrap.min.css">	
		<link rel="stylesheet" href="/css/all.css">	
        <link href="https://emoji-css.afeld.me/emoji.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Cardo:400,700|Oswald" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">

		<title>Wisdom - Student Home</title>
		<style>
		body {
			background-color:#003366;           
            font-family: 'Lato', sans-serif;
            font-size:13px;            
		}

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Oswald', sans-serif;            
        }
        a {
            color: blue;
        }       
        i.course:hover {
            color:orange;
            -webkit-transform: scale(1.3);
        }
        i.assess:hover {
            color:green;
            -webkit-transform: scale(1.3);
        }
        i.timetable:hover {          
            -webkit-transform: scale(1.3);
        }
        .feelings:hover {           
            -webkit-transform: scale(1.3);
        }
        .item {
			position:relative;
			padding-top:20px;
			display:inline-block;
		}
        .item:hover{
            -webkit-transform: scale(1.1); 
        }
        i.thumbhov:hover{
            -webkit-transform: scale(1.2); 
        }
        .notify-badge{
            position: absolute;
            right:26px;
            top:11px;
            background:red;
            text-align: center;
            border-radius: 30px 30px 30px 30px;
            color:white;
            padding:1px 5px;
            font-size:10px;
            border: 2px solid white;
        }
        .timetable-btn-active{
            font-size:18px;
            border-bottom: 1px solid #003366;            
        }
        .timetable-btn{
            color: gray;
            font-size: 18px;
            cursor:pointer;            
        }
        .timetable-btn:hover{
            color: #003366;                                    
        }
        .top-link{border:none;display:inline-block;padding:12px 16px;vertical-align:middle;overflow:hidden;text-decoration:none;color:inherit;text-align:center;cursor:pointer;white-space:nowrap}							
        .top-link:hover{box-shadow:0 8px 16px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19)}
		</style>
        <script src="/js/jquery-3.4.1.min.js"></script>
        <script src="/js/popper.min.js"></script>
        <script src="/js/bootstrap.min.js"></script>
	</head>
    <body>
        <nav class="navbar navbar-expand-sm navbar-dark" style="background-color:#003366;" >
	        <a class="navbar-brand" href="/student"><img src="images/SJCLogoNew.png" style="height:40px;padding-right:10px">Wisdom</a>
            
            <span class="ml-auto" style='color:white;'>
                @include ('profile')
            </span>
        </nav>
    @yield ('welcometext')
    @yield ('links')
        <br>
        <div style='background-color:gainsboro;'>
            <div class='container'>
                <br>                 
                @yield ('body')
            </div>
        </div>
        <script>
            function updateTimetable(item,date){
        
                $.get("/student/timetable/"+date,function(data){
            $('#timetable').fadeOut('fast', function(){
                $('#timetable').html(data);
                //Change Classes
                if ($(item).attr('id') == 'tt-tomorrow'){
                    $('#tt-tomorrow').addClass('timetable-btn-active');
                    $('#tt-tomorrow').removeClass('timetable-btn');
                    $('#tt-today').addClass('timetable-btn');
                    $('#tt-today').removeClass('timetable-btn-active');
                } else {
                    $('#tt-today').addClass('timetable-btn-active');
                    $('#tt-today').removeClass('timetable-btn');
                    $('#tt-tomorrow').addClass('timetable-btn');
                    $('#tt-tomorrow').removeClass('timetable-btn-active');
                }
            });
            $('#timetable').fadeIn('fast');
        },"html");  

            }
        </script>
    </body>
</html>