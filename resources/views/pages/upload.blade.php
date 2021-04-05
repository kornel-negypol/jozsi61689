@extends('layouts.default')
@section('content')


<h2>Csatolt fájlok a {{ $ticket_ID }}-s számú hibajegyhez</h2><br>
<div class="wrapper">
@foreach ($attachments as $attachment)
    <a href={{ asset('uploads/'.$attachment->filename) }}>{{ $attachment->origname }}</a><br>
@endforeach
<!--    <h3>Fájl feltöltés</h3><br> -->
    {!! Form::open(
        array(
            'action' => 'BasicController@saveFile', 
            'novalidate' => 'novalidate',
            'id' => 'file-form', 
            'files' => true)) !!}
        {!! Form::hidden('ticket_ID', $ticket_ID) !!}
        {!! Form::hidden('type', 'attachment') !!}
        <br><br>
        <div class="upload">
            <input type="file" name="file-up" id="file-up" class="hide-file" />
            <label for="file-up">⬆︎ Fájl kiválasztása</label>
            <label style="width:30px;"> </label>
            <label id="fName" name="fname" class="fname">Nincs fájl kiválasztva!</label>
        </div>
        <br><br>
        <div class="menu-form centre">
            {!! Form::button('Feltöltés',['type' => 'submit','class' => 'btn btn-warning']) !!}        
        </div>        
    {!! Form::close() !!}
</div>
<script>
$("#file-form :input").change(function() {
   $("#file-up").data("changed",true);
   $('#fName').text(this.value);
});    
</script>    
@stop
