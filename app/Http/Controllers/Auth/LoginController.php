<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\Models\User;
use GuzzleHttp\Exception\ClientException;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'User_Logon';
    }

    public function login(Request $request){
		$username = $request->input("User_Logon"); //sample logon
		$password = $request->input("password"); //sample password
		$url = "http://localhost/sdata/crmj/sagecrm2/-/Company?startindex=1&count=1"; //sample CRM sdata url

		$statuscode = "";
		try{
		$client = new \GuzzleHttp\Client([
					'allow_redirects' => false
		]);

		$request = $client->get($url, [
			"auth" => [
				$username, 
				$password
			]
		]);
		//get Status Code, if needed
		$statusCode = $request->getStatusCode();
		//get User who has this Username
		$user = User::where("User_Logon", $username)->first();
		if(!empty($user)){
			auth()->login($user);
			//redirect after success
			return redirect()->route("home");
		}
		}catch(ClientException $e){
			  //get Status Code if Error, if needed
			  $statuscode = $e->getCode();
		}

        return $this->sendFailedLoginResponse($request);
    }
}
