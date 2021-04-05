<column id="column-left" class="col-sm-2">
    
      <div class="list-group" id="sidemenu-container">
        <div  class="list-group-item list-group-item-category">Hibajegyek</div>
        <div class="category-group">
            <a href="#" class="list-group-item child-category-item" onclick="set_param('Nyitott')"></i>Nyitott <span class="color-orange"> ({{ session('open_tickets') }}) </span></a>
        
            <a href="#" class="list-group-item child-category-item" onclick="set_param('Lezárt')"></i>Lezárt <span class="color-orange"> ({{ session('closed_tickets') }}) </span></a>
        
            <a href="#" class="list-group-item child-category-item" onclick="set_param('Minden')"></i>Összes <span class= " color-orange"> ({{ session('closed_tickets')+session('open_tickets') }}) </span></a>
        </div>

        <div  class="list-group-item list-group-item-category">Feladatok</div>

        <div class="category-group">
	
            <a href="#" class="list-group-item child-category-item" onclick="set_task_param('Nyitott')"></i>Folyamatban <span class= " color-orange"> ({{ session('open_tasks') }}) </span></a>
        
            <a href="#" class="list-group-item child-category-item" onclick="set_task_param('Lezárt')"></i>Befejezett <span class= " color-orange"> ({{ session('closed_tasks') }}) </span></a>
        
            <a href="#" class="list-group-item child-category-item" onclick="set_task_param('Minden')"></i>Összes <span class= " color-orange"> ({{ session('open_tasks')+session('closed_tasks') }}) </span></a>        
        </div>

<!--        <div  class="list-group-item list-group-item-category">Értesítések</div>
        <div class="category-group" style="display:none;">
            <a href="#" class="list-group-item child-category-item"></i>Olvasatlan <span class= " color-orange"> (1) </span></a>
        
            <a href="#" class="list-group-item child-category-item"></i>olvasott <span class= " color-orange"> (10) </span></a>
        
            <a href="#" class="list-group-item child-category-item"></i>Összes <span class= " color-orange"> (11) </span></a>        
        </div>
-->
        <div  class="list-group-item list-group-item-category"><a href="/timedTasks">Ütemezett feladatok</a></div>
        <div  class="list-group-item list-group-item-category"><a href="/settings">Beállítások</a></div>
		
@if (Auth::user()->user_type == "std_user")
<!--		<div  class="list-group-item list-group-item-category"><a href="/napiLista">Napi feladatlista</a></div>  -->
@endif
@if (Auth::user()->user_type == "std_user")
<!--		<div  class="list-group-item list-group-item-category"><a href="/napiLista">Napi feladatlista</a></div>  -->
		<div  class="list-group-item list-group-item-category"><a href="/partners">Ügyfelek</a></div>
		<div  class="list-group-item list-group-item-category"><a href="/users">Felhasználók</a></div>
@endif
@if (Auth::user()->user_type == "admin")      
<!--		<div  class="list-group-item list-group-item-category"><a href="/napiLista">Napi feladatlista</a></div>  -->
		<div  class="list-group-item list-group-item-category"><a href="/partners">Ügyfelek</a></div>
		<div  class="list-group-item list-group-item-category"><a href="/users">Felhasználók</a></div>
		<div  class="list-group-item list-group-item-category"><a href="/newPartner">Új ügyfél felvitele</a></div>
		<div  class="list-group-item list-group-item-category"><a href="/newUser">Új felhasználó</a></div>
		<div  class="list-group-item list-group-item-category"><a href="/statisztika">Adatok lekérése</a></div>
			
@endif
	</div>
		
	<form name="tickets_menu" id="tickets_menu" action="{{url('/setParam')}}" method="post">
		<input type='hidden' name="ticket_state" id="ticket_state" value=" ">
		<input type='hidden' name="task_type" id="task_type" value=" ">
        {{ csrf_field() }}
	</form>
<script>
	function set_param(param) {
		document.getElementById('ticket_state').value = param;
		document.getElementById('task_type').value = 'hibajegy';
		document.getElementById('tickets_menu').submit();
	}
	
	function set_task_param(param) {
		document.getElementById('ticket_state').value = param;
		document.getElementById('task_type').value = 'feladat';
		document.getElementById('tickets_menu').submit();
	}

	$(".list-group-item-category").click(function(){
		$(this).next(".category-group").slideToggle();
	});	
</script>

</column>

