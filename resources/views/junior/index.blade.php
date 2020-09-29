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

		<title>Wisdom - Junior Home</title>
		<style>
		body {
			background-color:gainsboro;                       
            font-size:13px;            
        }
        .cropped1 {
            width: 80px; /* width of container */
            height: 80px; /* height of container */
            object-fit: cover;
            object-position: 20% 0px;             
            border: 3px solid whitesmoke;
        } 
        .cropped1:hover{
            border: 3px solid cyan;
            cursor:pointer;
        }                     
        </style>
        <script src="/js/jquery-3.4.1.min.js"></script>
        <script src="/js/popper.min.js"></script>
        <script src="/js/bootstrap.min.js"></script>
	</head>
    <body>
        <div class="wisbackground">
            <nav class="navbar navbar-expand-sm navbar-dark" style="background-color: #003366" >
                <div class="container-fluid">
                <span class="navbar-brand" id="sidebarCollapse" style='z-index:999;color:white;margin-left:-10px;cursor:pointer'><img  src="https://wisdom.sjc.wa.edu.au/images/SJCLogoNew.png" style="height: 40px; padding-right: 10px;">Wisdom</span>                
                    <span class="ml-auto">                       
                    </span>                                             
                </div>
            </nav>
            <div style='color:whitesmoke;'>
                <div class='container'>
                    <div class='row justify-content-center justify-content-md-between'>
                        <div class='col-sm-auto'>
                            <p class='text-center' style='font-size:22px;'><b>{{ $greeting }}</b></p>
                        </div>
                        <div class='col-sm-auto'>
                            <p class='text-center' style='font-size:22px'><b><?=date('l j F')?></b></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="wisbackground pb-3">
            @include('junior._links')
        </div>
        <div class="wisbackground">
            @include('junior._teacher')
        </div>
        <div>
            <div class="container p-2" id="links">
            
            </div>
        </div>
    </body>
    <script>
    function openLinks(teacher){
        $.get('/junior/links/'+teacher,function(data){
            $('#links').html(data);
        });
    }
    </script>
</html>
