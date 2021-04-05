@extends('layouts.default')
@section('content')
    <h2>Új ügyfél rögzítése</h2><br>
    <form class="form-horizontal" role="form" method="POST" action="{{ url('/addPartner') }}">
        {{ csrf_field() }}
    
        <div class="form-group{{ $errors->has('partner_name') ? ' has-error' : '' }}">
            <label for="partner_name" class="col-md-4 control-label">Ügyfél neve:</label>
    
            <div class="col-md-6">
                <input id="partner_name" type="text" class="form-control" name="partner_name" value="{{ old('partner_name') }}" required autofocus>
    
                @if ($errors->has('partner_name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('partner_name') }}</strong>
                    </span>
                @endif
            </div>
        </div>

         <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
            <label for="name" class="col-md-4 control-label">Város:</label>
    
            <div class="col-md-6">
                <input id="city" type="text" class="form-control" name="city" value="{{ old('city') }}" required>
    
                @if ($errors->has('city'))
                    <span class="help-block">
                        <strong>{{ $errors->first('city') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    
        <div class="form-group{{ $errors->has('zip_code') ? ' has-error' : '' }}">
            <label for="email" class="col-md-4 control-label">Irányító szám:</label>
    
            <div class="col-md-6">
                <input id="zip_code" type="text" class="form-control" name="zip_code" value="{{ old('zip_code') }}" required>
    
                @if ($errors->has('zip_code'))
                    <span class="help-block">
                        <strong>{{ $errors->first('zip_code') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    
        <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
            <label for="address" class="col-md-4 control-label">Telephely címe:</label>
    
            <div class="col-md-6">
                <input id="address" type="text" class="form-control" name="address" value="{{ old('address') }}" required>
    
                @if ($errors->has('address'))
                    <span class="help-block">
                        <strong>{{ $errors->first('address') }}</strong>
                    </span>
                @endif
            </div>
        </div>

       <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label for="email" class="col-md-4 control-label">Hibabejelető email cím:</label>
    
            <div class="col-md-6">
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" >
    
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('comment') ? ' has-error' : '' }}">
            <label for="comment" class="col-md-4 control-label">Megjegyzés:</label>
    
            <div class="col-md-6">
                <textarea id="comment" class="form-control" name="comment" rows="3">{{ old('comment') }}</textarea>
    
                @if ($errors->has('comment'))
                    <span class="help-block">
                        <strong>{{ $errors->first('comment') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group" >            
            <label for="default_topic" class="col-md-4 control-label" >Tevékenységi kör: </label>
            <div class="col-md-4 ">
                <select class="form-control" name="default_topic" id="default_topic">
                    <option value="IT-szerviz" selected>IT-szerviz</option>
                    <option value="Printer-szerviz" >Printer-szerviz</option>
                </select>
            </div>         
        </div>
        <div class="form-group" >            
            <label for="responsible" class="col-md-4 control-label" >Felelős: </label>
            <div class="col-md-4 ">
                <select class="form-control" name="responsible" id="responsible">
@foreach($users as $user)
                    <option value="{{ $user->id }}" >{{ $user->name.' '.$user->firstname }}</option>
@endforeach
                </select>
            </div>         
        </div>
       
        <div class="centre">
                <button type="submit" class="btn btn-warning">Rögzít</button>
        </div>
    </form>

@stop
