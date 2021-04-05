@extends('layouts.default')
@section('content')
    <h2>Felhasználó szerkesztése</h2><br>
@if($user_type <> "admin")
	<style>
		.hide-button {
			display: none;
		}
	</style>
@endif
    <form class="form-horizontal" role="form" method="post" action="{{ url('/updateUser') }}">
        {{ csrf_field() }}
         <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <label for="name" class="col-md-4 control-label">Vezetéknév:</label>
    
            <div class="col-md-6">
                <input id="name" type="text" class="form-control" name="name" value="{{ $user->name }}" <?php if ($user_type <> 'admin') { echo ' readonly';}?>>
    
                @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    
        <input name="user_id" id="user_id" type="hidden" value="{{ $user->id }}"></input>
        <div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
            <label for="firstname" class="col-md-4 control-label">Keresztnév:</label>
    
            <div class="col-md-6">
                <input id="firstname" type="text" class="form-control" name="firstname" value="{{ $user->firstname }}" <?php if ($user_type <> 'admin') { echo ' readonly';}?> required>
    
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
                <input id="email" type="email" class="form-control" name="email" value="{{ $user->email }}" <?php if ($user_type <> 'admin') { echo ' readonly';}?> required>
    
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    
        <div class="form-group">
            <label for="user_type" class="col-md-4 control-label">Felhasználó típusa:</label>
            <div class="col-md-4">
                <select class="form-control" name="user_type" id="user_type" <?php if ($user_type <> 'admin') { echo ' disabled';}?>>
@if ($user->user_type == 'partner')                
                    <option value="partner" selected>Vendég</option>
@else
                    <option value="partner">Vendég</option>
@endif
@if ($user->user_type == 'std_user')     
                    <option value="std_user" selected>Dolgozó</option>
@else
                    <option value="std_user">Dolgozó</option>
@endif
@if ($user->user_type == 'ext_user')     
                    <option value="ext_user" selected>Külsős</option>
@else
                    <option value="ext_user">Külsős</option>
@endif
@if ($user->user_type == 'admin')                
                    <option value="admin" selected>Adminisztrátor</option>
@else
                    <option value="admin">Adminisztrátor</option>
@endif
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="partner" class="col-md-4 control-label">Ügyfél:</label>
            <div class="col-md-4">
                <select class="form-control" name="partner" id="partner" <?php if ($user_type <> 'admin') { echo ' disabled';}?>>
@if($user->partner_ID > 0)
                    <option value="0">Válasszon!</option>
@foreach($partners as $partner)
                    <option value="{{ $partner->partner_ID }}" <?php if ($user->partner_ID == $partner->partner_ID) {echo 'selected="selected"';}?>>{{ $partner->partner_name }}</option>
@endforeach
@else    
                    <option value="0" selected="selected">Válasszon</option>
@foreach($partners as $partner)
                    <option value="{{ $partner->partner_ID }}">{{ $partner->partner_name }}</option>
@endforeach
@endif
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="state" class="col-md-4 control-label">Állapot:</label>
            <div class="col-md-4">
                <select class="form-control" name="state" id="state"  <?php if ($user_type <> 'admin') { echo ' disabled';}?>>
@if ($user->state == 'Active')                
                    <option value="Active" selected>Aktív</option>
                    <option value="Retired">Inaktív</option>
@else
                    <option value="Active">Aktív</option>
                    <option value="Retired" selected>Inaktív</option>
@endif
                </select>
            </div>
        </div>
        <div class="centre">
                <button type="submit" class="btn btn-warning hide-button">Elment</button>
        </div>
    </form>
    <hr />
<!-- Jelszó módosítás -->
    <h2>Jelszó módosítás</h2><br>
    <form class="form-horizontal" role="form" method="POST" action="{{ url('/resetPasswd') }}">
        <input name="user_id" id="user_id" type="hidden" value="{{ $user->id }}"></input>
        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password" class="col-md-4 control-label">Jelszó:</label>    
            <div class="col-md-6">
                <input id="password" type="password" class="form-control" name="password"  <?php if ($user_type <> 'admin') { echo ' readonly';}?>>   
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
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation"  <?php if ($user_type <> 'admin') { echo ' readonly';}?>>
            </div>
        </div>
        <div class="centre">
                <button type="submit" class="btn btn-warning hide-button">Elment</button>
        </div>
        {{ csrf_field() }}
    </form>
    <hr />
<!-- E-mail beállítások -->
    <h2>E-mail értesítések</h2><br>
    <form class="form-horizontal" role="form" method="POST" action="{{ url('/emailSet') }}">
        <input name="user_ID" id="user_ID" type="hidden" value="{{ $user->id }}"></input>
    
        <div class="form-group">
            <label for="newticketmail" class="col-md-4 control-label">Hibajegy állapot változás:</label>
            <div class="col-md-1">
                <input id="newticketmail" type="checkbox" class="form-control" name="newticketmail" <?php if ($user->newticketmail == 1) {echo 'checked="true"';}?>  <?php if ($user_type <> 'admin') { echo ' disabled';}?>>
            </div>
        </div>
        <div class="form-group">
            <label for="closeticketmail" class="col-md-4 control-label">Hibajegy lezárás:</label>
            <div class="col-md-1">
                <input id="closeticketmail" type="checkbox" class="form-control" name="closeticketmail" <?php if ($user->closeticketmail == 1) {echo 'checked="true"';}?>  <?php if ($user_type <> 'admin') { echo ' disabled';}?>> 
            </div>
        </div>
            
        <div class="centre">
                <button type="submit" class="btn btn-warning hide-button">Elment</button>
        </div>
        {{ csrf_field() }}
    </form>    
@stop
