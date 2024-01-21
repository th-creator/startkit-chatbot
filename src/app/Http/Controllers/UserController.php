<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Password;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Auth\Events\PasswordReset;
use App\Notifications\ResetPasswordNotification;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware('guest')->except(['logout','edit','update']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
      $formFields = $request->validate([
          'firstName' => ['required','min:2'],
          'lastName' => ['required','min:2'],
          'email' => ['required','email',Rule::unique('users','email')],
          'password' => 'required|confirmed|min:6'
      ]);

      //hash password
      $formFields['password'] = bcrypt($formFields['password']);

      $formFields['active'] = 1;

      // create user
      $user = User::create($formFields);
      
      //login
      return response()->json(["user" => $user, "token" => $user->createToken("Api Token of ".$user->name)->plainTextToken,"message" =>"user created and in"],200);
  }
  
  public function authenticate(Request $request) {
    $formFields = $request->validate([
        'email' => ['required','email'],
        'password' => 'required'
    ]);

    if(!Auth::attempt($formFields)) {
        return response()->json("invalid credentials",401);
    }
    $user = User::where('email',$request->email)->first();

    return response()->json(["user" => $user, "token" => $user->createToken("Api Token of ".$user->name)->plainTextToken,"message" =>"user in"],200);
    
}
    public function login(Request $request)
    {
      $credentials = $request->validate([
          'email' => ['required','email'],
          'password' => 'required',
      ]);

      if(Auth::attempt($credentials)) {
          $user = User::where('email',$request->email)->first();
          $name = $user->firstName;
      } else {
          return response()->json("invalid credentials",401);
      }

      // return response()->json($user,203);

      if ($user->active == 0 ) {
          return response()->json("this account is blocked",401);
      }

      return response()->json(["user" => $user, "token" => $user->createToken("Api Token of ".$name)->plainTextToken,"message" =>"user in"],200);
      
    }
    public function getUser(Request $request) {
      $user = auth("sanctum")->user();
      
      return response()->json($user,200);
  }
    public function sendResetLinkEmail(Request $request)
    {
        $email=$request->validate(['email' => 'required|email']);

        $user=User::where('email',$email)->first();
        if(!$user){
            return response()->json(['message' => 'Email does not exist'], 404);
        }
        // return response()->json(['message' => 'exist'], 200);
        // $status = Password::sendResetLink($request->only('email'));
        $token = Password::createToken($user);

        $user->notify(new ResetPasswordNotification($token));

        return response()->json(['message' => 'Password reset link sent']);
        // return $status === Password::RESET_LINK_SENT
        //     ? response()->json(['message' => 'Password reset link sent'])
        //     : response()->json(['message' => 'Unable to send reset link'], 500);
        
        
    }
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:5|confirmed',
        ]);

        $status = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->save();

            $user->setRememberToken(Str::random(60));

            event(new PasswordReset($user));
        });

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Password reset successfully'])
            : response()->json(['message' => 'Unable to reset password'], 500);
    }
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        $google_user = Socialite::driver('google')->stateless()->user();

        $user=User::where('google_id',$google_user->getId())->first();
        
        if(!$user){
            $new_user=User::create([
                'firstname'=>$google_user->getName(),
                'email'=>$google_user->getEmail(),
                'google_id'=>$google_user->getId(),
            ]);
            Auth::login($new_user);
            $token=$user->createToken('acces_token')->plainTextToken;
            return response()->json([
                'token'=>$token,
            ]);
        }
        else{
        Auth::login($user);
        $token=$user->createToken('acces_token')->plainTextToken;
            return response()->json([
                'token'=>$token,
            ]);
        }
        // Redirect the user to the desired page after successful registration/login
    }
    public function logout(Request $request) {
    //   auth('web')->logout();
    //   auth('sanctum')->user()->currentAccessToken()->delete();
      $request->user()->currentAccessToken()->delete();
    // $request->user('api')->tokens()->delete();
    // Auth::guard('web')->logout();

    // $cookieKeys = [
    //     'laravel_session',
    //     'acces_token',
    //     'XSRF-TOKEN',
    // ];

    // foreach ($cookieKeys as $cookieKey) {
    //     $request->cookies->remove($cookieKey);
    // }
    // if (Cookie::has('remember_me')) {
    //     Cookie::queue(Cookie::forget('remember_me'));
    // }

    // // Logout the user
    // Auth::guard('web')->logout();


      return response()->json("user logged out successfully",200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $id)
    {
        $roles=Role::all();
        $user=User::with('role_user')->where('user.id',$id)->get();
        return response()->json(['roles'=>$roles,'user'=>$user], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $Request, $id)
    {
        $Request->validate([
            'firstName'=> 'required | string',
            'lastName'=> 'required | string',
            'email'=>'required | email',
            'password'=>'required | min:5 | confirmed',
            'image'=>'required | image'
        ]);

        $imageName=time().'.'. $request->image->extension();
        $request->image->move(public_path('profile') ,$imageName);

        User::where('id','=',$id)->update([
            'firstname'=>$Request->firstName,
            'lastname'=>$Request->lastName,
            'email'=>$Request->email,
            'password'=>bcrypt($Request->password),
            'path'=>$Request->imageName
            
        ]);
        return response()->json(['message'=>'user has been updated'], 200);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
