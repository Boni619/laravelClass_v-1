<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use Auth;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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

  public function login(Request $request)
  {
    //dd($request->toArray());

    $request->validate([
      'phone_no' => 'required|max:11',
      'password' => 'required',
    ]);

    $this->validateLogin($request);

    if ($this->hasTooManyLoginAttempts($request)) {
      $this->fireLockoutEvent($request);
      return $this->sendLockoutResponse($request);
    }

    $checkUser = User::where('phone_no', $request->phone_no)->first();

    /*---------Check user is exist or not-----------*/
    if (empty($checkUser)) {
      return redirect()->back()->withInput($request->input())->withErrors(['active' => 'This email id does not exist in our system.']);
    }

    $data = [];
    $data['email'] = $checkUser->email;
    $data['password'] = $request->password;
    if ($this->guard()->validate($this->credentials($data))) {

      $user = $this->guard()->getLastAttempted();

      // Make sure the user is active
      if ($this->attemptLogin($data)) {

        // Send the normal successful login response
        if (Auth::user()->hasRole('User')) {
          return redirect()->intended('home');
        }
      } else {
        $this->incrementLoginAttempts($request);
        return redirect()->back()->withInput($request->input())->withErrors(['active' => 'You email address or password is wrong. Please try again.']);
      }
    } else {
      return redirect()->back()->withInput($request->input())->withErrors(['active' => 'You email address or password is wrong. Please try again.']);
    }
  }
}
