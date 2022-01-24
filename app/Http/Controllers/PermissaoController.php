<?php

namespace App\Http\Controllers;

use App\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PermissaoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        Session::put('url','permissoes');
    }

    public function index()
    {
        $permissions = Permission::all();
        return view('permissoes/index', compact('permissions'));
    }

    public function users($id)
    {
        $permission = Permission::find($id);
        return view('permissoes/users', compact('permission'));
    }

    public function perfis($id)
    {
        $permission = Permission::find($id);
        $perfis = $permission->roles;
        return view('permissoes/perfis', compact('perfis'));
    }
}