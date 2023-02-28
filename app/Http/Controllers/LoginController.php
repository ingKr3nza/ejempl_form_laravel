<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash; 
// PARA CONTRASEÑAS, UNA CONTRASEÑA NO SE PUEDE SUBIR COMO TEXTO PLANO POR LO TANTO IMPORTAMOS LA CLASE DE HASH 
// QUE ES LA QUE NOS PERMITE CIFRAR LA CONTRASEÑA con "Hash::make"
use Illuminate\Support\Facades\Auth;
// PARA EL PROCESO DE AUTENTICACIÓN DE LOGIN Y REGISTRO

class LoginController extends Controller
{
    public function register(Request $request)
    {
        //Ojo Validar los datos antes
        $user= new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        Auth::login($user);

        return redirect(route('privada'));
    }
    public function login(Request $request)
    {
        //Validación primero ojo.
        $credentials=[
            "email" => $request->email,
            "password" => $request->password,
            //"active"=>true
        ];

        $remember=($request->has('remember') ? true : false);

        if(Auth::attempt($credentials,$remember)){
            $request->session()->regenerate();
            return redirect()->intended('privada');
        }else{
            return redirect('login');
        }

    }
    public function logout(Request $request)
    {
        Auth::logout();
        //Resetear la sesión para evitar que quede guardada 
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect(route('login'));
    }
}
