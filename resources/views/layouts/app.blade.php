<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Ticket') }}</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
<style>
body{
	background: repeating-linear-gradient(
	  -45deg,
	  #efefef,
	  #efefef 2px,
	  #fdfdfd 2px,
	  #fdfdfd 6px
	);
}
.maincontainer{
    background-color:#FFF;
    background-image:url("/images/background_3.png");
    background-repeat:repeat;
    background-position-y:135px;
    padding-right:0;
    padding-left:0;
    width:1200px;
}

#header{
    position: relative;
	background-image: url("/images/header_bg.jpg");
    padding: 0px;
    background-size:contain;
}
.header-pic-cont{
    padding-bottom:5px;
    padding-left:45px;
}
#header-contact{
	padding: 10px;
	background: rgba(242,242,242,1);
	background: -moz-linear-gradient(45deg, rgba(242,242,242,1) 0%, rgba(255,255,255,1) 47%, rgba(255,255,255,1) 100%);
	background: -webkit-gradient(left bottom, right top, color-stop(0%, rgba(242,242,242,1)), color-stop(47%, rgba(255,255,255,1)), color-stop(100%, rgba(255,255,255,1)));
	background: -webkit-linear-gradient(45deg, rgba(242,242,242,1) 0%, rgba(255,255,255,1) 47%, rgba(255,255,255,1) 100%);
	background: -o-linear-gradient(45deg, rgba(242,242,242,1) 0%, rgba(255,255,255,1) 47%, rgba(255,255,255,1) 100%);
	background: -ms-linear-gradient(45deg, rgba(242,242,242,1) 0%, rgba(255,255,255,1) 47%, rgba(255,255,255,1) 100%);
	background: linear-gradient(45deg, rgba(242,242,242,1) 0%, rgba(255,255,255,1) 47%, rgba(255,255,255,1) 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f2f2f2', endColorstr='#ffffff', GradientType=1 );
	display: inline-block;
	border: 1px solid #e9e9e9;
	position: relative;
	top: 10px;
	text-align: left;
	color: #566e6c;
	font-family: "arial narrow", arial;
	font-stretch: condensed;
}

#header-contact a{
	color: #566e6c;
}
#header-contact::after{
	content: '';
	display: block;
	background-image: url('/images/header_contact_shadow.png');
	background-size: contain;
	background-repeat: no-repeat;
	width: 100%;
	height: 20px;
	position: absolute;
	bottom: -19px;
	left: 0;
}
#header-contact .fa-mobile{
	font-size: 24px;
	width: 20px;
	text-align: center;
}
#header-contact .fa-phone{
	font-size: 20px;
	width: 20px;
	text-align: center;
}
#header-contact .fa-envelope{
	width: 20px;
	text-align: center;
}
.phone-row{
	font-size: 19px;
	text-align: justify;
}
.email-row{
	font-size: 16px;
	font-weight: bold;
}
#header-logo img{
	position: relative;
	left: 30px;
    top: 20px
}

#header-logo{
    line-height:72px;
    text-align:center;
    padding-left:0px
}

</style>
</head>
<body class="common-home">
    <div id="app">
        <div class="container-fluid maincontainer">

		<div class="header" id="header">
			<div class="row">

				<div class="header-logo col-md-4 col-md-offset-0 col-sm-6 col-xs-6" id="header-logo">
					<a href="#">
						<img src="/images/NP_logo_new.jpg">
					</a>
				</div>

				<div class="header-pic-cont col-md-4 hidden-xs hidden-sm">
					<div id="bolt-kepek">
						<img src="/images/Kep_kozepen.png">
					</div>
                </div>
                <div class="hidden-xs hidden-sm col-md-3 col-md-offset-0 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1" id="header-third-container">
					<div id="header-contact">
						<div class="phone-row"><i class="fa fa-phone fa-rotate-90" aria-hidden="true"></i> +36 1 350 6157</div>
						<div class="phone-row"><i class="fa fa-mobile" aria-hidden="true"></i> +36 70 369 1873</div>
						<div class="email-row"><i class="fa fa-envelope" aria-hidden="true"></i> <a href="mailto:support@negypolus.hu">support@negypolus.hu</a></div>
					</div>
                </div>
            </div>
        </div>
            
        </div>
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="/js/app.js"></script>
</body>
</html>
