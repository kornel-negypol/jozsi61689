@extends('layouts.default')
@section('content')
    <h2 style="text-align: center; margin-top: 10px;">Új feladat</h2><br>
    <form name="ticket-form" id="ticket-form" class="form-horizontal" role="form" method="POST" action="{{ url('/addTask') }}">
        {{ csrf_field() }}
        <div class="form-group" >  
            <label class="col-md-3 control-label" for="partner_id" >Ügyfél: </label>
            <div class="col-md-4 ">
                <select class="form-control" name="partner_id" id="partner_id">
                    <option value="0" > Válasszon! </option>
@foreach($partners as $partner) 
                    <option value="{{ $partner->partner_ID }}" >{{ $partner->partner_name }}</option>
@endforeach
                </select>
            </div>          
        </div>
        <div class="form-group{{ $errors->has('ticket-title') ? ' has-error' : '' }}">
            <label for="ticket-title" class="col-md-3 control-label">Feladat megnevezése:</label>
    
            <div class="col-md-8">
                <input id="ticket-title" type="text" class="form-control" name="ticket-title" value="{{ old('ticket-title') }}" required autofocus>
    
                @if ($errors->has('title'))
                    <span class="help-block">
                        <strong>{{ $errors->first('ticket-title') }}</strong>
                    </span>
                @endif
            </div>
        </div>
            
        <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
            <label for="content" class="col-md-3 control-label">Feladat leírása:</label>
    
            <div class="col-md-8">
                <textarea id="content" class="form-control" name="content" rows="7">{{ old('content') }}</textarea>
    
                @if ($errors->has('content'))
                    <span class="help-block">
                        <strong>{{ $errors->first('content') }}</strong>
                    </span>
                @endif
            </div>
        </div>
                        
@if ($user_type=='partner')
        <input name="source" id="source" type="hidden" value="web">
@else
        <div class="form-group" >            
            <label for="source" class="col-md-3 control-label" >Bejelentés módja: </label>
            <div class="col-md-4 ">
                <select class="form-control" name="source" id="source">
                    <option value="Telefon" selected>Telefon</option>
                    <option value="E-mail" >E-mail</option>
                    <option value="Egyéb" >Egyéb</option>
                </select>
            </div>         
        </div>
@endif            
        <div class="form-group">
            <div class="col-md-4 col-md-offset-4">
                <button type="button" class="btn btn-warning clicked_rogzit">Rögzít</button>
            </div>
        </div>
    </form>
<script>
$('.clicked_rogzit').click(function() {
    var partner = document.getElementById('partner_id').value;
    
    if (partner != 0) {
        document.getElementById('ticket-form').submit();    
    }
    else {
        alert("Válasszon partnert!");
    }
});
    
</script>
            
@stop