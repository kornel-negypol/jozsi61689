@extends('layouts.default3')
@section('content')    

    <div class="row">
					<div class="col-md-3" style="margin-left: 10px;">
						<div class="web-stats warning" >
							<div class="pull-left">
								<h5>23 db</h5>
								<span class="description"><a href="#">Nyitott hibajegy</a></span>
							</div>
							<span class="pull-right mini-graph warning"></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="web-stats success">
							<div class="pull-left">
								<h5>2916 db</h5>
								<span class="description"><a href="#">Lezárt hibajegy</a></span>
							</div>
							<span class="pull-right mini-graph success"></span>
						</div>
					</div>
					<div class="col-md-3">
						<div class="web-stats danger">
							<div class="pull-left">
								<h5>11 </h5>
								<span class="description"><a href="#">Olvasatlan értesítés</a></span>
							</div>
							<span class="pull-right mini-graph danger"></span>
						</div>
					</div>
					<div class="col-md-3" style="margin-left: -10px;">
						<div >
							<button class="newticket_btn">Új hibajegy</button>
						</div>
					</div>
	</div>
    <div class="ticket-group">
        <div class="ticket-title-row">
            NYITOTT HIBAJEGYEK, FELADATOK
        </div>
            <div class="ticket-header">    
                <li class="col-1">Azonos</li>        
                <li class="col-2">Felvétel ideje</li>
                <li class="col-3">Tárgy</li>
                <li class="col-4">Felelős</li>
                <li class="col-5">Módosítás</li>                                        
            </div>
 
            <table class="ticket-row">
                <tl></tl>
                    <td class="col-1">12034</td>        
                    <td class="col-2">2017-02-13 12:34</td>
                    <td class="col-3">Bonyolult hibajelenség</td>
                    <td class="col-4">Opolcsik Ferenc</td>
                    <td class="col-5">2017-02-13 12:34</td>                                        
                </tl>
                    
            </table>
            <ul class="list-inline ticket-row">
                <li class="col-1">1202</li>        
                <li class="col-2">2017-02-13 12:34</li>
                <li class="col-3">Tárgy a hibáról</li>
                <li class="col-4">Köves Krisztián</li>
                <li class="col-5">2017-02-13 12:34</li>                                        
            </ul>
	</div>

<br><br>


@stop