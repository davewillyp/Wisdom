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

		<title>Wisdom - Student Home</title>
		<style>
		body {
            background-color:gainsboro;			
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
        <nav class="navbar navbar-expand-sm navbar-dark" style="background-color: #003366" >
            <div class="container-fluid">
            <span class="navbar-brand" style='z-index:999;color:white;margin-left:-10px;cursor:pointer'><img src="https://wisdom.sjc.wa.edu.au/images/SJCLogoNew.png" style="height: 40px; padding-right: 10px;">Wisdom</span>                
                <span class="ml-auto">
                    @include('profile')
                </span>                                             
            </div>
        </nav>
        <div class="wisbackground pb-1">
        @yield('welcometext')
        @yield('links')
        </div>          
        <div style='background-color:gainsboro;' class="pt-2">
            <div class='container'>                             
                @yield('body')
            </div>
        </div>        
    </body>
</html>