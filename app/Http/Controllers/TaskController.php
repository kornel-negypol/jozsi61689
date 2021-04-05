<?php

namespace Ticket\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Ticket\Http\Requests;
use Ticket\Http\Controllers\Controller;
use Mail;
use DateTime;

class TaskController extends Controller
{
     public function newTask(){
		$user_id = Auth::user()->id;
		$user_type = Auth::user()->user_type;
		session(['task_type' => 'feladat']);
        if ($user_type == 'ext_user') {
            $partner = DB::table('ext_user_rights')->where('user_ID',$user_id)->first();
            $partner_ID = $partner->partner_ID;
        }
        else {
            $partner_ID = Auth::user()->partner_ID;
        }
		if ($user_type == 'partner' || $user_type == 'ext_user') {
			$partners = DB::table('partners')->where('partner_state','active')->where('partner_ID', $partner_ID)->get();            
		}
		else {
			$partners = DB::table('partners')->where('partner_state','active')->orderBy('partner_name')->orderby('partner_name')->get();
		}
		return view('pages.newTask',['partners' => $partners])->with('user_type',$user_type);
    }

    public function addTask(Request $request){
        $this->validate($request, [
            'ticket-title' => 'required|max:127',
            'content' => 'required|min:5|max:32000',
        ]);
        $user_id = Auth::user()->id;
        $priority="Normál";
        $id = DB::table('tickets')->insertGetId([
               'partner_ID' => $request->input('partner_id'),
               'created_by' => $user_id,
               'title' => $request->input('ticket-title'),
               'content' => $request->input('content'),
               'source' => $request->input('source'),
               'owner' => 1,
               'priority' => $priority,
			'task_type' => 'feladat',
			'topic' => 'IT-szerviz',
        ]);
// a felelős részére email küldés, ha nem ő rögzítette a feladatot
        $owner = DB::table('responsible')->where('partner_ID',$request->partner_id)->select('user_ID')->first();
        if ($owner) {            
            $owners_email = DB::table('users')->select('email')->where('id', $owner->user_ID)->first();
            if ($owner->user_ID <> $user_id) {
                Mail::queue('emails.newTicketMail', ['title' => $id . ' számú feladat', 'content' => $request->input('ticket-title')], function ($message) use ($owners_email)
                {
                    $message->to($owners_email->email);
                    $message->subject("Új feladat");
                });
            }
        }
// log írás
        $IP = $_SERVER['REMOTE_ADDR'];
        $msg = "Új feladat $id";
        $id1 = DB::table('logs')->insertGetId([
            'user_ID' => $user_id,
            'ticket_ID' => $id,
            'partner_ID' => $request->partner_id,
            'action' => $msg,
            'IP' => $IP]);
        return redirect('/');
	}

	public function editTimedTask($task_ID){
		$user_id = Auth::user()->id;
		$user_type = Auth::user()->user_type;
		$task = DB::table('timed_tasks')->join('partners','timed_tasks.partner_ID','partners.partner_ID')
								->where('task_ID',$task_ID)->first();
		if ($user_type == 'partner') {
			$partners = DB::table('partners')->where('partner_state','active')->where('partner_ID', Auth::user()->partner_ID)->get();            
		}
		else {
			$partners = DB::table('partners')->where('partner_state','active')->where('partner_ID',$task->partner_ID)->get();
		}
		return view('pages.editTimedTask',['partners' => $partners])->with('user_type',$user_type)->with('task',$task);
	}
	
	public function timedTasks(){
		if (Auth::user()->user_type == 'partner') {
			$tasks = DB::table('timed_tasks')
                                ->join('partners','timed_tasks.partner_ID','partners.partner_ID')
						  ->where('timed_tasks.partner_ID',Auth::user()->partner_ID)
						  ->where('state','active')
                                ->orderby('created_at')->get();
		}
		else {
			$tasks = DB::table('timed_tasks')
                                ->join('partners','timed_tasks.partner_ID','partners.partner_ID')
						  ->where('state','active')
                                ->orderby('created_at')->get();
		}
		return view('pages.timedTasks',['tasks' => $tasks]);
    }

    public function timing(){
        $user_id = Auth::user()->id;
        $user_type = Auth::user()->user_type;
        if ($user_type == 'partner') {
            $partners = DB::table('partners')->where('partner_state','active')->where('contact_ID', $user_id)->get();            
        }
        else {
            $partners = DB::table('partners')->where('partner_state','active')->orderby('partner_name')->get();
        }
        return view('pages.timing',['partners' => $partners])->with('user_type',$user_type);
    }

    public function setTimer(Request $request){
        $this->validate($request, [
            'title' => 'required|max:127',
            'content' => 'required|min:5|max:510',
            'start_time' => 'required|after:today',
            'days' => 'integer',
            'weeks' => 'integer',
            'mounths' => 'integer',
        ]);
        
        $user_ID = Auth::user()->id;
        switch ($request->repeat_cycle) {
            case 'days':
                $repeat_time = $request->days; 
            case 'weeks':   
                $repeat_time = $request->weeks;
            case 'months':    
                $repeat_time = $request->months;
        }
        $id = DB::table('timed_tasks')->insertGetId(['title' => $request->title,
                                         'content' => $request->content,
                                         'partner_ID' => $request->partner_ID,
                                         'created_by' => $user_ID,
                                         'next_time' => $request->start_time,
                                         'repeat_cycle' => $request->repeat_cycle,
                                         'repeat_time' => $repeat_time,
                                         ]);
// log írás
        $IP = $_SERVER['REMOTE_ADDR'];
        $msg = $id . " számú időzített feladat bejegyzése";
        $id1 = DB::table('logs')->insertGetId([
            'user_ID' => $user_ID,
            'ticket_ID' => $id,
            'action' => $msg,
            'partner_ID' => $request->partner_ID,
            'IP' => $IP,
            'type' => 'private']);
        
		$message = "Időzített feladat bejegyezve.";		
		return view('pages.message')->with('message', $message);
    }
    
	public function updateTimer(Request $request) {
		$this->validate($request, [
			'title' => 'required|max:127',
			'content' => 'required|min:5|max:510',
			'start_time' => 'required|after:today',
			'days' => 'integer',
			'weeks' => 'integer',
			'months' => 'integer',
			]);
		$user_ID = Auth::user()->id;
		switch ($request->repeat_cycle) {
			case 'days':
				$repeat_time = $request->days;
				break;
			case 'weeks':   
				$repeat_time = $request->weeks;
				break;
			case 'months':    
				$repeat_time = $request->months;
				break;
		}
		DB::table('timed_tasks')->where('task_ID',$request->task_ID)
                ->update(['title' => $request->title,
					 'content' => $request->content,
					 'next_time' => $request->start_time,
                          'repeat_cycle' => $request->repeat_cycle,
                          'repeat_time' => $repeat_time,
					]);
// log írás
		$IP = $_SERVER['REMOTE_ADDR'];
		$msg = $request->task_ID . " számú időzített feladat módosítása";
		$id1 = DB::table('logs')->insertGetId([
			'user_ID' => $user_ID,
			'ticket_ID' => $request->task_ID,
			'action' => $msg,
			'partner_ID' => $request->partner_ID,
			'IP' => $IP,
			'type' => 'private']);
		$message = $request->task_ID . "-s számú időzített feladat módosítva.";		
		return view('pages.message')->with('message', $message);
	}

	public function activateTasks() {
		$tasks = DB::table('timed_tasks')->where('next_time','<',date('Y-m-d',strtotime('+1 day')) )->where('state','active')->get();
		foreach ($tasks as $task){
	//  Új ticket létrehozása
			$owner = DB::table('responsible')->select('user_ID')->where('partner_ID',$task->partner_ID)->first();
			$id = DB::table('tickets')->insertGetId([
				'partner_ID' => $task->partner_ID,
				'created_by' => $task->created_by,
				'title' => $task->title,
				'content' => $task->content,
				'source' => 'egyéb',
				'owner' => $owner->user_ID,
				'priority' => 'Normál',
				'task_type' => 'hibajegy'
				]); 
	//  A következő időpont kiszámítása
			switch ($task->repeat_cycle) {
				case 'days':
					$interval = 'day';
					break;
				case 'weeks':   
					$interval = 'week';
					break;
				case 'months':    
					$interval = 'month';
					break;
			}
			$interval = '+'. $task->repeat_time .' '. $interval;
			$next_time = new DateTime($task->next_time);
			$next_time->modify($interval);
			DB::table('timed_tasks')->where('task_ID',$task->task_ID)->update(['next_time' => $next_time->format('Y-m-d')]);
	//  a felelős részére email küldés, ha nem ő rögzítette a hibajegyet (ha van felelős)
			if ($owner) {            
				$owners_email = DB::table('users')->select('email')->where('id', $owner->user_ID)->first();
				$subject = "Új hibajegy - #".$id;
				Mail::queue('emails.newTicketMail', ['title' => '#'.$id . ' számú hibajegy rögzítésre került', 'content' => $task->title], function ($message) use ($owners_email, $id)
					{
					    $message->to($owners_email->email);
					    $message->subject("Új hibajegy - #".$id);
					});
			}
	//  log írás
			$IP = $_SERVER['REMOTE_ADDR'];
			$msg = "Új hibajegy (időzített feladat)";
			$id1 = DB::table('logs')->insertGetId([
				'user_ID' => 0,
				'ticket_ID' => $id,
				'partner_ID' => $task->partner_ID,
				'action' => $msg,
				'IP' => $IP]);	
		}
	}
	
	public function deleteTimedTasks($task_ID) {
		DB::table('timed_tasks')->where('task_ID',$task_ID)
                ->update(['state' => 'archive']);
	// log írás		
		$IP = $_SERVER['REMOTE_ADDR'];
		$task = DB::table('timed_tasks')->where('task_ID',$task_ID)->first();
		$msg = $task_ID . " számú időzített feladat törlése.";
		$id1 = DB::table('logs')->insertGetId([
			'user_ID' => Auth::user()->id,
			'ticket_ID' => $task_ID,
			'action' => $msg,
			'partner_ID' => $task->partner_ID,
			'IP' => $IP,
			'type' => 'private']);
		$message = $task_ID . "-s számú időzített feladat törölve.";		
		return view('pages.message')->with('message', $message);
	}
	
	public function setPartner(Request $request){
		session(['partner' => $request->partner]);
		return back()->withInput();
	}

	public function upload($ticket_ID) {
		$attachments = DB::table('uploads')->where('connected_ticket',$ticket_ID)->get();
//        echo $attachments;
		return view('pages.upload',['ticket_ID'=>$ticket_ID])->with('attachments',$attachments);
	}
	
	public function uploadDoc() {
		$partner = DB::table('partners')->where('partner_ID', session('partner'))->first();
		return view('pages.uploadDoc',['partner' => $partner]);
	}
	
	public function contact() {
        return view('pages.contact');    
	}
        
	public function news() {
        return view('pages.news');    
	}

	public function docs() {
		$partners = DB::table('partners')->where('partner_state','active')->orderBy('partner_name')->get();
		if (session('partner')== 0) {
			$docs = DB::table('uploads')->where('type','document')->join('partners','connected_partner','partner_ID')->get();
		}
		else {
			$docs = DB::table('uploads')->join('partners','connected_partner','partner_ID')->where('type','document')->where('connected_partner',session('partner'))->get();
		}
		return view('pages.docs',['docs' => $docs])->with('partners',$partners);    
	}
    
	public function leltar() {
        return view('pages.leltar');    
	}

	public function eszkozok() {
        return view('pages.eszkozok');    
	}

	public function szabalyzatok() {
        return view('pages.szabalyzatok');    
	}
}
