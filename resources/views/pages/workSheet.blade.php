@extends('layouts.default')
@section('content')
<h2>{{ $ticket->ticket_ID }} számú munkalap</h2><br>
   
<form class="form-horizontal" role="form" method="post" action="/addWorkSheet"> 
       {{ csrf_field() }}
    <input type='hidden' name='ticket_ID' value='{{ $ticket->ticket_ID }}'>
    <div class="form-group">
        <div class="panel panel-default" style="margin: 5px 30px auto;">
                <div class="panel-heading">A hiba leírása:</div>
                <textarea id="hiba" class="form-control" name="hiba" rows="3">{!! nl2br($ticket->content) !!}</textarea>
                @if ($errors->has('hiba'))
                    <span class="help-block">
                        <strong style="color: red">{{ $errors->first('hiba') }}</strong>
                    </span>
                @endif
        </div>
        <div class="panel panel-default" style="margin: 5px 30px auto;">
                <div class="panel-heading">A készülék adatai:</div>
                <textarea type="text" class="form-control" name="adat" id="" placeholder="A készülék adatai" ></textarea>      
                @if ($errors->has('adat'))
                    <span class="help-block">
                        <strong style="color: red">{{ $errors->first('adat') }}</strong>
                    </span>
                @endif
        </div>
        <div class="panel panel-default" style="margin: 5px 30px auto;">            
                <div class="panel-heading">Az elvégzett munka:</div>
                <textarea type="text" class="form-control" name="munka" id="" placeholder="Az elvégzett munka" rows="3"></textarea>      
                @if ($errors->has('munka'))
                    <span class="help-block">
                        <strong style="color: red">{{ $errors->first('munka') }}</strong>
                    </span>
                @endif
        </div>
        <div class="panel panel-default" style="margin: 5px 30px auto;">            
                <div class="panel-heading">Megjegyzés:</div>
                <textarea type="text" class="form-control" name="megjegyzés" id="" placeholder="Megjegyzés" rows="2"></textarea>      
                @if ($errors->has('megjegyzés'))
                    <span class="help-block">
                        <strong style="color: red">{{ $errors->first('megjegyzés') }}</strong>
                    </span>
                @endif
        </div>
								<div id="worktime">
            <br>
            <label for="ticket_created">Bejelentés ideje: </label>
            <input type="date" name="ticket_created" value="<?php echo date("Y-m-d",strtotime($ticket->created));?>">
            <label for="ticket_closed">Befejezés ideje: </label>
            <input type="date" name="ticket_closed" value="<?php echo date("Y-m-d");?>">
            <br><br>
            <label for="hours">Munkaidőben: </label>
            <input type="number" name="hours" id="hours" placeholder="óra" style="width: 50px; margin-left:30px; color: black;" min="0" max="99" > :       
            <input type="number" name="minutes" id="minutes" placeholder="perc" style="width: 50px" min="0" max="59" step="15">
            
            <label for="hours" style="margin-left: 84px;">Munkaidőn kívül: </label>
            <input type="number" name="hours2" id="hours" placeholder="óra" style="width: 50px; margin-left:7px;" min="0" max="99" > :       
												<input type="number" name="minutes2" id="minutes" placeholder="perc" style="width: 50px" min="0" max="59" step="15">
            <br><br>
            <div class="centre">
																<input type="submit" class="btn btn-warning btn-default" " value="Elment">
            </div>
								</div>
    </div>
</form>
    
@stop