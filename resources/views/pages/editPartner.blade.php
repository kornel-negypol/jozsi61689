@extends('layouts.default')
@section('content')
    <h2>Ügyfél adatlap</h2><br>
@foreach($partners as $partner)
@if($user_type == 'admin')
    <form class="form-horizontal" role="form" method="POST" action="{{ url('/updatePartner') }}">
@else	
    <form class="form-horizontal" role="form" method="POST" action="">
<style>
	.clicked {
		display: none;
	}
</style>
@endif
        {{ csrf_field() }}
    
        <div class="form-group{{ $errors->has('partner_name') ? ' has-error' : '' }}">
            <label for="partner_name" class="col-md-3 control-label">Ügyfél neve:</label>
    
            <div class="col-md-6">
                <input id="partner_name" type="text" class="form-control" name="partner_name" value="{{ $partner->partner_name }}" required readonly>
    
                @if ($errors->has('partner_name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('partner_name') }}</strong>
                    </span>
                @endif
            </div>
            <div class="" style="text-align: left">
                <a href="#" title="Szerkesztés" class="btn btn-warning clicked" name="partner_name_a"><i class="fa fa-inverse fa-pencil"></i></a>
            </div>         
        </div>

         <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
            <label for="name" class="col-md-3 control-label">Város:</label>
    
            <div class="col-md-6">
                <input id="city" type="text" class="form-control" name="city" value="{{ $partner->city }}" required readonly>
    
                @if ($errors->has('city'))
                    <span class="help-block">
                        <strong>{{ $errors->first('city') }}</strong>
                    </span>
                @endif
            </div>
            <div class="" style="text-align: left">
                <a href="#" title="Szerkesztés" class="btn btn-warning clicked" name="city_a"><i class="fa fa-inverse fa-pencil"></i></a>
            </div>         
        </div>
    
        <div class="form-group{{ $errors->has('zip_code') ? ' has-error' : '' }}">
            <label for="email" class="col-md-3 control-label">Irányító szám:</label>
    
            <div class="col-md-6">
                <input id="zip_code" type="text" class="form-control" name="zip_code" value="{{ $partner->zip_code }}" required readonly>
    
                @if ($errors->has('zip_code'))
                    <span class="help-block">
                        <strong>{{ $errors->first('zip_code') }}</strong>
                    </span>
                @endif
            </div>
            <div class="" style="text-align: left">
                <a href="#" title="Szerkesztés" class="btn btn-warning clicked" name="zip_code_a"><i class="fa fa-inverse fa-pencil"></i></a>
            </div>         
        </div>
    
        <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
            <label for="address" class="col-md-3 control-label">Telephely címe:</label>
    
            <div class="col-md-6">
                <input id="address" type="text" class="form-control" name="address" value="{{ $partner->address }}" required readonly>
    
                @if ($errors->has('address'))
                    <span class="help-block">
                        <strong>{{ $errors->first('address') }}</strong>
                    </span>
                @endif
            </div>
            <div class="" style="text-align: left">
                <a href="#" title="Szerkesztés" class="btn btn-warning clicked" name="address_a"><i class="fa fa-inverse fa-pencil"></i></a>
            </div>         
        </div>

       <div class="form-group">
            <label for="email" class="col-md-3 control-label">Hibabejelető email cím:</label>
    
            <div class="col-md-6">
                <input id="email" type="email" class="form-control" name="email" value="{{ $partner->email }}" readonly>
            </div>
            <div class="" style="text-align: left">
                <a href="#" title="Szerkesztés" class="btn btn-warning clicked" name="email_a"><i class="fa fa-inverse fa-pencil"></i></a>
            </div>         
        </div>

        <div class="form-group{{ $errors->has('comment') ? ' has-error' : '' }}">
            <label for="comment" class="col-md-3 control-label">Megjegyzés:</label>
    
            <div class="col-md-6">
                <textarea id="comment" class="form-control" name="comment" rows="4" readonly>{{ $partner->comment }}</textarea>
    
                @if ($errors->has('comment'))
                    <span class="help-block">
                        <strong>{{ $errors->first('comment') }}</strong>
                    </span>
                @endif
            </div>
            <div class="" style="text-align: left">
                <a href="#" title="Szerkesztés" class="btn btn-warning clicked" name="comment_a"><i class="fa fa-inverse fa-pencil"></i></a>
            </div>         
        </div>
        <div class="form-group" >            
            <label for="default_topic" class="col-md-3 control-label" >Tevékenységi kör: </label>
            <div class="col-md-4 ">
                <select class="form-control" name="default_topic" id="default_topic" disabled>
                    <option value="IT-szerviz" selected>IT-szerviz</option>
    @if($partner->default_topic == "Printer-szerviz")
                    <option value="Printer-szerviz" selected>Printer-szerviz</option>
    @endif
                    <option value="Printer-szerviz" >Printer-szerviz</option>
                </select>
            </div>         
    @if($user_type <> "partner")
            <div class="" style="text-align: left">
                <a href="#" title="Szerkesztés" class="btn btn-warning clicked" name="default_topic_a"><i class="fa fa-inverse fa-pencil"></i></a>
            </div>
    @endif
        </div>
<!-- Kapcsolattartók  -->
@if($contacts->count() <> 0)
@foreach($contacts as $contact)
        <div class="form-group" >            
    @if ($loop->first)
            <label for="contact1" class="col-md-3 control-label" >Kapcsolattartó(k): </label>
    @else
            <label for="contact1" class="col-md-3 control-label" > </label>
    @endif
            <div class="col-md-3 ">
                <input id="contact1" type="text" class="form-control" name="contact1" value="{{ $contact->name  ." ". $contact->firstname }}" readonly>
            </div>                         
        </div>
@endforeach
@endif
<!-- Felelősök  -->
@if($responsibles->count() <> 0)
@foreach($responsibles as $responsible)
    <div class="form-group" >            
    @if ($loop->first)
            <label for="responsible1" class="col-md-3 control-label" >Felelősök: </label>
    @else
            <label for="responsible1" class="col-md-3 control-label" > </label>
    @endif
            <div class="col-md-3">
                <input id="responsible1" type="text" class="form-control" name="responsible1" value="{{ $responsible->name ." ". $responsible->firstname }}" readonly>
            </div>                         
    @if($user_type <> "partner")
        @if ($loop->last)
            <div class="" style="text-align: left">
                <a href="#" title="Felelős törlése" class="btn btn-warning clicked" id="{{ $responsible->responsible_ID }}" name="felel_del"><i class="fa fa-inverse fa-minus"></i></a>
                <a href="#" title="Felelős hozzáadása" class="btn btn-warning clicked" name="felel"><i class="fa fa-inverse fa-plus"></i></a>
            </div>
        @else
            <div class="" style="text-align: left">
                <a href="#" title="Felelős törlése" class="btn btn-warning clicked" id="{{ $responsible->responsible_ID }}" name="felel_del"><i class="fa fa-inverse fa-minus"></i></a>
            </div>        
        @endif
    @endif
    </div>
@endforeach
@else
        <div class="form-group" >            
            <label for="responsible1" class="col-md-3 control-label" >Felelősök: </label>
            <div class="col-md-3 ">
                <input id="responsible1" type="text" class="form-control" name="responsible1" value="" readonly>
            </div>                         
    @if($user_type == "admin")
            <div class="" style="text-align: left">
                <a href="#" title="Felelős hozzáadása" class="btn btn-warning clicked"  name="felel"><i class="fa fa-inverse fa-plus"></i></a>
            </div>
    @endif
        </div>
@endif
<!-- Új felelős hozzáadása -->
        <div class="form-group" id="responsible-div" style="display: none;">            
            <label for="responsible" class="col-md-4 control-label" > </label>
            <div class="col-md-3">
                <select class="form-control" name="responsible" id="responsible">
                    <option value="0" >Válasszon!</option>
@foreach($users as $user)
                    <option value="{{ $user->id }}" >{{ $user->name.' '.$user->firstname }}</option>
@endforeach
                </select>
            </div>         
        </div>
        <br>
        <div class="centre">
@if($user_type == "admin")
                <button type="submit" class="btn btn-warning">Rögzít</button>
@endif
        </div>
        <input name="partner_ID" type="hidden" value="{{$partner->partner_ID}}">
    </form>
    <form id="contact_form" name="contact_form" role="form" method="POST" action="{{ url('/deleteContact') }}">
        <input id="delete_contact" name="delete_contact" type="hidden" value="">
        <input id="delete_responsible" name="delete_responsible" type="hidden" value="">
        <input name="partner_ID" type="hidden" value="{{$partner->partner_ID}}">
        {{ csrf_field() }}
    </form>
@endforeach
<script>
$('.clicked').click(function(){
    switch(this.name) {
        case "kapcsolat":
            $('#contact-div').show();
//            alert("kapcsolat");
            break;
        case "partner_name_a":
            $('#partner_name').prop("readonly",false);
            break;
        case "city_a":
            $('#city').prop("readonly",false);
            break;
        case "zip_code_a":
            $('#zip_code').prop("readonly",false);
            break;
        case "address_a":
            $('#address').prop("readonly",false);
            break;
        case "email_a":
            $('#email').prop("readonly",false);
            break;
        case "comment_a":
            $('#comment').prop("readonly",false);
                    break;
        case "default_topic_a":
            $('#default_topic').prop("disabled",false);
                    break;
        case "felel":
            $('#responsible-div').show();
            break;
        case "felel_del":
            document.getElementById('delete_responsible').value = this.id;
            document.getElementById('contact_form').submit();    
//            alert("felel-del"+this.id);
            break;
        case "kapcsolat_del":
            document.getElementById('delete_contact').value = this.id;
            document.getElementById('contact_form').submit();    
            break;
        default:
            alert("default");
        break;
    }
    
});
</script>

@stop