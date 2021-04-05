@extends('layouts.default')
@section('content')


<h2>Dokumentum feltöltés</h2><br>
<div class="wrapper">
    {!! Form::open(
        array(
            'action' => 'BasicController@saveFile', 
            'novalidate' => 'novalidate',
            'id' => 'file-form', 
            'files' => true)) !!}
    {!! Form::hidden('connected_partner', session('partner')) !!}
    {!! Form::hidden('type', 'document') !!}
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
