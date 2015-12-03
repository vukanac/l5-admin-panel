<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;

class UserController extends Controller
{
    /**
     * The user repository instance.
     */
    protected $users;

    /**
     * Create a new controller instance.
     *
     * @param  UserRepository  $users
     * @return void
     */
    // public function __construct(UserRepository $users)
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'asc')->get();

        return view('users.index', [
            'users' => $users,
            'roles' => \App\Role::getAllRoles(),
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
            //'role' => 'not_in:owner',
        ]);

        // Create The User...
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);

        $user->save();

        return redirect('/users');
        
    }

    /**
     * Show the profile for the given user.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $this->authorize('show-user', $user);

        return view('users.show', [
            'user' => $user
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, User $user)
    {
        $userLogged = $request->user();

        $this->authorize('update-user', $user);
        
        return view('users.edit', [
            'user' => $user,
            'roles' => \App\Role::getAllRoles(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User  $user1
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user1)
    {
        $this->authorize('update-user', $user1);

        $conditionsArr = [
            'name' => 'required|max:255',
        ];

        if ($user1->email !== $request->input('email')) {
            $conditionsArr['email'] = 'required|email|max:255|unique:users';
        }

        // check do we change password
        $passwordPost = $request->input('password');
        $changePass = !empty($passwordPost);
        if ($changePass) {
            $conditionsArr['password'] = 'required|confirmed|min:6';
        }

        $this->validate($request, $conditionsArr);

        $user1->name = $request->input('name');
        $user1->email = $request->input('email');
        $user1->role = $request->input('role');

        // change password
        if ($changePass) {
            $user1->password = bcrypt($request->input('password'));
        }

        $user1->save();

        return redirect('/users');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('destroy-user', $user);

        $user->delete();
        
        return redirect('/users');
    }
    
}
