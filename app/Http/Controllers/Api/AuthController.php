<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

use JWTAuth;

class AuthController extends Controller {

    public function login() {
        $credentials = request( [ 'email', 'password' ] );

        if ( !$token = auth()->attempt( $credentials ) ) {
            return response()->json( [ 'error' => 'Não autorizado, verfique os seus dados' ], 401 );
        }

        return [
            'mensagem' => 'You successfully logged!',
            'user' => auth()->user(),
            'token' => $token,
        ];
    }
    
    public function me () {
        return auth()->user();
    }

    public function signup( Request $request ) {

        $validator = validator::make( $request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ], [
            'name.required' => 'O campo nome é obrigatório.',
            'name.string' => 'O campo nome deve ser uma string.',
            'email.required' => 'O campo de email é obrigatório.',
            'email.email' => 'O email fornecido não é válido.',
            'email.unique' => 'Não foi possível processar este email.',
            'password.required' => 'A senha é obrigatória.',
            'password.string' => 'A senha deve ser uma string.',
            'password.min' => 'A senha deve ter pelo menos 6 caracteres.',
        ] );

        if ( $validator->fails() ) {
            return response()->json( $validator->errors(), 422 );
        }

        $user = new User( [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt( $request->password ),
        ] );

        $user->save();

        // Autentique o usuário recém-criado e gere um token
        $token = auth()->login( $user ) ;

        return [
            'mensagem' => 'You successfully logged!',
            'user' => auth()->user(),
            'token' => $token,
        ];
    }

    public function logout() {
        auth()->logout();
        return response()->json( [ 'message' => 'Successfully logged out' ] );
    }

    protected function respondWithToken( $token ) {
        return response()->json( [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ] );
    }
}
