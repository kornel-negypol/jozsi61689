@extends('layouts.default')
@section('content')
    <h2>Új felhasználó rögzítése</h2><br>
    
    <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
        {{ csrf_field() }}
    
         <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <label for="name" class="col-md-4 control-label">Vezetéknév:</label>
    
            <div class="col-md-6">
                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" autofocus>
    
                @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    
        <div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
            <label for="firstname" class="col-md-4 control-label">Keresztnév:</label>
    
            <div class="col-md-6">
                <input id="firstname" type="text" class="form-control" name="firstname" value="{{ old('firstname') }}" required>
    
                @if ($errors->has('firstname'))
                    <span class="help-block">
                        <strong>{{ $errors->first('firstname') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label for="email" class="col-md-4 control-label">E-Mail cím:</label>
    
            <div class="col-md-6">
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
    
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    
        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password" class="col-md-4 control-label">Jelszó:</label>
    
            <div class="col-md-6">
                <input id="password" type="password" class="form-control" name="password" required>
    
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    
        <div class="form-group">
            <label for="password-confirm" class="col-md-4 control-label">Jelszó megerősítése:</label>
    
            <div class="col-md-6">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
            </div>
        </div>
        <div class="form-group">
            <label for="user_type" class="col-md-4 control-label">Felhasználó típusa:</label>
            <div class="col-md-4">
                <select class="form-control" name="user_type" id="user_type">
                    <option value="partner" >Partner</option>
                    <option value="std_user">Dolgozó</option>
                    <option value="ext_user">Külsős</option>
                    <option value="admin">Adminisztrátor</option>   
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="partner" class="col-md-4 control-label">Ügyfél:</label>
            <div class="col-md-4">
                <select class="form-control" name="partner" id="partner">
                    <option value="0" selected="selected">Minden ügyfél</option>
@foreach($partners as $partner)
                    <option value="{{ $partner->partner_ID }}">{{ $partner->partner_name }}</option>
@endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="state" class="col-md-4 control-label">Állapot:</label>
            <div class="col-md-4">
                <select class="form-control" name="state" id="state">
                    <option value="Active" >Aktív</option>
                    <option value="Retired">Inaktív</option>
                </select>
            </div>
        </div>
        <div class="centre">
                <button type="submit" class="btn btn-warning">Rögzít</button>
        </div>
    </form>

@stop
