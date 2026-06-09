<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

use Illuminate\Routing\Controller as BaseController;

class UserController extends BaseController
{
    public function __construct()
    {
        // SOLO JEFE
        $this->middleware(function ($request, $next) {
            if (auth()->user()->rol !== 'JEFE') {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index()
    {
        return Inertia::render('Users/Index', [
            'usuarios' => User::latest()->paginate(10)
        ]);
    }

    public function create()
    {
        return Inertia::render('Users/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|min:6',
            'rol' => 'required|in:JEFE,ASESOR'
        ]);

        User::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'rol' => $request->rol
        ]);

        return redirect()->route('users.index');
    }

    public function edit(User $user)
    {
        return Inertia::render('Users/Edit', [
            'usuario' => $user
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => "required|email|unique:usuarios,email,{$user->id}",
            'rol' => 'required|in:JEFE,ASESOR'
        ]);

        $data = [
            'nombre' => $request->nombre,
            'email' => $request->email,
            'rol' => $request->rol
        ];

        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors('No puedes eliminarte');
        }

        $user->delete();
        return back();
    }
}