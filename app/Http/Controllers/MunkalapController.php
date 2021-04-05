<?php

namespace Ticket\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Ticket\Http\Requests;
use Ticket\Http\Controllers\Controller;
use PDF;

class MunkalapController extends Controller
{
    public function statisztika() {
    // check admin rights!!!
        $user_type = Auth::user()->user_type;
        if ($user_type != 'admin') {
            return redirect('/');
        }
    // első belépés ellenörzése
        $partners = DB::table('partners')->where('partner_state','active')->orderBy('partner_name')->get();
        $users = DB::table('users')->where('user_type','<>','partner')->where('state', 'Active')->orderBy('name')->get();
        $data['first'] = true;
        return view('pages.statisztika',['partners' => $partners])->with('users', $users)->with('data',$data);
    }
    public function dataList(Request $request) {
        $this->validate($request, [
            'start-date' => 'date_format:YYYY-mm-dd',
            'end-date' => 'date_format:YYYY-mm-dd',
        ]);
        $data['first'] = false;
        $partners = DB::table('partners')->where('partner_state','active')->orderBy('partner_name')->get();
        $users = DB::table('users')->where('user_type','<>','partner')->where('state', 'Active')->orderBy('name')->get();
        
        if (($request->partner == '0') and ($request->user == '0')) {
            $tickets = DB::table('tickets')->where('ticket_state',"lezárt")->where('modified','>=', $request->start_date)->where('modified','<=', $request->end_date)->get();
			$new_tickets = DB::table('tickets')->where('ticket_state','<>','Archive')->where('created','>=', $request->start_date)->where('created','<=', $request->end_date)->get();
        }
        elseif ($request->user == '0') {
            $tickets = DB::table('tickets')->where('ticket_state',"lezárt")->where('partner_ID',$request->partner)->where('modified','>=', $request->start_date)->where('modified','<=', $request->end_date)->get();            
    		$new_tickets = DB::table('tickets')->where('ticket_state','<>','Archive')->where('partner_ID',$request->partner)->where('created','>=', $request->start_date)->where('created','<=', $request->end_date)->get();
        }
        elseif ($request->partner == '0') {
            $tickets = DB::table('tickets')->where('ticket_state',"lezárt")->where('owner',$request->user)->where('modified','>=', $request->start_date)->where('modified','<=', $request->end_date)->get();
			$new_tickets = DB::table('tickets')->where('ticket_state','<>','Archive')->where('owner',$request->user)->where('created','>=', $request->start_date)->where('created','<=', $request->end_date)->get();
        }
        else {
            $tickets = DB::table('tickets')->where('ticket_state',"lezárt")->where('owner',$request->user)->where('partner_ID',$request->partner)->where('modified','>=', $request->start_date)->where('modified','<=', $request->end_date)->get();
			$new_tickets = DB::table('tickets')->where('ticket_state','<>','Archive')->where('owner',$request->user)->where('partner_ID',$request->partner)->where('created','>=', $request->start_date)->where('created','<=', $request->end_date)->get();
        }
        
    // partner név beállítás       
        if ($request->partner == '0') {
            $data['partner'] = "Minden ügyfél";
        }
        else {
            $partner = DB::table('partners')->where('partner_ID',$request->partner)->first();
            $data['partner'] = $partner->partner_name;
        }
    // user név beállítás
        if ($request->user == '0') {
            $data['user'] = "Minden felhasználó";
        }
        else {
            $user = DB::table('users')->where('id',$request->user)->first();
            $data['user'] = $user->name.' '.$user->firstname;
        }
        
        $count = $tickets->count();		
        $worktime_min = 0;
        $after_min = 0;
        foreach ($tickets as $ticket) {
            $worktime_min += $ticket->in_worktime;
            $after_min += $ticket->after_worktime;
        }
        $all_min = $worktime_min + $after_min;
        $data['state'] = $request->state;
        $data['start_date'] = $request->start_date;
        $data['end_date'] = $request->end_date;
        $data['hours'] = intval($all_min / 60);
        $data['minutes'] = $all_min - $data['hours']*60;
        $data['worktime_hours'] = intval($worktime_min / 60);
        $data['worktime_minutes'] = $worktime_min - $data['worktime_hours']*60;
        $data['out_hours'] = intval($after_min / 60);
        $data['out_minutes'] = $after_min - $data['out_hours']*60;
		$data['closed_tickets'] = $count;
		$data['new_tickets'] = $new_tickets->count();	
        return view('pages.dataList',['partners' => $partners])->with('users', $users)->with('data',$data);
    }
    
    public function workSheet($ticket_ID) {
        if (DB::table('worksheets')->where('ticket_ID',$ticket_ID)->count() > 0) {
            return view('pages.message',['message'=>'Már készült munkalap erről a hibajegyről!']);
        }
        $ticket = DB::table('tickets')->where('ticket_ID',$ticket_ID)->get()->first();
        
        return view('pages.workSheet',['ticket'=> $ticket]);        
    }
    
    public function addWorkSheet(Request $request) {
        $this->validate($request, [
            'hiba' => 'required|max:1023',
            'adat' => 'max:256',
            'munka' => 'required|max:1023',
            'megjegyzés' => 'max:511',
        ]);
// Készült-e már munkalap?
        if (DB::table('worksheets')->where('ticket_ID',$request->ticket_ID)->count() > 0) {
            return view('pages.message',['message'=>'Már készült munkalap erről a hibajegyről!']);
        }
        $in_worktime = $request->hours*60 + $request->minutes;
        $after_worktime = $request->hours2*60 + $request->minutes2;
        $id = DB::table('worksheets')->insertGetId([
                'ticket_ID' => $request->ticket_ID,
                'ticket_content' => $request->hiba,
                'device_name' => $request->adat,
                'works_done' => $request->munka,
                'comment' => $request->megjegyzés,
                'in_worktime' => $in_worktime,
                'after_worktime' => $after_worktime,
                'done_by' => Auth::user()->id,
                'ticket_created'=> $request->ticket_created,
                'ticket_closed'=> $request->ticket_closed,
        ]);
        $ticket = DB::table('tickets')->join('partners','tickets.partner_ID','partners.partner_ID')->where('ticket_ID',$request->ticket_ID)->get()->first();
        $cim = $ticket->zip_code ." ".$ticket->city ." ".$ticket->address;
        $data = ["telefon" => " ",
                'megrendelo' => $ticket->partner_name,
                'cim'=> $cim,
                'device_name' => $request->adat,
                'hiba' => $request->hiba,
                'munka' => $request->munka,
                'in_worktime' => $in_worktime/60,
                'after_worktime' => $after_worktime/60,
                'done_by' => (Auth::user()->name .' '. Auth::user()->firstname),
                'megjegyzes' => $request->megjegyzés,
                "ticket_ID"=>$request->ticket_ID,
                "datum"=>$request->ticket_created];
        $pdf = PDF::loadView('pages/workSheetPrint',$data)->setPaper('A4', 'portrait');
        return $pdf->download('munkalap.pdf');
    }

//    public function htmltopdfview(Request $request)  {
//         PDF::setPaper('A4', 'portrait')->setOption('margin-top', 15);
//         if($request->has('download')){
//            $pdf = PDF::loadView('pages/workSheetPrint');
//            return $pdf->download('pages/htmltopdfview');
//             }
//         return view('pages/htmltopdfview');
//    }
    
}
