@extends('layouts.default')
@section('content')
    <h2>Adat lekérés</h2><br>
   <form class="form-horizontal" role="form" method="POST" action="{{ url('/dataList') }}">
        {{ csrf_field() }}
    
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
            <label for="user" class="col-md-4 control-label">Munkatárs:</label>
            <div class="col-md-4">
                <select class="form-control" name="user" id="user">
                    <option value="0" selected="selected">Minden dolgozó</option>
@foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name." ".$user->firstname }}</option>
@endforeach
                </select>
            </div>
        </div>
					    
        <div class="form-group">
            <label for="start-date" class="col-md-4 control-label">Intervallum kezdete:</label>
    
            <div class="col-md-4">
                <input id="start_date" type="text" class="form-control date" name="start_date" required>
            </div>
        </div>

        <div class="form-group">
            <label for="end_date" class="col-md-4 control-label">Intervallum vége:</label>
    
            <div class="col-md-4">
                <input id="end_date" type="text" class="form-control date" name="end_date" required>
            </div>
        </div>

        <div class="centre">
                <button type="submit" class="btn btn-warning">Lekér</button>
        </div>
    </form>

<script type="text/javascript">
    $('.date').datepicker({  
       format: 'yyyy-mm-dd'
     });  
</script>  
		
@stop