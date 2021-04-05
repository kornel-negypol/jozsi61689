<?php

namespace Ticket\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DB;
use Ticket\Http\Requests;
use Ticket\Http\Controllers\Controller;

class BasicController extends Controller
{
    public function newUser(Request $request){
        $partners = DB::table('partners')->where('partner_state','active')->orderBy('partner_name')->get();
        return view('pages.newUser',['partners' => $partners]);
    }

    public function users(){
        $state = 'Active';
        $user_type = 'all';
        $users = DB::table('users')->where('state', $state)->orderby('name')->get();
        return view('pages.users',['users' => $users])->with('state', $state)->with('user_type',$user_type);
    }

    public function filteredUsers(Request $request){
        $state = $request->input('state');
        $user_type = $request->input('user_type');
        if ($user_type == 'all'){
            $users = DB::table('users')->where('state', $state)->orderby('name')->get();
        }
        else {
            $users = DB::table('users')->where([['user_type', $user_type],['state', $state]])->orderby('name')->get();            
        }
        return view('pages.users',['users' => $users])->with('state', $state)->with('user_type',$user_type);
    }
    
    public function editUser($user_ID){
        $user_type = Auth::user()->user_type;
        $user = DB::table('users')->where('id', $user_ID)->first();
        $partners = DB::table('partners')->where('partner_state','active')->orderBy('partner_name')->get();
        return view('pages.editUser',['user' => $user])->with('partners',$partners)->with('user_type',$user_type);
    }

    public function updateUser(Request $request) {
        $this->validate($request, [
            'firstname' => 'required|max:127',            
            'name' => 'required|max:127',
            'email' => 'required|email|max:255'
        ]);        

        $id = $request->input('user_id');
        $name = $request->input('name');
        $firstname = $request->input('firstname');
        $email = $request->input('email');
        $user_type = $request->input('user_type');
        $state = $request->input('state');
        DB::table('users')
            ->where('id', $id)
            ->update(['name' => $name,
                      'firstname' => $firstname,
                      'email'=> $email,
                      'state'=> $state,
                      'partner_ID' => $request->partner,
                      'updated_at'=>date('Y-m-d H:i:s'),
                      'user_type'=> $user_type]);
        if ($user_type == 'ext_user') {
            if (DB::table('ext_user_rights')->where('user_ID',$id)->first() == null) {
                DB::table('ext_user_rights')->insertGetId([
                        'user_ID' => $id,
                        'partner_ID' => $request->partner
                ]);
            }
            else {
                DB::table('ext_user_rights')->where('user_ID', $id)->update(['partner_ID' => $request->partner]);
            }
        }
        $state = 'Active';
        $user_type = 'all';
        $users = DB::table('users')->orderby('name')->get();
// log írás
        $IP = $_SERVER['REMOTE_ADDR'];
        $msg =  $request->name . " " . $request->firstname . " felhasználó módosítása";
        $id1 = DB::table('logs')->insertGetId([
            'user_ID' => Auth::user()->id,
            'action' => $msg,
            'IP' => $IP]);
        return view('pages.users',['users' => $users])->with('state', $state)->with('user_type',$user_type);
    }

    public function changePasswd(){
        return view('pages.changePasswd');
    }
    
    public function resetPasswd(Request $request){
        $this->validate($request, [
            'password' => 'required|min:8|max:127|confirmed',
        ]);        
        $id = $request->input('user_id');
        $password = Hash::make($request->password);
        DB::table('users')
            ->where('id', $id)
            ->update(['password' => $password]);
// log írás
        $IP = $_SERVER['REMOTE_ADDR'];
        $msg = Auth::user()->firstname . " " . Auth::user()->name . " új jelszó beállítása";
        $id1 = DB::table('logs')->insertGetId([
            'user_ID' => Auth::user()->id,
            'action' => $msg,
            'IP' => $IP,
            'type' => "private"]);
        return $this->users();
    }

    public function updatePasswd(Request $request){
        $this->validate($request, [
            'oldpasswd' => 'required',
            'password' => 'required|min:8|max:127|confirmed',
        ]);        
        $id = Auth::user()->id;
        $hashedPassword = Auth::user()->password;
        if (Hash::check($request->oldpasswd, $hashedPassword)) {           
            $password = Hash::make($request->password);
            DB::table('users')
                ->where('id', $id)
                ->update(['password' => $password]);
// log írás
            $IP = $_SERVER['REMOTE_ADDR'];
            $msg = Auth::user()->firstname . " " . Auth::user()->name . " jelszó csere";
            $id1 = DB::table('logs')->insertGetId([
                'user_ID' => Auth::user()->id,
                'action' => $msg,
                'IP' => $IP,
                'type' => "private"]);
            return redirect('/');
        }
        return redirect()->back();
    }
    
    public function newPartner(){
        $users = DB::table('users')->where([['user_type','<>','partner'],['state','Active']])->orderby('name')->get();
        return view('pages.newPartner',['users' => $users]);
    }

    public function addPartner(Request $request){
        $this->validate($request, [
            'partner_name' => 'bail|required|unique:partners|max:255',
            'city' => 'required|max:127',
            'zip_code' => 'required|digits:4',
            'address' => 'required|max:255',
            'email' => 'unique:partners|email',
            'comment' => 'max:511',
        ]);
        
        if ($request->email == '') {
            $request->email = NULL;
        }
        
        $id = DB::table('partners')->insertGetId([
                'partner_name' => $request->input('partner_name'),
                'city' => $request->input('city'),
                'zip_code' => $request->input('zip_code'),
                'address' => $request->input('address'),
                'comment' => $request->input('comment'),
                'default_topic' => $request->input('default_topic'),
                'email' => $request->email,
        ]);
        if ($id) {
        // ------ felelős felvitele ------
            $id1 = DB::table('responsible')->insertGetId([
                'partner_ID' => $id,
                'user_ID' => $request->input('responsible'),
            ]);
            $message = "Partner regisztrálva.";
            
    // log írás
            $IP = $_SERVER['REMOTE_ADDR'];
            $msg = $request->input('partner_name') . " ügyfél felvitele";
            $id1 = DB::table('logs')->insertGetId([
                'user_ID' => Auth::user()->id,
                'action' => $msg,
                'IP' => $IP,
                'type' => "private"]);
        }
        else {
            $message = "Ismeretlen hiba!";
        }
        return view('pages.message',['message'=> $message]);
    }

    public function partners(){
        $user_id = Auth::user()->id;
        $user_type = Auth::user()->user_type;
        if ($user_type <> 'partner') {
            $open_partners = DB::table('partners')
                ->leftjoin('tickets','partners.partner_ID','tickets.partner_ID')
                ->where('partner_state','active')
                ->where('ticket_state','<>', 'Lezárt')
//                ->orwhere('ticket_state', NULL)
//                ->select('partner_name','partners.partner_ID', DB::raw("count(if(tickets.ticket_state <> 'Lezárt', ticket_ID, 0)) as open_tickets"))
                ->select('partner_name','partners.partner_ID', DB::raw('count(ticket_ID) as open_tickets'))
                ->groupBy('partner_name')
                ->orderBy('partner_name')
                ->get();
            $partners = DB::table('partners')
                ->where('partner_state','active')
                ->select('partner_name','partner_ID', ('partner_ID as open_tickets'))
                ->orderBy('partner_name')
                ->get();
        }
        return view('pages.partners',['partners'=> $partners])->with('open_partners',$open_partners);
    }

    public function editPartner($partner_ID){
        $user_type = Auth::user()->user_type;
        $contacts = DB::table('users')->where('partner_ID',$partner_ID)->get();
        $responsibles = DB::table('responsible')
                            ->join('users','users.id','responsible.user_ID')
                            ->where('responsible.partner_ID',$partner_ID)->get();
        $partners = DB::table('partners')->where('partner_ID',$partner_ID)->get();
        $users = DB::table('users')->where([['user_type','<>','partner'],['state','Active']])->orderby('name')->get();
        $contacts = DB::table('users')->where([['partner_ID',$partner_ID],['state','Active']])->get();
        return view('pages.editPartner',['partners'=> $partners])
                                        ->with('users',$users)
                                        ->with('responsibles',$responsibles)
                                        ->with('user_type',$user_type)
                                        ->with('contacts',$contacts);
    }

    public function updatePartner(Request $request){
        if ($request->responsible > 0) {
            $id1 = DB::table('responsible')->insertGetId([
                'partner_ID' => $request->partner_ID,
                'user_ID' => $request->responsible,
            ]);
        }
        if ($request->contact > 0) {
            DB::table('users')->update(['partner_ID' => $request->partner_ID])->where('id',$request->contact);                       
        }
        $this->validate($request, [
            'partner_name' => 'bail|required|max:255',
            'city' => 'required|max:127',
            'zip_code' => 'required|digits:4',
            'address' => 'required|max:255',
            'comment' => 'max:511',
        ]);
        try {
            DB::table('partners')
                ->where('partner_ID', $request->partner_ID)
                ->update(['partner_name' => $request->partner_name,
                    'city' => $request->city,
                    'zip_code' => $request->zip_code,
                    'address' => $request->address,
//                    'comment' => $request->comment,
                    'comment' => $request->default_topic,
                    'email' => $request->email,
                    'default_topic' => $request->default_topic,]);
        }
        catch (\Illuminate\Database\QueryException $e) {
            $message = "Hibás adat: Az email cím már létezik";
            return view('pages.message',['message'=>$message]);
        } 
        
// log írás
        $IP = $_SERVER['REMOTE_ADDR'];
        $msg = $request->partner_name . " ügyfél módosítása";
        $id1 = DB::table('logs')->insertGetId([
                'user_ID' => Auth::user()->id,
                'action' => $msg,
                'IP' => $IP,
                'type' => "public"]);
        return $this->editPartner($request->partner_ID);
    }

    public function deleteContact(Request $request){
        if ($request->delete_contact > 0){
            DB::table('users')->where('id',$request->delete_contact)->update(['partner_ID'=> 0]);
// log írás
            $IP = $_SERVER['REMOTE_ADDR'];
            $msg = $request->input('partner_name') . " kapcsolattartó törlése";
            $id1 = DB::table('logs')->insertGetId([
                'user_ID' => Auth::user()->id,
                'action' => $msg,
                'IP' => $IP,
                'type' => "public"]);
        }
        if ($request->delete_responsible > 0){
            DB::table('responsible')->where('responsible_ID',$request->delete_responsible)->delete();
        }
        return $this->editPartner($request->partner_ID);
    }
    
    public function emailSet(Request $request){
        $closeticketmail = 0;
        $newticketmail = 0;
        if ($request->newticketmail == "on") {
           $newticketmail = 1;
        }
        if ($request->closeticketmail == "on") {
           $closeticketmail = 1;
        }
        DB::table('users')->where('id',$request->user_ID)
                        ->update(['newticketmail'=> $newticketmail,
                                'closeticketmail'=> $closeticketmail]);
        $user = DB::table('users')->where('id', $request->user_ID)->first();
        if (Auth::user()->id == $request->user_ID) {
            return redirect('/profil');
        }
        $user_type = Auth::user()->user_type;
        $partners = DB::table('partners')->where('partner_state','active')->orderBy('partner_name')->get();
        return view('pages.editUser',['user' => $user])->with('partners', $partners)->with('user_type',$user_type);
    }
    
	public function saveFile(Request $request) {
		$file = $request->file('file-up');
		$extension = $file->getClientOriginalExtension();
		$fileName = $file->getFilename().'.'.$extension;
		$origName = $file->getClientOriginalName();
		$mime = $file->getClientMimeType();
		$file->move("uploads", $fileName);
        if ($request->ticket_ID){
            $id = DB::table('uploads')->insertGetId(['filename' => $fileName,
                                                    'origname' => $origName,
                                                    'mime'=> $mime,
                                                    'type'=> $request->type,
                                                    'connected_ticket'=>$request->ticket_ID]);
            DB::table('tickets')->where('ticket_ID',$request->ticket_ID)
                                ->update(['modified'=>date('Y-m-d H:i:s')]);
        }
        else {
            $id = DB::table('uploads')->insertGetId(['filename' => $fileName,
                                                    'origname' => $origName,
                                                    'mime'=> $mime,
                                                    'type'=> $request->type,
                                                    'connected_partner'=> $request->connected_partner]);            
        }
		$message = "A feltöltött file neve: ". $fileName;		
		return view('pages.message')->with('message', $message);
	}
    
    public function downloadFile($upload_ID) {
        $document = DB::table('uploads')->where('ID',$upload_ID)->first();
        $file = public_path() . '/uploads/' . $document->filename;
        return Response()->download($file, $document->origname);
    }
    
    public function contact() {
        return view('pages.contact');
    }

    
    public function teszt() {
        return view('pages.teszt');
    }

    public function teszt3() {
        return view('pages.teszt3');
    }
    
}

