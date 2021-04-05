@extends('layouts.default')
@section('content')
    <h2>Felhasználók</h2><br>
     <br>
        <div class="ticket-group">
			<table class="partner-row">
                <thead>
                    <tr class="partner-header">                
                        <td >Név</td>        
                        <td >E-mail cím</td>
                        <td >Típus</td>
                    </tr>
                </thead>
                <tbody>
@foreach($users as $user)
                    <tr class="clickable-row pointer" data-href="editUser/{{ $user->id }}">
                        <td >{{ $user->name." ".$user->firstname }}</td>        
                        <td >{{ $user->email }}</td>
                        <td >
            @if($user->user_type == 'partner')  
                Ügyfél
            @endif
            @if($user->user_type == 'std_user')  
                Dolgozó
            @endif
            @if($user->user_type == 'admin')  
                Admin
            @endif
            @if($user->user_type == 'ext_user')  
                Külsős
            @endif
                        </td>
                    </tr>                
@endforeach
                </tbody>
			</table>
		</div>
<script>
//user kiválasztása
$(".clickable-row").click(function() {
    window.location = $(this).data("href");
});
</script>    
@stop
