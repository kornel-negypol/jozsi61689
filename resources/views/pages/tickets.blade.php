@extends('layouts.default')
@section('content')
<div class="row">
	<div class="col-md-2" style="margin-left: 25px;">
	    <div >
	@if(session('task_type') == 'hibajegy')
	        <button class="newticket_btn" onclick="window.location.href='/newTicket'">Új hibajegy</button>
	@else
	        <button class="newticket_btn" onclick="window.location.href='/newTask'">Új feladat</button>
	@endif
	    </div>
	</div>
	<div class="col-md-3" style="margin-left: 20px;">
	    <div class="web-stats warning" >
	        <div class="pull-left">
	            <h5>{{ session('open_tickets') }} db</h5>
	            <span class="description"><a href="#" onclick="set_param('Nyitott')">Nyitott hibajegy</a></span>
	        </div>
	        <span class="pull-right mini-graph warning"></span>
	    </div>
	</div>
	<div class="col-md-3">
	    <div class="web-stats success">
	        <div class="pull-left">
	            <h5>{{ session('closed_tickets') }} db</h5>
	            <span class="description"><a href="#" onclick="set_param('Lezárt')">Lezárt hibajegy</a></span>
	        </div>
	        <span class="pull-right mini-graph success"></span>
	    </div>
	</div>
	<div class="col-md-3">
	    <div class="web-stats danger">
	        <div class="pull-left">
	            <h5>{{ session('number_of_activities') }} db </h5>
	            <span class="description"><a href="freshEvents">Friss események</a></span>
	        </div>
	        <span class="pull-right mini-graph danger"></span>
	    </div>
	</div>
</div>

<!-- hibajegyek listázása --> 
    <div class="ticket-group">
        <div class="ticket-title-row">
@if($state=="Nyitott")
	@if(session('task_type') == 'hibajegy')
			NYITOTT HIBAJEGYEK
	@else
			FOLYAMATBAN LEVŐ FELADATOK
	@endif
@endif
@if($state=="Lezárt")
	@if(session('task_type') == 'hibajegy')
			LEZÁRT HIBAJEGYEK
	@else
			BEFEJEZETT FELADATOK
	@endif
@endif
@if($state=="Minden")
	@if(session('task_type') == 'hibajegy')
			MINDEN HIBAJEGY
	@else
			MINDEN FELADAT
	@endif
@endif
        </div>
			<table class="ticket-row">    
				<tr class="ticket-header">     
	                <td class="col-1">Azonos</td>        
	                <td class="col-2">Felvétel ideje</td>
	                <td class="col-3">Tárgy</td>
		@if(session('partner') > 0)				
	                <td class="col-4">Felelős</td>
		@else
	                <td class="col-4">Ügyfél</td>
		@endif
					<td class="col-5">Módosítás</td>                                        
				</tr>
@foreach($tickets as $ticket)
	@if((session('task_type') == $ticket->task_type) and ($ticket->ticket_state <> 'Archive'))
@if($ticket->owner == 1)
                <tr class="clickable-row pointer" style="background-color: #f9ffc8;" data-href="editTicket/{{ $ticket->ticket_ID }}">
@else
                <tr class="clickable-row pointer" data-href="editTicket/{{ $ticket->ticket_ID }}">
@endif
                    <td class="col-1 col-blue">{{ $ticket->ticket_ID }}</td>        
                    <td class="col-2">{{ substr($ticket->created,0,16) }}</td>
                    <td class="col-3 col-blue">{{ $ticket->title }}</td>
		@if(session('partner') > 0)				
                    <td class="col-4">{{ $ticket->name ." ".$ticket->firstname }}</td>
		@else
                    <td class="col-4">{{ $ticket->partner_name }}</td>
		@endif
                    <td class="col-5">{{ substr($ticket->modified,0,16) }}</td>                                        
                </tr>
	@endif		
@endforeach
			</table>
		</div>
<script>
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });

function set_topic(topic) {
    document.getElementById('topic').value = topic;
    document.getElementById('tickets_select').submit();
}

</script>
@stop
