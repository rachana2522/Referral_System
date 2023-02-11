<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Network;
use Mail;
use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use Carbon\Carbon;
use PhpParser\Node\Stmt\Catch_;

class UserController extends Controller
{
    //
    public function loadRegister()
    {
    $textname ="my name";
        return view('register')->with("textname",$textname);
    }
    public function registered(Request $request)
    {
    
        $request->validate([
            'name' => 'required |string|min:2',
            'email' => 'required |string|email|max:100|unique:users',
            'Password' => 'required|min:6|confirmed',
        ]);

        $referralCode = Str::random(10);
        $token =Str.random(50);


        if (isset($request->referral_code)) {

            $userData = User::where('referral_code', $request->referral_code)->get();

            if (count($userData) > 0) {


                $user_id = user::insertGetId([
                    'name' => '$request->name',
                    'email' => '$request->email',
                    'password' => Hash::make($request->password),
                    'referral_code' => $referralCode,
                    'remember_token' =>$token,
                ]);
                Network::insert([
                    'referral_code' => $request->referral_code,
                    'user_id' =>  $user_id,
                    'parent_user_id' => $userData[0]['id'],
                ]);
            } else {
                return back()->with('error', 'Please enter valid Refferal Code!');
            }
        } else {


            user::insert([
                'name' => '$request->name',
                'email' => '$request->email',
                'password' => Hash::make($request->password),
                'referral_code' => $referralCode,
                'remember_token' =>$token,
            ]);


        }

        $domain = URL::to('/');
        $url = $domain.'/referral-register?ref='.$referralCode;

        $data['url']=$url;
        $data['name']=$request->name;
        $data['email']=$request->email;
        $data['password']=$request->password;
        $data['title']='Registered';
        
        Mail::send('emails.registerMail',['data'=>$data],function($message)use($data){
            $message->to($data['email'])->subject( $data['title']);
        });

        //verify mail
        $url = $domain.'/email-verification/'.$token;

        $data['url']=$url;
        $data['name']=$request->name;
        $data['email']=$request->email;
        $data['title']='Referral verification mail';
        
        Mail::send('emails.verifyMail',['data'=>$data],function($message)use($data){
            $message->to($data['email'])->subject( $data['title']);
        });


        return back()->with('success', 'Your Registration has been successfull  and please verify your mail!');
    }

    public function loadReferralRegister(Request  $request)
    {
        if(isset($request->ref)){
            $referral =$request->ref;
            User::where('referral_code' , $referral)->get();


            if(count($userData) > 0){

                return view('referralRegister',compact('referral'));
            }
            else{
                return view('404');
            }

        }
        else{
            return redirect('/');

        }

    }

    public function emailVerification($token)
    {
     $userData = user::where('remember_token',$token) -> get();

    if(count($userData) >0){

        if($userData[0]['is_verified'] == 1){
            return view('verified',['message'=> 'your mail is already verifed']);
        }

        User::where('id', $userData[0]['id']) ->update([
            'is_verified'=> 1,
            'email_verified_at'=> date('y-m-d H:i:s')
        ]);
        return view('verified',['message'=> 'your' .$userData[0] ['email'].'mail is verifed  successfull!']);

    }
     else{
        return view('verified' , ['message' => '404 page not found!']);
     }


    }

    Public function loadlogin()
    {
        return view('/login');

    }

    public function userLogin(Request $request)
    {
        $request->validate([
            'email' => 'requiredstring|email',
            'password' => 'required |string'
        ]);

        $userData =User::where('email',$request->email)->first();
        if(!empty($userData)){
            if($userData->is_verified ==0){
                return back()->with('error','please verify your mail!');
            }
        }

        $userCredential = $request->only('email','password');
        if(Auth::attempt($userCredential)){
            return redirect('/dashboard');
        }
        else{
            return back()->with('error','Username & password  is incorrect');
        }

    }

 Public function loadDashboard()
    {
        die();
       $networkCount=Network::where('parent_user_id',Auth::user()->id)->orWhere('user_id',Auth::user())->count();
       Network::with('user')->where('parent_user_id',Auth::user()->id)->get();

        $shareComponent = \share::page(
            URL::to('/').'/referral-register?ref='.Auth::user()->referral_code,
            'share and earn point referral',
         )

        ->facebook()
        ->twitter()
        ->linkedin()
        ->whatsapp()
        ->reddit();
        


        return view('dashboard',compact(['networkCount','networkData','shareComponent']));

    }

    Public function logout(Request $request)
    {
          $request ->session()->flush();
          Auth::login();
          return redirect('/');
    }

    Public function referralTrack()
    {
        $dateLabels =[];
        $dateData =[];
        for($i = 30;$i >=0;$i--){

         $dateLabels[]= Carbon::now()->subDays($i)->format('d-m-y');
         Network::whereDate('created_at',Carbon::now()->subDays($i)->format('d-m-y'))->
         Where('parent_user_id',Auth::user()->id)->count();

         $dateLabels =json_encode($dateLabels);
         $dateData =json_encode($dateData);


          return view('referralTrack',compact(['dataLabels','dataData']));
    
            }
    }

    Public function deleteAccount(Request $request)
    {
            try{
                User::where('id',Auth::user()->id)->delete();
                $request->session()->flush();
                Auth::logout();
                return response()->json(['success' =>true]);

               } Catch(\Exception $e){

                return response()->json(['success' =>false,'msg'=>$e->getMessage()]);
            }

    }
}
