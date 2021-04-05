@extends('layouts.default')
@section('content')
    <h2 style="text-align: center; margin-top: 10px;">Új hibajegy</h2><br>
    <form name="ticket-form" id="ticket-form" class="form-horizontal" role="form" method="POST" onsubmit="rogzit.disabled = true; return true;" action="{{ url('/addTicket') }}">
        {{ csrf_field() }}
        <div class="form-group" >  
@if ($user_type<>'partner')
            <label class="col-md-3 control-label" for="partner_id" >Ügyfél: </label>
            <div class="col-md-4 ">
                <select class="form-control" name="partner_id" id="partner_id">
                    <option value="0" > Válasszon! </option> 
                    @foreach($partners as $partner)
                        @if($partner->partner_ID == old('partner_id'))
                            <option value="{{ $partner->partner_ID }}" selected>{{ $partner->partner_name }}</option>
                        @else
                            <option value="{{ $partner->partner_ID }}">{{ $partner->partner_name }}</option>                            
                        @endif
                    @endforeach
                </select>
            </div>          
@endif
        </div>
        <div class="form-group" >            
            <label for="topic" class="col-md-3 control-label" >Tevékenységi kör: </label>
            <div class="col-md-4 ">
                <select class="form-control" name="topic" id="topic">
                    <option value="IT-szerviz" selected>IT-szerviz</option>
                    <option value="Printer-szerviz" >Printer-szerviz</option>
                </select>
            </div>         
        </div>
        <div class="form-group{{ $errors->has('ticket-title') ? ' has-error' : '' }}">
            <label for="ticket-title" class="col-md-3 control-label">Bejelentés tárgya:</label>
    
            <div class="col-md-8">
                <input id="ticket-title" type="text" class="form-control" name="ticket-title" value="{{ old('ticket-title') }}" autofocus>
    
                @if ($errors->has('title'))
                    <span class="help-block">
                        <strong>{{ $errors->first('ticket-title') }}</strong>
                    </span>
                @endif
            </div>
        </div>
            
        <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
            <label for="content" class="col-md-3 control-label">Hiba leírása:</label>
    
            <div class="col-md-8">
                <textarea id="content" class="form-control" name="content" rows="7">{{ old('content') }}</textarea>
    
                @if ($errors->has('content'))
                    <span class="help-block">
                        <strong>{{ $errors->first('content') }}</strong>
                    </span>
                @endif
            </div>
        </div>
            
        <div class="form-group" >            
            <label for="priority" class="col-md-3 control-label" >Különösen sürgős: </label>
            <div class="col-md-2 checkbox" style="margin-left: 25px;">
                    <input type="checkbox" name="priority" value="Sürgős">
            </div>         
        </div>

        <div class="centre" style="width: 150px; margin-top: 10px;">
                <button type="button" class="btn btn-warning" style="width: 150px" data-toggle="modal" data-target="#uploadModal">Fájl csatolása</button>
        </div>
            
@if ($user_type=='partner')
        <input name="source" id="source" type="hidden" value="web">
        <input name="partner_id" type="hidden" value="{{ $partner_ID }}">
@else
        <br>
        <div class="form-group" >            
            <label for="source" class="col-md-3 control-label" >Felelős: </label>
            <div class="col-md-4 ">
                <select class="form-control" name="owner" id="owner">
            @foreach($users as $user) 
                    <option value="{{ $user->id }}" >{{ $user->name ." ". $user->firstname }} </option>
            @endforeach
                </select>
            </div>         
        </div>
        <br>
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
        <div class="centre">
 <!--               <button name="rogzit" type="submit" class="btn btn-warning">Rögzít</button> -->
            <button name="rogzit" type="button" class="btn btn-warning clicked_rogzit">Rögzít</button>
        </div>
<!-- The Upload Modal -->
        <div id="uploadModal" class="modal fade">
            <div class="modal-dialog">
<!-- Modal content -->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h2 class="modal-title">Adatok mentése</h2>
                    </div>
                    <div class="modal-body form-group" >
                        <br>
           
                        <label>Feltöltés előtt menteni kell a hibajegyet!</label>
                        <br><br>
                        <div class="confirm">
                            <button type="button" class="btn btn-default clicked" data-toggle="modal" data-target="#uploadModal">Elment</button>
                            <button class="btn btn-default" data-dismiss="modal">Elhagy</button>
                        </div>            
                    </div>
                </div>
            </div>
        </div>
<!-- End Modal -->        
        <input name="upload" id="upload" type="hidden" value="false">
    </form>

<script>
$('.clicked').click(function() {
    document.getElementById('upload').value="true";        
    document.getElementById('ticket-form').submit();    
});

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