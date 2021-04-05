@extends('layouts.default')
@section('content')
    <h2>Az {{ $ticket->ticket_ID }} számú hibajegy módosítása</h2>
    <br>
    <form name="ticket-form" class="form-horizontal" role="form" method="POST" action="{{ url('/updateTicket') }}">
        {{ csrf_field() }}
        <input type='hidden' name='ticket_ID' value='{{ $ticket->ticket_ID }}'>
        <div class="form-group" >  
            <label class="col-md-3 control-label" for="partner_id" >Ügyfél: </label>
            <div class="col-md-4 ">
                <input id="partner_name" type="text" class="form-control" name="ticket-title" value="{{ $partner->partner_name }}" readonly="readonly">
            </div>          
        </div>
@if ($user_type <> 'partner')
        <div class="form-group" >            
            <label for="topic" class="col-md-3 control-label" >Tevékenységi kör: </label>
            <div class="col-md-4 ">
                <select class="form-control" name="topic" id="topic">
                    <option value="IT-szerviz" <?php if ($ticket->topic == 'IT-szerviz') {echo 'selected';}?> >IT-szerviz</option>
                    <option value="Printer-szerviz" <?php if ($ticket->topic == 'Printer-szerviz') {echo 'selected';}?> >Printer-szerviz</option>
                </select>
            </div>         
        </div>
@endif        
        <div class="form-group{{ $errors->has('ticket-title') ? ' has-error' : '' }}">
            <label for="ticket-title" class="col-md-3 control-label">Bejelentés tárgya:</label>
    
            <div class="col-md-8">
                <input id="ticket-title" type="text" class="form-control" name="ticket-title" value="{{ $ticket->title }}" readonly="readonly">
            </div>
        </div>
            
        <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
            <label for="content" class="col-md-3 control-label">Hiba leírása:</label>
    
            <div class="col-md-8">
                <textarea id="content" class="form-control" name="content" rows="7" readonly="readonly">{{ $ticket->content }}</textarea>
           </div>
        </div>
            
        <div class="form-group" >            
            <label for="priority" class="col-md-3 control-label" >Prioritás: </label>
            <div class="col-md-4 ">
                <select class="form-control" name="priority" id="priority">
                    <option value="Normál" <?php if ($ticket->priority == 'Normál') {echo 'selected';}?> >Normál</option>
                    <option value="Sürgős" <?php if ($ticket->priority == 'Sürgős') {echo 'selected';}?> >Sürgős</option>
                    <option value="Kritikus" <?php if ($ticket->priority == 'Kritikus') {echo 'selected';}?> >Kritikus</option>
                </select>
            </div>         
        </div>
            
@if ($user_type <> 'partner')
        <div class="form-group" >            
            <label for="source" class="col-md-3 control-label" >Bejelentés módja: </label>
            <div class="col-md-4 ">
                <select class="form-control" name="source" id="source">
                    <option value="telefon" <?php if ($ticket->source == 'telefon') {echo 'selected';}?> >telefon</option>
                    <option value="e-mail" <?php if ($ticket->source == 'e-mail') {echo 'selected';}?> >e-mail</option>
                    <option value="web" <?php if ($ticket->source == 'web') {echo 'selected';}?> >web</option>
                    <option value="egyéb" <?php if ($ticket->source == 'egyéb') {echo 'selected';}?> >egyéb</option>
                </select>
            </div>         
        </div>
        <div class="form-group" >
            <input type="hidden" name="previous_owner" value="{{ $ticket->owner }}">
            <input type="hiden" name="title" value="{{ $ticket->title }}">
            <label for="owner" class="col-md-3 control-label" >Felelős: </label>
            <div class="col-md-4 ">
                <select class="form-control" name="owner" id="owner">
@foreach($users as $user) 
                    <option value="{{ $user->id }}" <?php if ($ticket->owner == $user->id) {echo 'selected';}?> >{{ $user->name ." ". $user->firstname }} </option>
@endforeach
                </select>
            </div>         
        </div>
@else
        <input type="hidden" name="owner" value="{{ $ticket->owner }}">
@endif            
        <div class="centre">
            <button type="submit" class="btn btn-warning">Rögzít</button>
        </div>
    </form>
<script>
$('.input-group.date').datepicker({
    weekStart: 1,
    startDate: 0,
    language: "hu",
    orientation: "top auto",
    autoclose: true,
    todayHighlight: true
});
</script>
    
@stop