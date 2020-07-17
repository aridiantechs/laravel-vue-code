<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
          //validate incoming request 
        $vali = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
        if($vali->fails()){
            return api_response((object)[],400,'',$vali->errors());
        }

        $credentials = $request->only(['email', 'password']);

        if (! $token = Auth::attempt($credentials)) {
    
            return api_response((object)[],401,'Credentials are invalid!');
        }
        
        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        if(auth()->user()){
            return api_response(self::authUserWithPermissions());
        }else{
            return api_response((object)[],401,'Session Expired');
        }
        
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return api_response([
            'user' => self::authUserWithPermissions(),
            'token' => 'bearer '.$token,
            'expires_in' => Auth::factory()->getTTL() * 60,
        ], 200);
    }

    /**
     * Store a new user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function register(Request $request)
    {
        //validate incoming request 
        $vali = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);
        if($vali->fails()){
            return api_response((object)[],400,'',$vali->errors());
        }

        try {

            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $plainPassword = $request->input('password');
            $user->password = app('hash')->make($plainPassword);

            $user->save();

            //return successful response
            return api_response($user, 200,'User Registered.');

        } catch (\Exception $e) {
            //return error message
            return api_response((object)[], 200,'User Registration Failed.',400);
        }

    }

     /**
     * Profile Update
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profileUpdate(Request $request)
    {
          //validate incoming request 
        $vali = Validator::make($request->all(), [
            'name' => 'required|string',
            'avatar' => 'nullable|image',
        ]);

        if($vali->fails()){
            return api_response((object)[],400,'',$vali->errors());
        }

        $user = auth()->user();
        $user->name = $request->name;
        if($request->hasFile('avatar')){
            $user->avatar = \App\Helpers\AppHelper::fileUpload($request->avatar,[
                            'delete' => true,
                            'delete_file' => $user->avatar,
                            'delete_remove_path' => '/storage/',
                        ]);
        }
        
        $user->save();

        return api_response($user);
    }

    /**
     * Profile Update
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function passwordUpdate(Request $request)
    {
          //validate incoming request 
        $vali = Validator::make($request->all(), [
            'password_current' => 'required|string',
            'password' => 'required|confirmed',
        ]);

        if($vali->fails()){
            return api_response((object)[],400,"New password doesn't match.",$vali->errors());
        }

        if ( \Illuminate\Support\Facades\Hash::check($request->password_current, auth()->user()->password)) {

            auth()->user()->password = bcrypt($request->password);

            auth()->user()->save();
          
          return api_response("",200,"Password has been updated.");
        }else{
            return api_response((object)[],400,"Current Password doesn't match.");
        }
    }


    /**
     * Get Roles and Permissions
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function permissionsAndRoles(Request $request)
    {
        if (auth()->user()) {
          return api_response([
            'roles' => auth()->user()->getRoleNames(),
            'permissions' => auth()->user()->allPermissions(),
          ]);
        }
    }


    /**
     * authUserWithPermissions
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function authUserWithPermissions()
    {
        $user = auth()->user()->toArray();
        $user['roles'] = auth()->user()->getRoleNames();
        $user['permissions'] =  auth()->user()->allPermissions();
        
        return $user;
    }


}
