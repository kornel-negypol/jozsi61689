<?php

namespace Ticket\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class MyLoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @return Response
     */
    public function authenticate(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'state' => 'Active'])) {
            // Authentication passed...
            // Session alpértékek beállítása
            session(['topic' => 'Minden csoport']);
            session(['ticket_state' => 'Nyitott']);
            session(['task_type' => 'hibajegy']);
            session(['open_tickets' => 0]);
            session(['closed_tickets' => 0]);
            session(['last_activities' => 0]);
            if (Auth::user()->user_type=="partner") {
                $user = Auth::user()->id;
                $partner = Auth::user()->partner_ID;
                session(['partner' => $partner]);
                session(['user' => $user]);
                
            }
            else {
                session(['partner' => 0]);
                session(['user' => 0]);                
            }
            //return redirect('/');     //lapukornel 2021.04.26
            return redirect()->intended('/');
        }
        return back()->withInput()->withErrors(['email' => 'Hibás e-mail cím, vagy jelszó!']);
    }
    
    public function setTopic(Request $request){
        session(['topic' => $request->topic]);
        session(['partner' => $request->partner]);
        session(['user' => $request->user]);
        return redirect('/');
    }
    
    public function setParam(Request $request){
        session(['ticket_state' => $request->ticket_state]);
        session(['task_type' => $request->task_type]);
//        echo session('task_type');
        return redirect('/');
    }
    
    public function mainPage(){
        session(['topic' => 'Minden csoport']);
        session(['ticket_state' => 'Nyitott']);
        session(['task_type' => 'hibajegy']);
        session(['open_tickets' => 0]);
        session(['closed_tickets' => 0]);
        session(['last_activities' => 0]);
        if (isset(Auth::user()->user_type)) {
            if (Auth::user()->user_type=="partner") {
                $user = Auth::user()->id;
                $partner = Auth::user()->partner_ID;
                session(['partner' => $partner]);
                session(['user' => $user]);                
            }
            else {
                session(['partner' => 0]);
                session(['user' => 0]);                
            }
        }
        return redirect('/');
    }

    public function myLogout() {
        Auth::logout();
        return redirect('/');
    }
}
