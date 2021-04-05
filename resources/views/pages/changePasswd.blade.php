@extends('layouts.default')
@section('content')
    <h2>Jelszó csere</h2><br>
    <form class="form-horizontal" role="form" method="POST" action="{{ url('/updatePasswd') }}">
        <div class="form-group{{ $errors->has('oldpasswd') ? ' has-error' : '' }}">
            <label for="oldpasswd" class="col-md-4 control-label">Régi jelszó:</label>    
            <div class="col-md-6">
                <input id="oldpasswd" type="password" class="form-control" name="oldpasswd" >   
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('oldpasswd') }}</strong>
                    </span>
                @endif
            </div>
        </div>
            
        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password" class="col-md-4 control-label">Új jelszó:</label>    
            <div class="col-md-6">
                <input id="password" type="password" class="form-control" name="password" >   
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
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" >
            </div>
        </div>
        <div class="centre">
                <button type="submit" class="btn btn-warning">Mentés</button>
        </div>
        {{ csrf_field() }}
    </form>
@stop
