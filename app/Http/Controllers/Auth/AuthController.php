<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\Util\ReturnJSON;
use Auth;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use PhpParser\Node\Expr\Empty_;
use PhpParser\Node\Stmt\Return_;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout','login']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        /*return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
        */
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function login(LoginRequest $request) {
        
        $credentials = $request->all();
        
        if(!Auth::guard('api')->attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);

        $user = $request->user();

        return response()->json([
            'User' => $user,
            'api_token' => $user->api_token
        ]);
    }

    public function loginSocial(){
        $inputs = request()->all();

        $email = $inputs['email'];
        $user = User::where('email',$email)->first() ;

        if(empty ($user) ){


            //crear imagen 
            $image = null;


            $newUser = new User();
            $newUser->first_name = 'test';
            $newUser->last_name = ' Test';
            $newUser->role = 'user';
            $newUser->email = '';
            $newUser->password = '';
            $newUser->api_token = str_random(50);

            if( ! empty($image)){
                $newUser->photo_id = $image->id;
            }

            
            $user = $newUser->save();
            //Auth::guard('api')->login($newUser, true);

        }

        return  ReturnJSON::success([
            'User' => $user,
            'api_token' => $user->api_token
        ]);
    }


}
