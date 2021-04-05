@extends('layouts.default')
@section('content')

    
        <form name="comment-form" class="" role="form" method="POST" action="{{ url('/addComment') }}">
        {{ csrf_field() }}
            <br>
            <input type="hidden" name='ticket_ID' value="{{ $ticket->ticket_ID }}">
            <input type="hidden" name='owner' value="{{ $ticket->owner }}">
            <div class="panel panel-default">
                <div class="panel-heading text-center">Bejelentés tárgya:</div>
                <div class="panel-body text-left">{{ $ticket->title }}</div>
            </div>
            <div class="">
                <div class="panel panel-default">
                    <div class="panel-heading text-center">Hiba leírása:</div>
                    <div class="panel-body text-left">{!! nl2br($ticket->content) !!}</div>
                </div>
            </div>
@foreach($comments as $comment)               
            <div class="">
                <div class="panel panel-default">
                    <div class="panel-heading text-center">{{$comment->firstname}} - {{$comment->created}} - {{$comment->comment_type}}</div>
@if ($comment->comment_type == 'private')
                    <div class="panel-body text-left" style="background: LightYellow">{!! nl2br($comment->comment) !!}</div>
@else                        
                    <div class="panel-body text-left">{!! nl2br($comment->comment) !!}</div>
@endif
                </div>
            </div>
@endforeach
            <div class="panel panel-default">
                <div class="panel-heading text-center">Feltöltések:</div>
@foreach ($attachments as $attachment)
                <a href={{ '/downloadFile/' . $attachment->ID }} style="margin-left: 15px">{{ $attachment->origname }}</a><br>
@endforeach
               
            </div>
            <div class="panel panel-default">
                <div class="panel-heading text-center">Hozzászólás:</div>
                <textarea id="comment" class="form-control" name="comment" rows="5" autofocus="autofocus" placeholder="Hozzászólás helye..." autofocus></textarea>
            </div>
@if($user_type <> 'partner')
            <div class="checkbox centre" style="width: 180px">                
                    <label><input type="radio" name="comment_type" class="hide_email" value="public" checked="checked"> Hozzászólás</input></label><br>
                    <label><input type="radio" name="comment_type" class="hide_email" value="private"> Belső hozzászólás</input></label><br>
                    <label><input type="radio" name="comment_type" class="show_email" value="email"> E-mail</input></label>
            </div>
            <div id= "email" class="centre" style="width: 350px; display: none">
                <input type="email" name="email" style="width: 350px" value="{{ $ticket->reply_address }}" multiple>
            </div>    
@else
            <input type="hidden" name="comment_type" value="public" checked="checked"></input>
    
@endif
            <br>
            <div class="centre">
                    <button type="submit" class="btn btn-warning btn-short">Rögzít</button>
            </div>
            <div class="">
                <br><br>
            </div>
        </form>


<!-- Hibajegy lezárás modal -->
<form class="form-horizontal" role="form" method="post" action="/closeTicket"> 
<!-- The Modal -->
  <div id="myModal" class="modal fade">
    <div class="modal-dialog">
<!-- Modal content -->
      <div class="modal-content">
       {{ csrf_field() }}
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h2 class="modal-title">Ráfordítás</h2>
        </div>
        <div class="modal-body form-group" >
            <br>
            <textarea style="width: 400px; margin-left: 100px; color: black;" id="comment" name="comment" rows="5" autofocus="autofocus" placeholder="Hozzászólás helye..." autofocus></textarea>
            <input type="hidden" name="ticket_ID" value="{{$ticket->ticket_ID}}">
            <input type="hidden" name="ticket_title" value="{{$ticket->title}}">
            <input type="hidden" name="reply_address" value="{{$ticket->reply_address}}">
            <input type="hidden" name="partner_ID" value="{{$ticket->partner_ID}}">
            <label for="hours">Munkaidőben: </label>
            <input type="number" name="hours" id="hours" placeholder="óra" value='{{ $hours }}' style="width: 50px; margin-left:30px; color: black;" min="0" max="99" > óra       
            <input type="number" name="minutes" id="minutes" placeholder="perc" value='{{ $minutes }}' style="width: 50px; color: black;" min="0" max="59" step="15"> perc
            <br><br>
        
            <label for="hours">Munkaidőn kívül: </label>
            <input type="number" name="hours2" id="hours" placeholder="óra" value='{{ $hours2 }}' style="width: 50px; margin-left:13px; color: black;" min="0" max="99" > óra       
            <input type="number" name="minutes2" id="minutes" placeholder="perc" value='{{ $minutes2 }}' style="width: 50px; color: black;" min="0" max="59" step="15"> perc
            <br><br>
            <div class="centre">
                <button type="submit" class="btn btn-default" ">Elment</button>
            </div>
            
        </div>
      </div>
    </div>
  </div>
</form>
    
<script>

$('.show_email').click(function() {
    document.getElementById('email').style.display = "block";
});

$('.hide_email').click(function() {
    document.getElementById('email').style.display = "none";
});
</script>
@stop