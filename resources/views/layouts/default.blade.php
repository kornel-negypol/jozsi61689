<!doctype html>
<html lang="Hu">
 <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Feladat kezelő') }}</title>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.js"></script>
    <script src="/js/app.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>  
    <!-- Styles -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" href="/css/bootstrap-datepicker.min.css">
	<link rel="stylesheet" href="/css/font-awesome.min.css">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" rel="stylesheet">

<style class="ticket-styles">
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
#menu-row {
	background-color: #fefefe;
    line-height:22px;
}
#header-menu li{
    border: 1px solid gray;
    line-height: 16px;
    padding:0px;
    margin:0px;
}
#header-menu li:first-child{
    margin-left: 0px;
    width: 110px;
    text-align: right;
}
#header-menu li:last-child{
    width: 130px;
}
#header-menu ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    text-align: left;
}
#header-menu a {
	position: relative;
	display: block;
	padding: 5px 31px 5px;
	font-size: 12px;
    text-decoration: none;
    color: black;
}    
#header-menu a:hover {
	background: #ccc;
	box-shadow: inset 0 -2px #555960;
	color: #000;
}
#header-instant-search-container{
		position: absolute;
		bottom: -60px;
		left: -15px;
		width: -moz-calc(100% + 30px);
		width: -webkit-calc(100% + 30px);
		width: calc(100% + 30px);
		background-image: url('/images/search_bg.jpg');
		background-size: cover;
		z-index: 9;
		text-align: center;
}
#header-instant-search-container::before{
    position: absolute;
    top: -10px;
    left: 0;
    content: '';
    width: 0;
    height: 0;
    border-style: solid;
    border-width: 0 0 10px 15px;
    border-color: transparent transparent #cbcbcb transparent;
}
#header-instant-search-container::after{
    position: absolute;
    top: -10px;
    right: 0;
    content: '';
    width: 0;
    height: 0;
    border-style: solid;
    border-width: 10px 0 0 15px;
    border-color: transparent transparent  transparent #cbcbcb;
}
#header-search{
    display:inline-block;
	width: 600px;
	position: relative;
	top: 9px;
}
#search{z-index:100}

#search-stripe-text{
	font-family: "arial narrow", arial;
	font-stretch: condensed;
	display: inline-block;
	line-height: 40px;
	font-size: 14px;
	color: #fefefe;
	position: relative;
	bottom: 0;
	padding-right: 10px;
    padding-left:-30px;
}
#third-chevron{
	font-size: 18px;
	color: #dbf0f5;
	border: 3px solid #dbf0f5;
	border-radius: 50%;
	width: 28px;
	height: 28px;
	padding-top: 3px;
	padding-left: 2px;
}
#second-chevron{
	font-size: 16px;
	color: #c7eaf1;
	border: 3px solid #c7eaf1;
	border-radius: 50%;
	width: 26px;
	height: 26px;
	padding-top: 3px;
	padding-left: 2px;
	position: relative;
	bottom: 1px;
}
#first-chevron{
	font-size: 14px;
	color: #ade1ea;
	border: 3px solid #ade1ea;
	border-radius: 50%;
	width: 24px;
	height: 24px;
	padding-top: 2px;
	padding-left: 2px;
	position: relative;
	bottom: 2px;
	margin-left: 15px;
}
#search-before-i{
	width: 8%;
	line-height: 26px;
	float: left;
	border-radius: 2px 0 0 2px;
	background-color: #f6f6f6;
}
#header-search-textbox{
	width: 87%;
	border: none;
	border-left: 1px solid #c8c0c5;
	font-size: 13px;
	line-height: 14px;
    height: 25px;
}
.ps-button-instant-search{
		border-radius: 2px;
		border: 1px solid #fefefe;
		padding: 5px 15px;
		background-color: transparent;
		color: #fefefe;
		margin-left: 0px;
		line-height: 12px;
		font-size: 10px;
}
/* left menu */
#sidemenu-container{
    margin-top: 70px;
    margin-left: -20px;
}
#sidemenu-container .list-group-item{background-color:transparent}
#sidemenu-container .list-group-item:hover{display:block}
.category-group{display:inline-block}
.list-group-item{
    border:none;
    padding:5px 10px
}
.list-group-item-category{
    font-weight:700;
    margin-top:10px;
    font-size:16px;
    padding-right:0;
    cursor:pointer;
}
.list-group-item-category a {
				color: black;
				text-decoration: none;
}
.list-group-item-category:before{
    content:" ";
    display:inline-block;
    width:6px;
    height:15px;
    margin-right:8px;
    position:relative;
    top:2px;
    background-color:#a40f1c;
}
.child-category-item{
    margin-left:30px;
}
.child-category-item:hover, .list-group-item-category:hover{
    background:rgba(255,255,255,0);
    background:-moz-linear-gradient(left,rgba(255,255,255,0) 0,rgba(110,205,221,.76) 100%);
    background:-webkit-gradient(left top,right top,color-stop(0,rgba(255,255,255,0)),color-stop(100%,rgba(110,205,221,.76)));
    background:-webkit-linear-gradient(left,rgba(255,255,255,0) 0,rgba(110,205,221,.76) 100%);
    background:-o-linear-gradient(left,rgba(255,255,255,0) 0,rgba(110,205,221,.76) 100%);
    background:-ms-linear-gradient(left,rgba(255,255,255,0) 0,rgba(110,205,221,.76) 100%);
    background:linear-gradient(to right,rgba(255,255,255,0) 0,rgba(110,205,221,.76) 100%);
    filter:progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#6ecddd', GradientType=1 )
}
.color-orange{color:#f79421}

.content{
	padding-top: 40px;
    background-color: #e6fefb;
    width: 760px;
    min-height: 700px;
}
.content .uzenofal-cont-category h1,.login-login-submit{-moz-box-sizing:border-box;-webkit-box-sizing:border-box}
.content{padding-bottom:20px;padding-left:0;padding-right:0; padding-top: 70px;}
.content h1{font-size:22px;margin-top:0;margin-bottom:5px}
.content h2{font-size:18px;margin-top:10;margin-bottom:0; text-align: center}
.content h3{font-size:16px}

/* Web Statistics mini
---------------------------------------------------------------------------------------------- */
.web-stats {
  padding: 10px;
  margin-bottom: 20px;
  background-color: #FFFFFF;
  border: 1px solid #d7e3f0;
  height: 80px;
}
.web-stats > .mini-graph.success {
  background-color: #48C9B0;
}
.web-stats > .mini-graph.info {
  background-color: #3498DB;
}
.web-stats > .mini-graph.warning {
  background-color: #F5D313;
}
.web-stats > .mini-graph.danger {
  background-color: #E74C3C;
}
.web-stats > div > h5 {
  font-size: 20px !important;
}
.web-stats > span {
  padding: 5px;
}
.web-stats.primary {
  border-left: 4px solid #48C9B0;
}
.web-stats.success {
  border-left: 4px solid #2ECC71;
}
.web-stats.warning {
  border-left: 4px solid #F5D313;
}
.web-stats.danger {
  border-left: 4px solid #E74C3C;
}
.web-stats.info {
  border-left: 4px solid #3498DB;
}
.web-stats.inverse {
  border-left: 4px solid #34495E;
}
/* ------ Hibajegyek ------- */
.newticket_btn {
    margin-top: 25px;
    width: 130px;
    height: 33px;
    background-color: gray;
    color: white;
    -moz-border-radius:5px;
	-webkit-border-radius:5px;
	border-radius:5px;
	border:1px solid #566963;
}
.newticket_btn:hover {
    background: #dedede;
    color: gray;
}
.ticket-group > *{
   margin-left: 10px;
   margin-right: 10px;
}
.ticket-title-row {
    background-color: #dfdfdf;
    color: blue;
    text-align: center;
    padding: 4px 5px 4px;
}
.ticket-header td {
    margin: -5px -6px 0px 0px;
    padding: 2px 5px 2px;
    background-color: #a4a4a4;
    color: white;
    border: 2px solid #a4a4a4;
}
.ticket-header td:first-child {
    color: #a4a4a4;
}
.clickable-row:hover {
	background-color: #eeeedd;
}
.ticket-row {
	width: 740px;
}
.ticket-row td {
    margin: 0px -6px 0px 0px;
    padding: 2px 5px 2px;
    height: 28px;
    border: 2px solid #a4a4a4;
    border-top: 0px
    border-collapse: collapse;
}
.col-1 {
    width: 60px;
}
.col-2 {
    width: 128px;
}
.col-4 {
    width: 134px;
}
.col-5 {
    width: 128px;
}
.col-blue {
	color: blue;
}
.ticket-block {
    margin-top: 20px;
    display: block;
    width: 198px;
    background-color: #f3fafa;
}
.ticket-block label {
	padding-top: 7px;
	padding-bottom: 0px;
}
.btn-long {
  width: 200px;
  margin-top:20px;
}
/* --------- Actions ------------*/ 
.action-header td {
    margin: -5px -6px 0px 0px;
    padding: 2px 5px 2px;
    background-color: #a4a4a4;
    color: white;
    border: 2px solid #a4a4a4;
}

/* ---------- Timed Tasks ----------*/
.task-header td {
    margin: -5px -6px 0px 0px;
    padding: 2px 5px 2px;
    background-color: #a4a4a4;
    color: white;
    border: 2px solid #a4a4a4;
}


/* ---------- Partnerek ----------*/
.partner-header td {
    margin: -5px -6px 0px 0px;
    padding: 2px 5px 2px;
    background-color: #a4a4a4;
    color: white;
	border: 0px;
}
.partner-row {
	width: 740px;
}
.partner-row td {
    margin: 0px -6px 0px 0px;
    padding: 2px 5px 2px;
    height: 28px;
    border: 2px solid #a4a4a4;
    border-top: 0px
    border-collapse: collapse;
}
.col-partner-1 {
   width: 430px;	
}
.col-partner-2 {
   width: 28px;
   text-align: center;
}
.name-block {
    margin-top: 90px;
    display: block;
	text-align: center;
    font-weight: bold;
    width: 198px;
	height: 30px;
	padding-top: 6px;
    background-color: #e6fefb;
}
.name-block:hover {
	background-color: #b6eeeb;
}
.take-block {
    margin-top: 5px;
    display: block;
	text-align: center;
    font-weight: bold;
    width: 198px;
	height: 30px;
	padding-top: 6px;
    background-color: #f2fefb;
}
.take-block:hover {
	background-color: #b6eeeb;
}
.ceg-block {
    margin-top: 20px;
    display: block;
    width: 198px;
    background-color: #ffffff;
}
.ceg-block li {
    list-style-type: none;
    padding: 2px 3px 2px 4px;
	text-align: center;
}
.ceg-block span {
    font-weight: bold;
}
.ceg-block label {
	padding-top: 7px;
	padding-left: 5px;
	padding-bottom: 0px;	
}
.ceg-block div {
	float: left;
	width: 100%;
	margin-top: 20px;
}
.activity-block {
    margin-top: 15px;
    display: block;
    width: 198px;
    background-color: #ffffff;
}
.activity-block li {
    list-style-type: none;
    padding: 2px 3px 2px 4px;
	text-align: center;	
}
.activity-block span {
    font-weight: bold;
}
.block-header {
	background-color: #e6fefb;
	font-size: larger;
}
div.centre {
  text-align: left;
  width: 100px;
  display: block;
  margin-top: 30px;
  margin-left: auto;
  margin-right: auto;
}
div.centre-2 {
  text-align: left;
  width: 200px;
  display: block;
  margin-top: 20px;
  margin-left: auto;
  margin-right: auto;
}
div.centre button {
	width: 100px;
	margin-left: auto;
	margin-right: auto;
}
div.top-row {
	text-align: right;
	width: 740px;
	display: block;
	margin-top: 10px;
	margin-left: auto;
	margin-right: auto;
	margin-bottom: 20px;
}
tr.pointer {
		 cursor: pointer;
}
/* ----------- Modal ------------ */
#myModal {
	top: 130px;
	outline: no;
}
#myModal label {
	margin-left:150px;
}
#myModal.modal-content {
		background: red;
		color: white;
		top:60px
}
hr {
	border-width: 2px;
}
.wrapper {
	text-align: center;
}
.upload {
	display: inline-block; 
}
#uploadModal {
	top: 25% 
}
#uploadModal label {
	display: block;
	font-size: 20px; 
	text-align: center;
}
.modal-content {
		background: orange;
		color: white;
		top:60px;
}
.confirm {
	display: block;
	text-align: center;
}
.confirm button {
	margin-right: 30px;
	margin-left: 30px;
	text-align: center;
}
/* -------------- file upload ----------------- */
.hide-file {
    width: 0.1px;
	height: 0.1px;
	opacity: 0;
	overflow: hidden;
	position: absolute;
	z-index: -1;
}
.hide-file + label {
    font-size: 1.2em;
    font-weight: 500;
    background-color: transparent;
    width: 180px;
    height: 30px;
    border: 2px solid #ccc;
    border-radius: 10px;
    display: inline-block;
    cursor: pointer;
}
.hide-file:focus + label {
	background-color: #fefffe;
}
.hide-file + label:hover {
    background-color: #d1e1e2;
}
.fname {
    color: blue;    
}

input[type=checkbox] {
  transform: scale(1.2);
  margin-top: 0px;
}
/* -------------- Napi munkák ------------------ */

.munka-group {
   margin-left: 10px;
   margin-right: 10px;
   margin-bottom: 10px;
}
.munka-title-row {
    margin-top: 15px;
    background-color: #dfdfdf;
    color: blue;
    text-align: center;
    padding: 4px 5px 4px;
}
.newmunka_btn {
    margin-top: 10px;
    width: 130px;
    height: 33px;
    background-color: gray;
    color: white;
    -moz-border-radius:5px;
	-webkit-border-radius:5px;
	border-radius:5px;
	border:1px solid #566963;
}
.newmunka_btn:hover {
    background: #dedede;
    color: gray;
}
.munka-header td {
    margin: -5px -6px 0px 0px;
    padding: 2px 5px 2px;
    background-color: #a4a4a4;
    color: white;
    border: 2px solid #a4a4a4;
}

</style>
</head>
<script>

</script>

<body class="common-home">
				<div class="container-fluid maincontainer">
        <header> @include('layouts.header3') </header>
        <div class="container">
            <div class="row">
                @include('layouts.sidebar')
               
                <div class="content col-sm-8">
                    @yield('content')
																</div>
                @include('layouts.rightside')
            </div>
        </div>
    </div>
</body>
</html>

