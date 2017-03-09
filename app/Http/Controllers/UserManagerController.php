<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserManagerController extends Controller
{
    /**
     * All all actions require a user 1to be logged in...
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing placeholder of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        if (Gate::denies('manage-users-list')) {
            abort(403, 'Unauthorized action');
        }
        return view('users.admin.list');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Gate::denies('manage-users-list')) {
            abort(403, 'Unauthorized action');
        }

        $items = User::latest()->paginate(5);

        $response = [
            'pagination' => [
                'total'        => $items->total(),
                'per_page'     => $items->perPage(),
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'from'         => $items->firstItem(),
                'to'           => $items->lastItem()
            ],
            'data' => $items
        ];

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Gate::denies('manage-users-create')) {
            abort(403, 'Unauthorized action');
        }

        $this->validate($request, [
            'name'     => 'required|max:100',
            'email'    => 'required|max:100|email|unique:users',
            'password' => 'required|max:100',
        ]);

        $create = User::create($request->all());
        return response()->json($create);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id user id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (Gate::denies('manage-users-update')) {
            abort(403, 'Unauthorized action');
        }

        $user = User::find($id);

        if ($request->user()->id == $user->id) {
            // 403 - unauthorized action
            return response()->json(['_common' => ['You cannot edit yourself, period.']], 403);
        }

        $rules = [
            'name'     => 'required|max:100'
        ];

        // validate email change
        if ($request->get('email') !== $user->email) {
            $rules['email'] = 'required|max:100|email|unique:users';
        }
        // validate password if it arrives
        if ($request->get('password')) {
            $rules['password'] = 'required|max:100';
        }
        $this->validate($request, $rules);

        $user->update($request->all());
        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id user id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if (Gate::denies('manage-users-delete')) {
            abort(403, 'Unauthorized action');
        }

        $user = User::find($id);

        if ($request->user()->id == $user->id) {
            // 403 - unauthorized action
            return response()->json(['_common' => ['You cannot edit yourself, period.']], 403);
        }

        $user->delete();
        return response()->json(['done']);
    }
}
