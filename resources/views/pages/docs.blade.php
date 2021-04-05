 @extends('layouts.default')
@section('content')
<h2>Dokumentációk</h2><br>
<div class="ticket-group">
	<table class="partner-row">    
		<tr class="partner-header">                
            <td >Fájl neve</td>        
            <td >Leírás</td>
            <td >Ügyfél</td>
		</tr>
		@foreach($docs as $doc)
                <tr class="clickable-row" data-href="/downloadFile/{{ $doc->ID }}">
                    <td class="col-partner-1">{{ $doc->origname }}</td>        
                    <td class="">{{ $doc->upload_comment }}</td>
                    <td class="">{{ $doc->partner_name }}</td>
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