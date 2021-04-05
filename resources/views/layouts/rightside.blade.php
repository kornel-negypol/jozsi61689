<div id="column-right" class="col-sm-2">
    <div class="name-block" id="name-block">
        {{ Auth::user()->name }} {{ Auth::user()->firstname }}
        <input type="hidden" id="userid" value= "{{ Auth::user()->id }}">
    </div>
<?php
    $user_type = Auth::user()->user_type
?>
@if($user_type=="partner")
    
    @if( strpos($_SERVER['REQUEST_URI'],"editTicket") < 1)
        <div class="ceg-block">
            <li><span>CÉGNÉV:</span></li>
            <li>{{ session('partner_name') }}</li>
            <li><span>Az Ön kapcsolattartója:</span></li>
            <li>{{ session('responsible') }}</li>
        </div>
        <div class="activity-block">
            <li class="block-header">Utolsó aktivitások:</li>
        @foreach(session('last_activities') as $activity)
            <li><span>{{ $activity->created_at }}</span></li>
            <li>{{ $activity->action }}</li>
            <br>
        @endforeach
        </div>
    @endif

@else
    <!-- ------ Hibajegyek, feladatok ------ -->
  @if($_SERVER['REQUEST_URI']=="/" || $_SERVER['REQUEST_URI']=="/closeTicket")    
    <div class="ceg-block">
      <form name="tickets_select" id="tickets_select" action="{{url('setTopic')}}" method="post">
      @if($user_type != "ext_user")
        <div class="">
            <select class="form-control input-sm" name="topic" id="topic"  style="font-size: 14px">
                <option value="Minden csoport" <?php if( $topic == 'Minden csoport' ){echo("selected");} ?>>Minden csoport</option>
                <option value="IT-szerviz" <?php if( $topic == 'IT-szerviz' ){echo("selected");} ?>>IT-szerviz</option>
                <option value="Printer-szerviz" <?php if( $topic == 'Printer-szerviz' ){echo("selected");} ?>>Printer-szerviz</option>
            </select>
        </div>
        <div class="">
            <select class="form-control input-sm" name="partner" id="partner"  style="font-size: 14px">
                <option value="0" <?php if( $partner_ID == 0 ){echo("selected");} ?>>Minden ügyfél</option>
                 
          @foreach($partners as $partner)
                <option value="{{ $partner->partner_ID }}" <?php if($partner_ID == $partner->partner_ID){echo("selected");} ?> >{{ $partner->partner_name }}</option>
          @endforeach

            </select>
        </div>
      @else
        <div class="">
            <select class="form-control input-sm" name="partner" id="partner"  style="font-size: 14px">
                 
                <option value="{{ $partner_ID }}" selected>{{ session("partner_name") }}</option>

            </select>
        </div>         
      @endif      
        <div class="">
            <select class="form-control input-sm" name="user" id="user" style="font-size: 14px">
                <option value="0" <?php if( $user_ID == 0 ){echo("selected");} ?> >Minden felhasználó</option>
        @foreach($users as $user)
                <option value="{{ $user->id }}" <?php if($user_ID == $user->id){echo("selected");} ?> >{{ $user->name . " ". $user->firstname }}</option>
        @endforeach
            </select>
        </div>         
        {{ csrf_field() }}
      </form>
    </div>
  @endif  
@endif
<!-- Ticket szerkesztés-->
@if(substr($_SERVER['REQUEST_URI'],0, 12)=="/editTicket/" || substr($_SERVER['REQUEST_URI'],0, 13)=="/updateTicket" || substr($_SERVER['REQUEST_URI'],0, 11)=="/takeTicket")

    <div class="take-block" id="take-block">
        <form name="take_form" id="take_form" class="" role="form" method="post" action="{{ url('/takeTicket/') }}">
            {{ csrf_field() }}
            Átveszem
            <input type="hidden" name="owner" value= "{{ Auth::user()->id }}">
            <input type="hidden" name="ticket_ID" value="{{ $ticket->ticket_ID }}">
        </form>
    </div>
    
    <div class="ticket-block">
        <div class="mark" ><label class=" ">{{ $task_type }}: {{ $ticket->ticket_ID }}</label></div>
        <label class=" ">Ügyfél:</label>
        <div class="mark">{{$ticket->partner_name }} </div>   
        <label class="" >Tevékenységi kör: </label>
        <div class="mark" name="topic">{{$ticket->topic }}</div>         
        <label class=" control-label" >Bejelentés módja: </label>
        <div class="mark">{{$ticket->source }}</div>         
        <label class=" control-label" >Bejelentés ideje: </label>
        <div class="mark">{{$ticket->created }}</div>         
        <label class=" control-label" >Prioritás: </label>
        <div class="mark">{{$ticket->priority }}</div>         
        <label class=" control-label" >Állapot: </label>
        <div class="mark">{{$ticket->ticket_state }}</div>         
        <label class=" control-label" >Felelős: </label>
        <div class="mark">{{$owner }}</div>         
        <label class=" control-label" >A hibajegyet rözítette: </label>
        <div class="mark">{{$created_by }}</div>         
    </div>
    <form name="ticket-form" class="" role="form" action="{{ url('/modifyTicket/'.$ticket->ticket_ID) }}">
        {{ csrf_field() }}
            <div>
                <button type="submit" class="btn btn-warning btn-long" >{{ $task_type }} módosítás</button>
            </div>
    </form>
    
    @if($user_type <> 'partner')
        @if(($ticket->ticket_state <> "Lezárt") && ($owner_ID > 1))
            <div>
                <button type="button" class="btn btn-success btn-long" id="closeTicket" data-toggle="modal" data-target="#myModal">{{ $task_type }} lezárása</button>
            </div>
        @endif
        @if($user_type == 'admin')
            <div>
                <a type="button" style="text-decoration: none; color: gray;" class="btn btn-default btn-long" id="change_type" href="{{ '/putTask/'.$ticket->ticket_ID }}">Feladatnak átrakás</a>
            </div>
        @endif
        <div>
            <a type="button" style="text-decoration: none; color: white;" class="btn btn-info btn-long" id="worksheet" href="{{ '/workSheet/'.$ticket->ticket_ID }}">Munkalap készítése</a>
        </div>
    @endif
    <div>
        <a type="button" style="text-decoration: none; color: white;" class="btn btn-primary btn-long" id="upload" href="{{ '/upload/'.$ticket->ticket_ID }}">Fájl feltöltés</a>
    </div>
    @if($user_type == "admin")        
        <div>
            <a type="button" style="text-decoration: none; color: white;" class="btn btn-danger btn-long" id="archive" href="{{ '/archiving/'.$ticket->ticket_ID }}">Archiválás</a>
        </div>
    @endif
    <br>
@endif

<!-------- Ütemezett feladat szerkesztése ------->
@if(substr($_SERVER['REQUEST_URI'],0, 15)=="/editTimedTask/")
        <div>
            <br><br>
            <a type="button" style="text-decoration: none; color: white;" class="btn btn-danger btn-long" id="deletetask" href="{{ '/deleteTimedTask/'.$task->task_ID }}">Feladat törlése</a>
        </div>    
@endif

<!-------- Felhasználók -------->
@if($_SERVER['REQUEST_URI']=="/users" or $_SERVER['REQUEST_URI']=="/filteredUsers")
  {!! Form::open(['url' => 'filteredUsers', 'id' => 'controlls']) !!}
    <div class="ceg-block">
            <div class='mark'><label for="user_type" class="control-label">Felhasználó típusa: </label></div>           
            <select  class="form-control input-sm" name="user_type" id="user_type" style="font-size: 14px">
                    <option value="all" <?php if($user_type == 'all'){echo("selected");}?> >Mindenki</option>
                    <option value="partner" <?php if($user_type == 'partner'){echo("selected");}?> >Ügyfél</option>
                    <option value="std_user" <?php if($user_type == 'std_user'){echo("selected");}?> >Dolgozó</option>
                    <option value="ext_user" <?php if($user_type == 'ext_user'){echo("selected");}?> >Külsős</option>
                    <option value="admin" <?php if($user_type == 'admin'){echo("selected");}?> >Admin</option>
            </select>       
            <div class='mark'><label for="state" class=" control-label">Állapot: </label></div>          
            <select class="form-control input-sm" name="state" id="state" style="font-size: 14px">
                    <option value="Active" <?php if($state == 'Active'){echo("selected");}?> >Aktív</option>
                    <option value="Retired" <?php if($state == 'Retired'){echo("selected");}?> >Inaktív</option>
            </select>         
    </div>
  </form>
  <form  name="edit" id="edit" action="{{url('editUser')}}" method="post">
    <input type="hidden" name="user_id" id="user_id" value=" "></input> 
    {!!csrf_field()!!}
  </form>
@endif
<!--    Adatok lekérése    -->
@if($_SERVER['REQUEST_URI']=="/statisztika" || $_SERVER['REQUEST_URI']=="/dataList" || $_SERVER['REQUEST_URI'] == "/senderList")
    <div>
        <a type="button" style="text-decoration: none; color: gray;" class="btn btn-default btn-long" id="senderList" href="{{ '/senderList' }}">Beküldő szerint</a>
    </div>
    
@endif
</div>
<script>
// saját ticketek
$(".name-block").click(function() {
    document.getElementById('user').value = document.getElementById('userid').value;
    document.getElementById('tickets_select').submit();
//    alert(document.getElementById('user').value);
//   window.location = $(this).data("href");
});

$("#tickets_select :input").change(function() {
    document.getElementById('tickets_select').submit();    
});
// Ticket átvétel
$(".take-block").click(function() {
    document.getElementById('take_form').submit();    
});
// Felhasználó választás
$("#partner_select :input").change(function() {
    document.getElementById('partner_select').submit();    
});
// Felhasználó típus választás
$("#user_type").on("input", function() {
    document.getElementById('controlls').submit();
});
// Felhasználó állapot választás
$("#state").on("input", function() {
//    alert("Change to " + this.value);
    document.getElementById('controlls').submit();
});
//  Ügyfél választás ellenörzése
function check_partner() {
    if (document.getElementById('partner').value != 0) {
        location.href = '/uploadDoc'
    }
    else {
        alert('Válasszon ügyfelet!');
    }
}
</script>
