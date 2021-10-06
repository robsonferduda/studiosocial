<?php

namespace App\Http\Controllers;

use App\User;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        Session::put('url','usuarios');
    }

    public function index()
    {
        $usuarios = User::orderBy('name')->get();
        return view('usuarios/index', compact('usuarios'));
    }

    public function show(User $user, $id)
    {
        $user = User::find($id);
        return view('usuarios/perfil', compact('user'));
    }

    public function perfil()
    {
        return view('usuarios/perfil');
    }

    public function create()
    {
        return view('usuarios/novo');
    }

    public function edit($id)
    {
        return view('usuarios/editar');
    }

    public function destroy(User $user)
    {
        Flash::warning("Dados de teste! Não é possível excluir o usuário.");
        return redirect('usuarios');
    }
}