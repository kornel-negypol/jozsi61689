<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
Route::get('/', function () {
    return view('welcome');
});*/

Auth::routes();
Route::get('/register','HomeController@index'); //disable registering
Route::get('/home', 'HomeController@index');

Route::post('mylogin','MyLoginController@authenticate');
Route::post('setTopic','MyLoginController@setTopic')->middleware('auth');
Route::post('setParam','MyLoginController@setParam')->middleware('auth');
Route::get('myLogout','MyLoginController@myLogout');
Route::get('mainPage','MyLoginController@mainPage');

Route::get('newUser','BasicController@newUser');
Route::get('users','BasicController@users')->middleware('auth');
Route::post('filteredUsers','BasicController@filteredUsers')->middleware('auth');
Route::get('editUser/{user_ID}','BasicController@editUser')->middleware('auth');
Route::post('updateUser','BasicController@updateUser')->middleware('auth');
Route::get('settings','BasicController@changePasswd')->middleware('auth');
Route::post('resetPasswd','BasicController@resetPasswd')->middleware('auth');
Route::post('updatePasswd','BasicController@updatePasswd')->middleware('auth');
Route::post('emailSet','BasicController@emailSet')->middleware('auth');
Route::post('saveFile','BasicController@saveFile')->middleware('auth');

Route::get('newPartner','BasicController@newPartner')->middleware('auth');
Route::post('addPartner','BasicController@addPartner')->middleware('auth');
Route::get('partners','BasicController@partners')->middleware('auth');
Route::get('editPartner/{partner_ID}','BasicController@editPartner')->middleware('auth');
Route::post('deleteContact','BasicController@deleteContact')->middleware('auth');
Route::post('updatePartner','BasicController@updatePartner')->middleware('auth');
Route::get('contact','BasicController@contact')->middleware('auth');
Route::get('downloadFile/{upload_ID}','BasicController@downloadFile')->middleware('auth');

Route::get('/','TicketController@index')->middleware('auth');
Route::get('newTicket','TicketController@newTicket')->middleware('auth');
Route::post('addTicket','TicketController@addTicket')->middleware('auth');
Route::post('addComment','TicketController@addComment')->middleware('auth');
Route::get('editTicket/{ticket_ID}','TicketController@editTicket')->middleware('auth');
Route::get('modifyTicket/{ticket_ID}','TicketController@modifyTicket')->middleware('auth');
Route::post('updateTicket','TicketController@updateTicket')->middleware('auth');
Route::post('closeTicket','TicketController@closeTicket')->middleware('auth');
Route::get('openTicket/{ticket_ID}','TicketController@openTicket')->middleware('auth');
Route::get('putTask/{ticket_ID}','TicketController@putTask')->middleware('auth');
Route::post('takeTicket','TicketController@takeTicket')->middleware('auth');
Route::post('searchTicket','TicketController@searchTicket')->middleware('auth');
Route::get('archiving/{ticket_ID}','TicketController@archiving')->middleware('auth');
Route::get('freshEvents','TicketController@freshEvents')->middleware('auth');

Route::get('newTask','TaskController@newTask')->middleware('auth');
Route::post('addTask','TaskController@addTask')->middleware('auth');
Route::get('timedTasks','TaskController@timedTasks')->middleware('auth');
Route::get('timing','TaskController@timing')->middleware('auth');
Route::get('editTimedTask/{task_ID}','TaskController@editTimedTask')->middleware('auth');
Route::post('setTimer','TaskController@setTimer')->middleware('auth');
Route::post('updateTimer','TaskController@updateTimer')->middleware('auth');
Route::get('activateTasks','TaskController@activateTasks');
Route::get('deleteTimedTask/{task_ID}','TaskController@deleteTimedTasks')->middleware('auth');
Route::get('news','TaskController@news')->middleware('auth');
Route::get('docs','TaskController@docs')->middleware('auth');
Route::get('leltar','TaskController@leltar')->middleware('auth');
Route::get('eszkozok','TaskController@eszkozok')->middleware('auth');
Route::get('szabalyzatok','TaskController@szabalyzatok')->middleware('auth');
Route::post('setPartner','TaskController@setPartner')->middleware('auth');
Route::get('uploadDoc','TaskController@uploadDoc')->middleware('auth');
Route::get('upload/{ticket_ID}','TaskController@upload')->middleware('auth');

Route::get('getMails','EmailController@getMails');
Route::get('phpinfo','EmailController@php_info');
Route::get('getMails_test','EmailController@getMails_test'); // tesztelés
Route::get('checkOwner','EmailController@checkOwner');

// Munkalap és statisztika
Route::get('statisztika','MunkalapController@statisztika')->middleware('auth');
Route::post('dataList','MunkalapController@dataList')->middleware('auth');
Route::post('addWorkSheet','MunkalapController@addWorkSheet')->middleware('auth');
Route::get('workSheet/{ticket_ID}','MunkalapController@workSheet')->middleware('auth');
Route::get('htmltopdfview',array('as'=>'htmltopdfview','uses'=>'MunkalapController@htmltopdfview'));
Route::get('napiLista','MunkalapController@napiLista')->middleware('auth');
Route::post('addMunka','MunkalapController@addMunka')->middleware('auth');

Route::get('moveData','TicketController@moveData');
Route::get('teszt','BasicController@teszt');
Route::get('teszt3','BasicController@teszt3');
