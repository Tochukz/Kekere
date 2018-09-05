<!DOCTYPE html>
<html>
<head>
    <title>Kekere Lite Framework</title>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"> </script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"> </script>
    <![endif]-->   
</head>
<body>
@partial('Partials.navigation')
<section>
<div class="container">    
    <div class="row">                          
         @render('content')
    </div>
</div>
</section>
    
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>

