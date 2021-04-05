@extends('layouts.default')
@section('content')
    <h2>Ügyfelek</h2><br>
        <div class="ticket-group">
			<table class="partner-row">    
				<tr class="partner-header">                
	                <td >Ügyfél neve</td>        
	                <td >Hibajegyek</td>
	                <td >Felelős</td>
				</tr>
@foreach($partners as $partner)
<?php
    $responsible = DB::table('responsible')->where('responsible.partner_ID',$partner->partner_ID)->join('users','users.id','responsible.user_ID')->first();
?>
                <tr class="clickable-row" data-href="editPartner/{{ $partner->partner_ID }}">
                    <td class="col-partner-1">{{ $partner->partner_name }}</td>
				@if($open_partners->contains('partner_name',$partner->partner_name))		
                    <td class="col-partner-2"><label class="badge">{{ $open_partners->where('partner_name',$partner->partner_name)->first()->open_tickets }}</label></td>
				@else
                    <td class="col-partner-2"><label class="badge">0</label></td>
				@endif
                    <td class="">
@if ($responsible)
            {{ $responsible->name." ". $responsible->firstname }}
@endif
                    </td>
                </tr>
@endforeach
			</table>
		</div>
<script>
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });

</script>
@stop