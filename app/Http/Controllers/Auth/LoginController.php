<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }
   
    /**
     * Check credentials and redirect.
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if($this->guard()->validate($this->credentials($request))) {
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password, 'status' => '1'])) {
                $this->clearLoginAttempts($request);
                return redirect()->route('dashboard');
            }

            $this->incrementLoginAttempts($request);
            return redirect()->back()->withInput()
                ->withErrors(['email' => 'This account is not activated, please contact your administration.']);
        }

        $this->incrementLoginAttempts($request);
        return redirect()->back()->withInput()
            ->withErrors(['email' => 'These credentials do not match our records.']);
    }

    /**
     * Logout user.
     */
    public function logout()
    {
        if(session()->has('accessModules')) {
            session()->flush();
        }

        Auth::logout();
        return redirect()->route('login');
    }
}
