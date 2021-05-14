<?php

namespace Ticket\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Ticket\Http\Requests;
use Ticket\Http\Controllers\Controller;
use Mail;
// use App\Mail\NewTicket;

class TicketController extends Controller
{
    public function index(){
        $user_id = Auth::user()->id;
        $user_type = Auth::user()->user_type;
        $topic = session('topic');// topic értékének beolvasása
        $selected_partner_ID = session('partner');
        $selected_user_ID = session('user');
        $selected_state = session('ticket_state');
        $nowdays = date('Y-m-d',strtotime('-1 day'));
        if ($user_type == 'partner') {
                session(['open_tickets' => DB::table('tickets')
                                        ->where([['ticket_state','<>','Lezárt' ],['ticket_state','<>','Archive'],['partner_ID',$selected_partner_ID],['task_type','hibajegy']])->count()]);
                session(['closed_tickets' => DB::table('tickets')
                                        ->where([['ticket_state','=','Lezárt' ],['partner_ID',$selected_partner_ID],['task_type','hibajegy']])->count()]);
                session(['last_activities' => DB::table('logs')->where([['partner_ID', $selected_partner_ID],['type','public']])
                                                                ->orderBy('created_at','desc')
                                                                ->take(5)->get()]);                        
                session(['number_of_activities' => DB::table('logs')->where([['partner_ID', $selected_partner_ID],['type','public'],['created_at','>=',$nowdays]])
                                                                ->count()]);                        
                session(['open_tasks' => DB::table('tickets')
                                        ->where([['ticket_state','<>','Lezárt' ],['partner_ID',$selected_partner_ID],['task_type','feladat']])->count()]);
                session(['closed_tasks' => DB::table('tickets')
                                        ->where([['ticket_state','=','Lezárt' ],['partner_ID',$selected_partner_ID],['task_type','feladat']])->count()]);
                $responsible_ID = DB::table('responsible')->where('partner_ID',$selected_partner_ID)->first();
                $responsible = DB::table('users')->where('id',$responsible_ID->user_ID)->first();
                session(['responsible' => $responsible->name . " " . $responsible->firstname]);
                if ($selected_state == 'Nyitott') {                     
                    $tickets = DB::table('tickets')
                            ->join('partners','tickets.partner_ID','partners.partner_ID')
                            ->join('users','tickets.owner','users.id')
                            ->where([['ticket_state','<>','Lezárt' ],['tickets.partner_ID',$selected_partner_ID]])
                            ->latest('created')->get();
                }
                else {
                    if ($selected_state == 'Lezárt') {                     
                        $tickets = DB::table('tickets')
                            ->join('partners','tickets.partner_ID','partners.partner_ID')
                            ->join('users','tickets.owner','users.id')
                            ->where([['ticket_state','Lezárt'],['tickets.partner_ID',$selected_partner_ID]])
                            ->latest('created')->get();
                    }
                    else {                        
                        $tickets = DB::table('tickets')
                            ->join('partners','tickets.partner_ID','partners.partner_ID')
                            ->join('users','tickets.owner','users.id')
                            ->where('tickets.partner_ID',$selected_partner_ID)
                            ->latest('created')->get();
                    }
                }            
                $users = DB::table('users')->where('id',$user_id)->orderby('name')->get();
                $partner = DB::table('partners')->where('partner_ID',$selected_partner_ID)->orderby('partner_name')->first();
                session(['partner_name' => $partner->partner_name ]);                
                return view('pages.tickets',['tickets'=> $tickets])
                    ->with('users',$users)
                    ->with('partner_ID',$selected_partner_ID)
                    ->with('user_ID',$selected_user_ID)
                    ->with('state',$selected_state)
                    ->with('user_type',$user_type)
                    ->with('topic',$topic);
        }
        elseif ($user_type == 'ext_user') {           
                $partner = DB::table('ext_user_rights')->where('user_ID',$user_id)->first();
                $selected_partner_ID = $partner ->partner_ID;
                session(['partner' => $partner->partner_ID]);
                
                session(['open_tickets' => DB::table('tickets')
                                        ->where([['ticket_state','<>','Lezárt' ],['ticket_state','<>','Archive'],['partner_ID',$selected_partner_ID],['task_type','hibajegy']])->count()]);
                session(['closed_tickets' => DB::table('tickets')
                                        ->where([['ticket_state','=','Lezárt' ],['partner_ID',$selected_partner_ID],['task_type','hibajegy']])->count()]);
                session(['last_activities' => DB::table('logs')->where([['partner_ID', $selected_partner_ID],['type','public']])
                                                                ->orderBy('created_at','desc')
                                                                ->take(5)->get()]);                        
                session(['number_of_activities' => DB::table('logs')->where([['partner_ID', $selected_partner_ID],['type','public'],['created_at','>=',$nowdays]])
                                                                ->count()]);                        
                session(['open_tasks' => DB::table('tickets')
                                        ->where([['ticket_state','<>','Lezárt' ],['partner_ID',$selected_partner_ID],['task_type','feladat']])->count()]);
                session(['closed_tasks' => DB::table('tickets')
                                        ->where([['ticket_state','=','Lezárt' ],['partner_ID',$selected_partner_ID],['task_type','feladat']])->count()]);
                $responsible_ID = DB::table('responsible')->where('partner_ID',$selected_partner_ID)->first();
                $responsible = DB::table('users')->where('id',$responsible_ID->user_ID)->first();
                session(['responsible' => $responsible->name . " " . $responsible->firstname]);
                if ($selected_state == 'Nyitott') {                     
                    $tickets = DB::table('tickets')
                            ->join('partners','tickets.partner_ID','partners.partner_ID')
                            ->join('users','tickets.owner','users.id')
                            ->where([['ticket_state','<>','Lezárt' ],['tickets.partner_ID',$selected_partner_ID]])
                            ->latest('created')->get();
                }
                else {
                    if ($selected_state == 'Lezárt') {                     
                        $tickets = DB::table('tickets')
                            ->join('partners','tickets.partner_ID','partners.partner_ID')
                            ->join('users','tickets.owner','users.id')
                            ->where([['ticket_state','Lezárt'],['tickets.partner_ID',$selected_partner_ID]])
                            ->latest('created')->get();
                    }
                    else {                        
                        $tickets = DB::table('tickets')
                            ->join('partners','tickets.partner_ID','partners.partner_ID')
                            ->join('users','tickets.owner','users.id')
                            ->where('tickets.partner_ID',$selected_partner_ID)
                            ->latest('created')->get();
                    }
                }            
                $users = DB::table('users')->where('id',$user_id)->orderby('name')->get();
                $partner = DB::table('partners')->where('partner_ID',$selected_partner_ID)->orderby('partner_name')->first();
                session(['partner_name' => $partner->partner_name ]);
                return view('pages.tickets',['tickets'=> $tickets])
                    ->with('users',$users)
                    ->with('partner_ID',$selected_partner_ID)
                    ->with('user_ID',$selected_user_ID)
                    ->with('state',$selected_state)
                    ->with('user_type',$user_type)
                    ->with('topic',$topic);            
        }
        else {
            if ($selected_partner_ID <> 0) {
                if ($selected_user_ID <> 0) {
                    if ($selected_state == 'Nyitott') {
                        if ($topic <> 'Minden csoport') {
                            $tickets = DB::table('tickets')
                                            ->join('partners','tickets.partner_ID','partners.partner_ID')
                                            ->join('users','tickets.owner','users.id')
                                            ->where([['ticket_state','<>', 'Lezárt'],
                                                     ['tickets.partner_ID', $selected_partner_ID],
                                                     ['topic', $topic],
                                                     ['owner', $selected_user_ID]])->latest('created')->get();
                        }
                        else {                            
                            $tickets = DB::table('tickets')
                                            ->join('partners','tickets.partner_ID','partners.partner_ID')
                                            ->join('users','tickets.owner','users.id')
                                            ->where([['ticket_state','<>', 'Lezárt'],
                                                     ['tickets.partner_ID', $selected_partner_ID],
                                                     ['owner', $selected_user_ID]])->latest('created')->get();
                        }
                    }
                    else {
                        if ($selected_state == 'Lezárt') {
                            if ($topic <> 'Minden csoport') {
                                $tickets = DB::table('tickets')
                                            ->join('partners','tickets.partner_ID','partners.partner_ID')
                                            ->join('users','tickets.owner','users.id')
                                            ->where([['ticket_state', 'Lezárt'],
                                                     ['tickets.partner_ID', $selected_partner_ID],
                                                     ['topic', $topic],
                                                     ['owner', $selected_user_ID]])->latest('created')->get();
                            }
                            else {                            
                                $tickets = DB::table('tickets')
                                            ->join('partners','tickets.partner_ID','partners.partner_ID')
                                            ->join('users','tickets.owner','users.id')
                                            ->where([['ticket_state', 'Lezárt'],
                                                     ['tickets.partner_ID', $selected_partner_ID],
                                                     ['owner', $selected_user_ID]])->latest('created')->get();
                            }
                        }
                        else {
                            if ($topic <> 'Minden csoport') {
                                $tickets = DB::table('tickets')
                                            ->join('partners','tickets.partner_ID','partners.partner_ID')
                                            ->join('users','tickets.owner','users.id')
                                            ->where([['tickets.partner_ID', $selected_partner_ID],
                                                     ['topic', $topic],
                                                     ['owner', $selected_user_ID]])->latest('created')->get();
                            }
                            else {                            
                                $tickets = DB::table('tickets')
                                            ->join('partners','tickets.partner_ID','partners.partner_ID')
                                            ->join('users','tickets.owner','users.id')
                                            ->where([['tickets.partner_ID', $selected_partner_ID],
                                                     ['owner', $selected_user_ID]])->latest('created')->get();
                            }                            
                        }
                    }
                }
                else {
                    if ($selected_state == 'Nyitott')
                        if ($topic <> 'Minden csoport') {
                            $tickets = DB::table('tickets')
                                            ->join('partners','tickets.partner_ID','partners.partner_ID')
                                            ->join('users','tickets.owner','users.id')
                                            ->where([['ticket_state','<>', 'Lezárt'],
                                                     ['topic', $topic],
                                                     ['tickets.partner_ID', $selected_partner_ID]])->latest('created')->get();
                        }
                        else {                            
                            $tickets = DB::table('tickets')
                                            ->join('partners','tickets.partner_ID','partners.partner_ID')
                                            ->join('users','tickets.owner','users.id')
                                            ->where([['ticket_state','<>', 'Lezárt'],
                                                     ['tickets.partner_ID', $selected_partner_ID]])->latest('created')->get();
                        }
                    else {
                        if ($selected_state == 'Lezárt') {
                            if ($topic <> 'Minden csoport') {
                                $tickets = DB::table('tickets')
                                            ->join('partners','tickets.partner_ID','partners.partner_ID')
                                            ->join('users','tickets.owner','users.id')
                                            ->where([['ticket_state', 'Lezárt'],
                                                     ['topic', $topic],
                                                     ['tickets.partner_ID', $selected_partner_ID]])->latest('created')->get();
                            }
                            else {                           
                                $tickets = DB::table('tickets')
                                            ->join('partners','tickets.partner_ID','partners.partner_ID')
                                            ->join('users','tickets.owner','users.id')
                                            ->where([['ticket_state', 'Lezárt'],
                                                     ['tickets.partner_ID', $selected_partner_ID]])->latest('created')->get();
                            }
                        }
                        else {                            
                            if ($topic <> 'Minden csoport') {
                                $tickets = DB::table('tickets')
                                            ->join('partners','tickets.partner_ID','partners.partner_ID')
                                            ->join('users','tickets.owner','users.id')
                                            ->where([['topic', $topic],
                                                     ['tickets.partner_ID', $selected_partner_ID]])->latest('created')->get();
                            }
                            else {                           
                                $tickets = DB::table('tickets')
                                            ->join('partners','tickets.partner_ID','partners.partner_ID')
                                            ->join('users','tickets.owner','users.id')
                                            ->where('tickets.partner_ID', $selected_partner_ID)->latest('created')->get();
                            }
                        }
                    }
                }
            }
            else {
                if ($selected_user_ID <> 0) {
                    if ($selected_state == 'Nyitott')
                        if ($topic <> 'Minden csoport') {
                            $tickets = DB::table('tickets')
                                            ->join('partners','tickets.partner_ID','partners.partner_ID')
                                            ->join('users','tickets.owner','users.id')
                                            ->where([['ticket_state','<>', 'Lezárt'],
                                                     ['topic', $topic],
                                                     ['owner', $selected_user_ID]])->latest('created')->get();
                        }
                        else {
                            $tickets = DB::table('tickets')
                                            ->join('partners','tickets.partner_ID','partners.partner_ID')
                                            ->join('users','tickets.owner','users.id')
                                            ->where([['ticket_state','<>', 'Lezárt'],
                                                     ['owner', $selected_user_ID]])->latest('created')->get();
                        }
                    else {
                        if ($selected_state == 'Lezárt') {
                            if ($topic <> 'Minden csoport') {
                                $tickets = DB::table('tickets')
                                            ->join('partners','tickets.partner_ID','partners.partner_ID')
                                            ->join('users','tickets.owner','users.id')
                                            ->where([['ticket_state', 'Lezárt'],
                                                     ['topic', $topic],
                                                     ['owner', $selected_user_ID]])->latest('created')->get();
                            }
                            else {                            
                                $tickets = DB::table('tickets')
                                            ->join('partners','tickets.partner_ID','partners.partner_ID')
                                            ->join('users','tickets.owner','users.id')
                                            ->where([['ticket_state', 'Lezárt'],
                                                     ['owner', $selected_user_ID]])->latest('created')->get();
                            }
                        }
                        else {                            
                            if ($topic <> 'Minden csoport') {
                                $tickets = DB::table('tickets')
                                            ->join('partners','tickets.partner_ID','partners.partner_ID')
                                            ->join('users','tickets.owner','users.id')
                                            ->where([['topic', $topic],
                                                     ['owner', $selected_user_ID]])->latest('created')->get();
                            }
                            else {                            
                                $tickets = DB::table('tickets')
                                            ->join('partners','tickets.partner_ID','partners.partner_ID')
                                            ->join('users','tickets.owner','users.id')
                                            ->where('owner', $selected_user_ID)->latest('created')->get();
                            }
                        }
                    }                    
                }
                else {
                    if ($selected_state == 'Nyitott') {
                        if ($topic <> 'Minden csoport') {
                            $tickets = DB::table('tickets')
                                            ->join('partners','tickets.partner_ID','partners.partner_ID')
                                            ->join('users','tickets.owner','users.id')
                                            ->where([['ticket_state','<>', 'Lezárt'],
                                                     ['topic', $topic]])->latest('created')->get();
                        }
                        else {                            
                            $tickets = DB::table('tickets')
                                            ->join('partners','tickets.partner_ID','partners.partner_ID')
                                            ->join('users','tickets.owner','users.id')
                                            ->where('ticket_state','<>', 'Lezárt')->latest('created')->get();
                        }
                    }    
                    else {                        
                        if ($selected_state == 'Lezárt') {
                            if ($topic <> 'Minden csoport') {
                                $tickets = DB::table('tickets')
                                            ->join('partners','tickets.partner_ID','partners.partner_ID')
                                            ->join('users','tickets.owner','users.id')
                                            ->where([['ticket_state', 'Lezárt'],
                                                     ['topic', $topic]])->latest('created')->get();
                            }
                            else {
                                $tickets = DB::table('tickets')
                                            ->join('partners','tickets.partner_ID','partners.partner_ID')
                                            ->join('users','tickets.owner','users.id')
                                            ->where('ticket_state', 'Lezárt')->latest('created')->get();
                            }
                        }
                        else {
                            if ($topic <> 'Minden csoport') {
                                $tickets = DB::table('tickets')
                                            ->join('partners','tickets.partner_ID','partners.partner_ID')
                                            ->join('users','tickets.owner','users.id')
                                            ->where('topic', $topic)->latest('created')->get();
                            }
                            else {
                                $tickets = DB::table('tickets')
                                            ->join('partners','tickets.partner_ID','partners.partner_ID')
                                            ->join('users','tickets.owner','users.id')
                                            ->latest('created')->get();
                            }                            
                        }
                    }
                }
            }                    
            $partners = DB::table('partners')->where('partner_state','active')->orderby('partner_name')->get();
            $users = DB::table('users')->where([['state','Active'],['user_type','<>','partner']])->orderby('name')->get();        
        }

        return view('pages.tickets',['tickets'=> $tickets])
                    ->with('partners',$partners)
                    ->with('users',$users)
                    ->with('partner_ID',$selected_partner_ID)
                    ->with('user_ID',$selected_user_ID)
                    ->with('state',$selected_state)
                    ->with('user_type',$user_type)
                    ->with('topic',$topic);
    }
  
    public function newTicket(){
        $user_id = Auth::user()->id;
        $user_type = Auth::user()->user_type;
        if ($user_type == 'ext_user') {
            $partner = DB::table('ext_user_rights')->where('user_ID',$user_id)->first();
            $partner_ID = $partner->partner_ID;
        }
        else {
            $partner_ID = Auth::user()->partner_ID;
        }
        if ($user_type == 'partner' || $user_type == 'ext_user') {
            $partners = DB::table('partners')->where('partner_state','active')->where('partner_ID',$partner_ID)->get();            
        }
        else {
            $partners = DB::table('partners')->where('partner_state','active')->orderby('partner_name')->get();
        }
        $users = DB::table('users')->where('user_type','<>','partner')->where('state','Active')->get();
        return view('pages.newTicket',['partners' => $partners])->with('user_type',$user_type)
                                                                ->with('users',$users)
                                                                ->with('partner_ID',$partner_ID);
    }

    public function addTicket(Request $request){
        $this->validate($request, [
            'ticket-title' => 'required|max:127',
            'content' => 'required|min:5|max:32000',
        ]);
        $user_id = Auth::user()->id;
        $priority="Normál";
        if ($request->priority == "Sürgős") {
            $priority="Sürgős";
        }
            
            $id = DB::table('tickets')->insertGetId([
                'partner_ID' => $request->input('partner_id'),
                'created_by' => $user_id,
                'title' => $request->input('ticket-title'),
                'content' => $request->input('content'),
                'source' => $request->input('source'),
                'owner' => $request->owner,
                'topic' => $request->input('topic'),
                'priority' => $priority,
            ]);
// a felelős részére email küldés, ha nem ő rögzítette a hibajegyet (ha van felelős)
        if ($request->owner > 1) {
            $owners_email = DB::table('users')->select('email')->where('id', $request->owner)->first();
        }
        else {
            $owner = DB::table('responsible')->where('partner_ID',$request->partner_id)->select('user_ID')->first();            
            if ($owner) {
                if ($owner->user_ID <> $user_id) {
                    $owners_email = DB::table('users')->select('email')->where('id', $owner->user_ID)->first();
                }
            }            
        }
        if (isset($owners_email)) {            
			$subject = "Új hibajegy - #".$id;

            //lapukornel 2021.05.11 - Levélbe link beszúrása
            $ticket_url = redirect()->to('/editTicket/'.$id)->getTargetUrl();
            Mail::queue('emails.newTicketMailURL', ['title' => '#'.$id . ' számú hibajegy rögzítésre került', 'content' => $request->input('ticket-title'), 'ticket_url' => $ticket_url ], function ($message) use ($owners_email, $id)
            {
                $message->to($owners_email->email);
                $message->subject("Új hibajegy - #".$id);
            });
        }
// log írás
        $IP = $_SERVER['REMOTE_ADDR'];
        $msg = "Új hibajegy";
        $id1 = DB::table('logs')->insertGetId([
            'user_ID' => $user_id,
            'ticket_ID' => $id,
            'partner_ID' => $request->partner_id,
            'action' => $msg,
            'IP' => $IP]);
        if ($request->upload == "true") {
            $attachments = DB::table('uploads')->where('connected_ticket',$id)->get();
            return view('pages.upload',['ticket_ID'=>$id])->with('attachments',$attachments);
        }
        else {
//            return redirect('/');
            return redirect('/editTicket/'.$id);
        }
    }
    
    public function editTicket($ticket_ID){        
        $ticket = DB::table('tickets')->join('partners','tickets.partner_ID','partners.partner_ID')->where('ticket_ID',$ticket_ID)->get()->first();
        $owner_id = $ticket->owner;
        $created_by_id = $ticket->created_by;
        $owner = DB::table('users')->where('id', $owner_id)->get()->first();
        $name = $owner->name;
        $firstname = $owner->firstname;
        $owner = $name ." ". $firstname; 
        $user = DB::table('users')->where('id', $created_by_id)->get()->first();
        $created_by = $user->name ." ". $user->firstname;
        $user_type = Auth::user()->user_type;
        if ($user_type == 'partner') {
            $comments = DB::table('comments')->join('users','comments.user_ID','users.id')->where([['ticket_ID', $ticket_ID],['comment_type','<>','private']])->orderBy('created')->get();
        }
        else {
            $comments = DB::table('comments')->join('users','comments.user_ID','users.id')->where('ticket_ID', $ticket_ID)->orderBy('created')->get();
        }
        $attachments = DB::table('uploads')->where('connected_ticket',$ticket_ID)->get();
        if ($ticket->task_type == 'feladat') {
            $task_type = "Feladat";
        }
        else {
            $task_type = "Hibajegy";
        }
        $hours = intval($ticket->in_worktime/60);
        $minutes = $ticket->in_worktime - $hours*60;
        $hours2 = intval($ticket->after_worktime/60);
        $minutes2 = $ticket->after_worktime - $hours2*60;
        return view('pages.editTicket',['ticket'=> $ticket])->with('comments',$comments)
                                                            ->with('owner',$owner)
                                                            ->with('owner_ID',$owner_id)
                                                            ->with('created_by',$created_by)
                                                            ->with('user_type',$user_type)
                                                            ->with('task_type',$task_type)
                                                            ->with('hours',$hours)
                                                            ->with('minutes',$minutes)
                                                            ->with('hours2',$hours2)
                                                            ->with('minutes2',$minutes2)
                                                            ->with('attachments',$attachments);
    }
    
    public function modifyTicket($ticket_ID){        
        $user_id = Auth::user()->id;
        $user_type = Auth::user()->user_type;        
        $users = DB::table('users')->where([['user_type','<>','partner'],['state','Active']])->get();
        $ticket = DB::table('tickets')->where('ticket_ID',$ticket_ID)->get()->first();
        $partner = DB::table('partners')->where('partner_ID',$ticket->partner_ID)->get()->first();
        return view('pages.modifyTicket',['ticket'=> $ticket])
                                    ->with('user_type',$user_type)
                                    ->with('partner', $partner)
                                    ->with('users',$users);
    }

    public function updateTicket(Request $request){

        DB::table('tickets')->where('ticket_ID',$request->ticket_ID)
                    ->update(['topic'=>$request->topic,
                             'priority'=>$request->priority,
                             'deadline'=>$request->deadline,
                             'source'=>$request->source,
                             'owner'=>$request->owner,
                             'modified'=>date('Y-m-d H:i:s')]);
        $ticket = DB::table('tickets')->where('ticket_ID',$request->ticket_ID)->first();
// Email küldés, ha változik a felelős
        if ($request->previous_owner <> $request->owner) {
            $user = DB::table('users')->where('id',$ticket->owner)->first();
            $reply_address = $user->email;
			$subject = "A #".$request->ticket_ID ." sorszámú hibajegy / feladat neked lett kiosztva - IT Szerviz HelpDesk";
            $content = "A #".$request->ticket_ID . " számú hibajegy / feladat neked lett kiosztva."; 

            //lapukornel 2021.05.11 - Levélbe link beszúrása
            $ticket_url = redirect()->to('/editTicket/'.$request->ticket_ID)->getTargetUrl();
            Mail::queue('emails.newTicketMailURL', ['title' => "A feladat tárgya: ".$ticket->title, "content" => $content, 'ticket_url' => $ticket_url], function ($message) use ($reply_address,$subject) {
								$message->to($reply_address);
								$message->subject($subject);
			});
            
        }
// log írás
        $IP = $_SERVER['REMOTE_ADDR'];
        $msg = "Hibajegy alapadatok módosítása";
        $id1 = DB::table('logs')->insertGetId([
            'user_ID' => Auth::user()->id,
            'ticket_ID' => $request->ticket_ID,
            'partner_ID' => $ticket->partner_ID,
            'action' => $msg,
            'IP' => $IP]);
        return $this->editTicket($request->ticket_ID);
    }
    
    public function closeTicket(Request $request){
//        echo $request->ticket_ID . " számú ticket lezárva";
        $in_worktime = $request->hours*60 + $request->minutes;
        $after_worktime = $request->hours2*60 + $request->minutes2;
        DB::table('tickets')->where('ticket_ID',$request->ticket_ID)
                ->update(['ticket_state'=>'Lezárt',
                          'in_worktime'=>$in_worktime,
                          'after_worktime'=>$after_worktime,
                          'modified'=>date('Y-m-d H:i:s')]);
        $user_id = Auth::user()->id;
        $comment_type =  "public";
        $ticket_ID = $request->ticket_ID;
        if (isset($request->comment)) {
            $comment = "A bejelentés tárgya: ".$request->ticket_title."<br/><br/>".$request->comment."<br/><br/>";
        }
        else {
            $comment = "Tárgy: ".$request->ticket_title."<br/><br/>";
        }
        $id = DB::table('comments')->insertGetId([
                'comment' => $comment,
                'user_ID' => $user_id,
                'ticket_ID' => $request->input('ticket_ID'),
                'comment_type' => $comment_type,
        ]);
// ---  Levél küldés a bejelentőnek  ---
        if ($request->input('reply_address') != null) {
            $reply_address = $request->input('reply_address');
            $ticket_ID = $request->ticket_ID;
			$subject = "Értesítés a ".$ticket_ID ." sorszámú hibajegy / feladat lezárásól - IT Szerviz HelpDesk";
            $content = $comment."A #".$ticket_ID . " számú hibajegyet / feladatot lezártuk. További információ információ megtekintése érdekében, amennyiben rendelkezik jogosultsággal,
            ide kattintva léphet be hibajegy és feladatkezelő rendszerünkbe:<br> https://ticket.negypolus.hu <br/><br/>Üdvözlettel, <br/>IT Szerviz HelpDesk";

			Mail::queue('emails.newTicketMail', ['title' => "Tisztelt Ügyfelünk!", "content" => $content], function ($message) use ($reply_address,$subject) {
								$message->to($reply_address);
                                $message->bcc("hibajegy_ell@itszerviz.hu");
								$message->subject($subject);
			});
		}
// ---  Levél küldés a kapcsolattartónak  ---
        $kapcsolattarto = DB::table('users')->where('partner_ID',$request->partner_ID)->where('closeticketmail',1)->first();
        if (isset($kapcsolattarto)) {
            $reply_address = $kapcsolattarto->email;
            $ticket_ID = $request->ticket_ID;
			$subject = "Értesítés a ".$ticket_ID ." sorszámú hibajegy / feladat lezárásól - IT Szerviz HelpDesk";
            $content = $comment."A #".$ticket_ID . " számú hibajegyet / feladatot lezártuk. További információ információ megtekintése érdekében, amennyiben rendelkezik jogosultsággal,
            ide kattintva léphet be hibajegy és feladatkezelő rendszerünkbe:<br> https://ticket.negypolus.hu <br/><br/>Üdvözlettel, <br/>IT Szerviz HelpDesk";

			Mail::queue('emails.newTicketMail', ['title' => "Tisztelt Ügyfelünk!", "content" => $content], function ($message) use ($reply_address,$subject) {
								$message->to($reply_address);
 								$message->subject($subject);
			});
        }

// log írás
        $ticket = DB::table('tickets')->where('ticket_ID',$request->ticket_ID)->first();        
        $IP = $_SERVER['REMOTE_ADDR'];
        $msg = "Hibajegy lezárás";
        $id1 = DB::table('logs')->insertGetId([
            'user_ID' => $user_id,
            'ticket_ID' => $request->input('ticket_ID'),
            'partner_ID' => $ticket->partner_ID,
            'action' => $msg,
            'IP' => $IP]);
        return $this->index();
    }

    public function openTicket($ticket_ID){                
        DB::table('tickets')->where('ticket_ID',$ticket_ID)->update(['ticket_state'=>'Folyamatban']);
// log írás
        $ticket = DB::table('tickets')->where('ticket_ID',$request->ticket_ID)->first();        
        $IP = $_SERVER['REMOTE_ADDR'];
        $msg = "Hibajegy újranyitás";
        $id1 = DB::table('logs')->insertGetId([
            'user_ID' => Auth::user()->id,
            'ticket_ID' => $ticket_ID,
            'partner_ID' => $ticket->partner_ID,
            'action' => $msg,
            'IP' => $IP]);
        return $this->index();
    }

    public function putTask($ticket_ID) {
        DB::table('tickets')->where('ticket_ID',$ticket_ID)->update(['task_type'=>'feladat', 'modified'=>date('Y-m-d H:i:s')]);
// log írás
        $ticket = DB::table('tickets')->where('ticket_ID',$ticket_ID)->first();        
        $IP = $_SERVER['REMOTE_ADDR'];
        $msg = "Hibajegy átrakva feladatnak";
        $id1 = DB::table('logs')->insertGetId([
            'user_ID' => Auth::user()->id,
            'ticket_ID' => $ticket_ID,
            'partner_ID' => $ticket->partner_ID,
            'action' => $msg,
            'IP' => $IP]);        
        return redirect('/');
    }
    
    public function archiving($ticket_ID) {
        DB::table('tickets')->where('ticket_ID',$ticket_ID)->update(['ticket_state'=>'Archive', 'modified'=>date('Y-m-d H:i:s')]);
        $ticket = DB::table('tickets')->where('ticket_ID',$ticket_ID)->select('partner_ID')->first();
// log írás
        $ticket = DB::table('tickets')->where('ticket_ID',$ticket_ID)->first();        
        $IP = $_SERVER['REMOTE_ADDR'];
        $msg = "Hibajegy archiválás";
        $id1 = DB::table('logs')->insertGetId([
            'user_ID' => Auth::user()->id,
            'ticket_ID' => $ticket_ID,
            'partner_ID' => $ticket->partner_ID,
            'action' => $msg,
            'IP' => $IP]);
        return redirect('/');      
//        return $this->index();
    }
    
    public function addComment(Request $request){
        $user_id = Auth::user()->id;
        $comment_type = $request->comment_type;
        $id = DB::table('comments')->insertGetId([
                'comment' => $request->input('comment'),
                'user_ID' => $user_id,
                'ticket_ID' => $request->input('ticket_ID'),
                'comment_type' => $comment_type,
        ]);        
        DB::table('tickets')->where('ticket_ID', $request->input('ticket_ID'))->update(['ticket_state'=>'Folyamatban', 'modified'=>date('Y-m-d H:i:s')]);
 
    //  Sending email to partner     
        if ($request->comment_type == 'email') {
            if ($request->email) {
                if ( strpos($request->email,',') !== false) {
					$partners_email = explode(",",$request->email);
				}
				else $partners_email = $request->email;
//				$owners_email = ["j@negypolus.hu","jozsi@negypolus.hu"];
                $t_ID = $request->ticket_ID;
                Mail::queue('emails.newTicketMail', ['title' => 'A #'.$t_ID . ' számú hibajegyhez hozzászóltak', 'content' => $request->comment], function ($message) use ($partners_email, $t_ID)
                {
                    $message->to($partners_email);
                    $message->subject('A #'.$t_ID . ' számú hibajegyhez hozzászóltak');
                });
            }
        }
    //  Sending email to owners    
        if (Auth::user()->user_type == 'partner') {
            $owner = DB::table('users')->where('id',$request->owner)->select('email')->first();
            $owners_email = $owner->email;
            $t_ID = $request->ticket_ID;
    
            //lapukornel 2021.05.11 - Levélbe link beszúrása
            $ticket_url = redirect()->to('/editTicket/'.$id)->getTargetUrl();
            Mail::queue('emails.newTicketMailURL', ['title' => 'A #'.$t_ID . ' számú hibajegyhez hozzászóltak', 'content' => $request->comment, 'ticket_url' => $ticket_url], function ($message) use ($owners_email, $t_ID)
            {
                $message->to($owners_email);
                $message->subject('A #'.$t_ID . ' számú hibajegyhez hozzászóltak');
            });
        }
        
    // log írás
        $ticket = DB::table('tickets')->where('ticket_ID',$request->ticket_ID)->first();        
        $IP = $_SERVER['REMOTE_ADDR'];
        if ($request->comment_type == 'email') {
            $msg = "Új hozzászólás - email";
        }
        else {
            $msg = "Új hozzászólás";
        }    
        $id1 = DB::table('logs')->insertGetId([
            'user_ID' => $user_id,
            'ticket_ID' => $request->ticket_ID,
            'action' => $msg,
            'partner_ID' => $ticket->partner_ID,
            'IP' => $IP,
            'type' => $comment_type]);
        return $this->index();
    }
    
    public function takeTicket(Request $request) {
        DB::table('tickets')->where('ticket_ID',$request->ticket_ID)
                    ->update(['owner'=>$request->owner,
                             'modified'=>date('Y-m-d H:i:s')]);

// log írás
        $ticket = DB::table('tickets')->where('ticket_ID',$request->ticket_ID)->first();        
        $IP = $_SERVER['REMOTE_ADDR'];
        $msg = "Hibajegy tulajdonos módosítása";
        $id1 = DB::table('logs')->insertGetId([
            'user_ID' => Auth::user()->id,
            'ticket_ID' => $request->ticket_ID,
            'partner_ID' => $ticket->partner_ID,
            'action' => $msg,
            'IP' => $IP]);
        return $this->editTicket($request->ticket_ID);        
    }
    
    public function searchTicket(Request $request) {
        $search = '%'.$request->search.'%';
        $user_type = Auth::user()->user_type;
        if ($user_type=='partner') {
            $partner_ID = Auth::user()->partner_ID;
            $tickets = DB::table('tickets')->select('tickets.ticket_ID','tickets.created','name','firstname','title','modified')
                                        ->join('partners','tickets.partner_ID','partners.partner_ID')
                                        ->join('users','tickets.owner','=','users.id')
                                        ->leftjoin('comments','tickets.ticket_ID','=','comments.ticket_ID')
                                        ->where([['tickets.partner_ID',$partner_ID],['title','like',$search]])
                                        ->orWhere([['tickets.partner_ID',$partner_ID],['tickets.ticket_ID','like',$search]])
                                        ->orWhere([['tickets.partner_ID',$partner_ID],['content','like',$search]])
                                        ->orWhere([['tickets.partner_ID',$partner_ID],['comments.comment','like',$search]])
                                        ->orderby('tickets.ticket_ID')->distinct()->get();
            
        }
        else {
            $tickets = DB::table('tickets')->select('tickets.ticket_ID','tickets.created','name','firstname','title','modified','partner_name')
                                        ->join('partners','tickets.partner_ID','partners.partner_ID')
                                        ->join('users','tickets.owner','=','users.id')
                                        ->leftjoin('comments','tickets.ticket_ID','=','comments.ticket_ID')
                                        ->where('title','like',$search)
                                        ->orWhere('tickets.ticket_ID','like',$search)
                                        ->orWhere('content','like',$search)
                                        ->orWhere('comments.comment','like',$search)
                                        ->orderby('tickets.ticket_ID')->distinct()->get();
        }
        return view('pages.result',['tickets'=> $tickets])->with('user_type',$user_type);   
    }
    
    public function moveData(){
        $kezd=1;
        $sourceArray = array("Web"=>"web",
                             "Phone"=>"telefon",
                             "Email"=>"e-mail",
                             "Other"=>"egyéb");
        $statusArray = array("open"=>"Új feladat",
                             "closed"=>"Lezárt",
                             "new"=>"Új feladat",
                             "archive"=>"Archive");
        $userArray = array(0=>1,
                           1=>1,
                           2=>12,
                           3=>11,
                           4=>1,
                           5=>1,
                           6=>1,
                           7=>1,
                           8=>1,
                           9=>1,
                           10=>1,
                           11=>1,
                           11=>1,
                           13=>1,
                           14=>1,
                           15=>1,
                           16=>1,
                           17=>1,
                           18=>1,
                           19=>1,
                           20=>1,
                           21=>1,
                           22=>1,
                           23=>1,
                           24=>1,
                           25=>1,
                           26=>1,
                           27=>1,
                           28=>1,
                           29=>1,
                           30=>1,
                           31=>1,
                           32=>1,
                           33=>1,
                           34=>1,
                           35=>1,
                           36=>1,
                           37=>5,
                           38=>5,
                           39=>8,
                           40=>1,
                           41=>1,
                           42=>1,
                           43=>2,
                           44=>6,    
                           );
        $partnerArray = array(5=>	7,
68=>	8,
7=>	    9,
9=>	    10,
11=>	11,
12=>	12,
13=>	13,
16=>	14,
17=>	15,
18=>	16,
20=>	17,
22=>	18,
24=>	19,
25=>	1,
28=>	20,
29=>	21,
30=>	22,
32=>	23,
33=>	24,
37=>	25,
38=>	26,
39=>	27,
40=>	6,
65=>	28,
42=>	29,
43=>	30,
44=>	31,
46=>	32,
64=>	3,
63=>	33,
180=>	17,
184=>	35,
185=>	35,
186=>	36,
188=>	37,
189=>	34,
193=>	4,
195=>	35,
196=>	5,
        );
        do {
            
            $oldtickets =  DB::table('ost_ticket')->whereBetween('ticket_id', array($kezd,$kezd+19))->get();
            foreach ($oldtickets as $oldticket) {
                
                if (array_key_exists( $oldticket->ugyfel, $partnerArray) and ($oldticket->status <> 'archived')){
                    $partner_ID = $partnerArray[$oldticket->ugyfel];
                    if (array_key_exists( $oldticket->felvitte, $userArray)) {
                        $created_by = $userArray[$oldticket->felvitte];
                    }
                    else {
                        $created_by = 1;
                    }
                    if (array_key_exists( $oldticket->source, $sourceArray)) {
                        $source = $sourceArray[$oldticket->source];
                    }
                    else {
                        $source = 'egyéb';
                    }
                    if (array_key_exists( $oldticket->staff_id, $userArray)) {
                        $owner = $userArray[$oldticket->staff_id];
                    }
                    else {
                        $owner = 1;
                    }
                    $modified = $oldticket->lasttouch;
                    $state = $statusArray[$oldticket->status];
                    if ($oldticket->description) {
                        $content = $oldticket->description;
                    }
                    else {
                        $content = $oldticket->subject;
                    }
                    $topic = "IT-szerviz";
                    if ($oldticket->staff_id == 39) {
                        $topic = "Printer-szerviz";
                    }
                    echo ($oldticket->ticket_id ." ". $oldticket->subject ." ");
                    echo ($source." ");
                    echo ("owner:".$owner." ");
                    echo ($state." ");
                    echo "<br>";
                    
                    $id = DB::table('tickets')->insertGetId([
                        'partner_ID' => $partner_ID,
                        'created_by' => $created_by,
                        'title' => $oldticket->subject,
                        'content' => $content,
                        'source' => $source,
                        'owner' => $owner,
                        'modified' => $modified,
                        'ticket_state' => $state,
                        'priority' => 'Normál',
                        'created' => $oldticket->created,
                        'topic' => $topic,
                    ]);
                    // comments tábla feltöltése comment_type: public
                    $comments = DB::table('ost_ticket_response')->where('ticket_id',$oldticket->ticket_id)->get();
                    foreach ($comments as $comment) {
                        if (array_key_exists( $comment->staff_id, $userArray)) {
                            $user_ID = $userArray[$comment->staff_id];
                        }
                        else {
                            $user_ID = 1;
                        }
                        $cpub_id = DB::table('comments')->insertGetId([
                            'ticket_ID' => $id,
                            'comment' => $comment->response,
                            'user_ID' => $user_ID,
                            'comment_type' => 'public',
                            'created' => $comment->created,
                        ]);
                    }
                    // comments tábla feltöltése comment_type: privte
                    $comments = DB::table('ost_ticket_note')->where('ticket_id',$oldticket->ticket_id)->get();
                    foreach ($comments as $comment) {
                        if (array_key_exists( $comment->staff_id, $userArray)) {
                            $user_ID = $userArray[$comment->staff_id];
                        }
                        else {
                            $user_ID = 1;
                        }
                        $cpriv_id = DB::table('comments')->insertGetId([
                            'ticket_ID' => $id,
                            'comment' => $comment->note,
                            'user_ID' => $user_ID,
                            'comment_type' => 'private',
                            'created' => $comment->created,
                        ]);
                    }
                }    
            }
            $kezd = $kezd+20;
        } while ($kezd < 4400); 
    }
    
    public function freshEvents() {
        $nowdays = date('Y-m-d',strtotime('-1 day'));        
        $selected_partner_ID = session('partner');
        $events = DB::table('logs')->where([['partner_ID', $selected_partner_ID],['type','public'],['created_at','>=',$nowdays]])->get();
        return view('pages.fresh_events',['events'=> $events]);   
    }   
}
