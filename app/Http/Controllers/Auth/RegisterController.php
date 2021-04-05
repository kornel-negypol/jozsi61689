<?php

namespace Ticket\Http\Controllers\Auth;

use Ticket\User;
use Validator;
use Ticket\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;

use DB;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/users';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'firstname' => 'required|max:128',            
            'name' => 'required|max:128',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:8|confirmed',
            'user_type' => 'required',
            'partner' => 'required',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $err = User::create([
            'firstname' => $data['firstname'],
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'user_type' => $data['user_type'],
            'state' => $data['state'],
            'partner_ID' => $data['partner'],
        ]);
        $user_ID = DB::table('users')->where('email',$data['email'])->first();
        DB::table('ext_user_rights')->insertGetId([
                                        'user_ID' => $user_ID->id,
                                        'partner_ID' => $data['partner']
                                        ]);
        return($err);
    }
}
