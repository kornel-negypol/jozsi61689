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
@if($data['partner'] == $partner->partner_name)
                    <option value="{{ $partner->partner_ID }}" selected>{{ $partner->partner_name }}</option>
@else
				<option value="{{ $partner->partner_ID }}">{{ $partner->partner_name }}</option>
@endif
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
@if($data['user'] == $user->name." ".$user->firstname)
                    <option value="{{ $user->id }}" selected>{{ $user->name." ".$user->firstname }}</option>
@else
                    <option value="{{ $user->id }}">{{ $user->name." ".$user->firstname }}</option>
@endif
@endforeach
                </select>
            </div>
        </div>
					    
        <div class="form-group">
            <label for="start-date" class="col-md-4 control-label">Intervallum kezdete:</label>
    
            <div class="col-md-4">
                <input id="start_date" type="text" class="form-control date" name="start_date" value="{{$data['start_date']}}" required>
            </div>
        </div>

        <div class="form-group">
            <label for="end_date" class="col-md-4 control-label">Intervallum vége:</label>
    
            <div class="col-md-4">
                <input id="end_date" type="text" class="form-control date" name="end_date" value="{{$data['end_date']}}" required>
            </div>
        </div>

        <div class="centre">
                <button type="submit" class="btn btn-warning">Új lekérés</button>
        </div>
    </form>
<!-- Eredmény kiíratása -->
	<br>
	<form class="form-horizontal" role="form" method="POST">
	     <div class="form-group">
			<label for="line1" class="col-md-4 control-label">Összes munkaóra:</label>   
	          <div class="col-md-4">
	               <input id="line1" type="text" class="form-control" name="line1" value="{{ $data['hours']." óra ".$data['minutes']." perc" }}">
	          </div>
	     </div>
	     <div class="form-group">
			<label for="line2" class="col-md-4 control-label">Munkaidőben:</label>   
	          <div class="col-md-4">
	               <input id="line2" type="text" class="form-control" name="line2" value="{{ $data['worktime_hours']." óra ".$data['worktime_minutes']." perc" }}">
	          </div>
	     </div>
	     <div class="form-group">
			<label for="line3" class="col-md-4 control-label">Munkaidőn kívül:</label>   
	          <div class="col-md-4">
	               <input id="line3" type="text" class="form-control" name="line3" value="{{ $data['out_hours']." óra ".$data['out_minutes']." perc" }}">
	          </div>
	     </div>
	     <div class="form-group">
			<label for="line4" class="col-md-4 control-label">Új hibajegyek:</label>   
	          <div class="col-md-4">
	               <input id="line4" type="text" class="form-control" name="line4" value="{{ $data['new_tickets']." db" }}">
	          </div>
	     </div>
	     <div class="form-group">
			<label for="line5" class="col-md-4 control-label">Hibajegy lezárás:</label>   
	          <div class="col-md-4">
	               <input id="line5" type="text" class="form-control" name="line5" value="{{ $data['closed_tickets']." db" }}">
	          </div>
	     </div>
	</form>

<script type="text/javascript">
    $('.date').datepicker({  
       format: 'yyyy-mm-dd'
     });  
</script>  

@stop