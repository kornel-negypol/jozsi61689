@extends('layouts.default')
@section('content')
<!-- Új feladat gomb  --> 

	<div class="top-row">
	    <div >
	        <button class="newticket_btn" style="margin-right: 40px; margin-top: 0px;" onclick="window.location.href='/timing'">Új feladat</button>
	    </div>
	</div>


<!-- feladatok listázása --> 
    <div class="ticket-group">
        <div class="ticket-title-row">
			IDŐZÍTETT FELADATOK
        </div>
			<table class="ticket-row">    
				<tr class="task-header">
					<td > </td>
	                <td >Tárgy</td>
	                <td style="width: 250px">Ügyfél neve</td>
					<td style="width: 95px">Esedékes</td>
					<td style="width: 75px">Ismétlés</td>
				</tr>
@foreach($tasks as $task)
                <tr class="clickable-row" data-href="editTimedTask/{{ $task->task_ID }}">
					<td >{{ $task->task_ID }}</td>
                    <td class="col-blue">{{ $task->title }}</td>
                    <td >{{ $task->partner_name }}</td>
                    <td >{{ $task->next_time }}</td>                                        
                    <td >{{ $task->repeat_time }} {{ $task->repeat_cycle }}</td>                                        
                </tr>
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
