<?php 

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Firebase\JWT\JWT;

class AuthController extends Controller
{
	protected function jwt(User $user) {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued. 
            'exp' => time() + 60*60*24*7 // Expiration time
        ];
        
        // As you can see we are passing `JWT_SECRET` as the second parameter that will 
        // be used to decode the token in the future.
        return JWT::encode($payload, env('JWT_SECRET'));
    } 

	public function store(Request $request)
	{
		$this->validate($request, [
			'first_name' => 'required',
			'last_name' => 'required',
			'email' => 'required|email',
			'password' => 'required|min:6'
		]);

		$first_name = $request->input('first_name');
		$last_name = $request->input('last_name');
		$email = $request->input('email');
		$password = $request->input('password');

		$user = new User([
			'first_name' => $first_name,
			'last_name' => $last_name,
			'email' => $email,
			'password' => Crypt::encrypt($password)
		]);

		if ($user->save()) {
			$user->signin = [
				'href' => 'api/v1/user/signin',
				'method' => 'POST',
				'params' => 'email, password'
			];
			$response = [
				'msg' => 'User created',
				'result' => $user
			];
			return response()->json($response, 201);
		}

		$response = [
			'msg' => 'An error occured'
		];
		return response()->json($response, 404);
	}

	public function signin(Request $request)
	{
		$this->validate($request, [
			'email' => 'required|email',
			'password' => 'required'
		]);

		$user = User::where('email', $request->input('email'))->first();

		if (!$user) {
            return response()->json([
                'error' => 'Email does not exist.'
            ], 400);
        }

        if ( $request->input('password') == Crypt::decrypt($user->password) ) 
        {
            return response()->json([
                'token' => $this->jwt($user)
            ], 200);
        }

		return response()->json([
            'error' => 'Email or password is wrong.'
        ], 400);
	}
}