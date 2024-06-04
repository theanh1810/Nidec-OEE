<?php

namespace App\Http\Controllers\Api\Account;

use App\Http\Controllers\Controller;
use App\Models\History\History;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $username      = $request->username;
        $id_user      = $request->id_user;
        $lvl_user      = $request->lvl_user;
        if ($lvl_user == 9999) {
            $users = User::where('IsDelete', 0)
                ->where('level', '!=', 9999)
                ->where('id', '<>', 2)
                ->when($username, function ($query, $username) {
                    return $query->where('username', $username);
                })
                ->orderBy('id', 'desc')
                // ->get();
                ->paginate($request->length);
        } else {
            $users = User::where('IsDelete', 0)
                ->where('level', '!=', 9999)
                ->where('id', $id_user)
                ->when($username, function ($query, $username) {
                    return $query->where('username', $username);
                })
                ->orderBy('id', 'desc')
                // ->get();
                ->paginate($request->length);
        }
        // dd($users);
        // dd($name,$symbols,$masterMachine);
        return response()->json([
            'recordsTotal' => $users->total(),
            'recordsFiltered' => $users->total(),
            'data' => $users->toArray()['data']
        ]);
    }

    public function index_role(Request $request)
    {
        $username      = $request->username;
        $roles = Role::where('IsDelete', 0)
            ->orderBy('id', 'asc')
            ->paginate($request->length);
        // dd($roles);
        return response()->json([
            'recordsTotal' => $roles->total(),
            'recordsFiltered' => $roles->total(),
            'data' => $roles->toArray()['data']
        ]);
    }

    public function history(Request $request)
    {
        $users = History::where('Table_Name', 'user_role')
            ->orderBy('ID', 'desc')
            // ->get();
            ->paginate($request->length);
        // dd($users);
        return response()->json([
            'recordsTotal' => $users->total(),
            'recordsFiltered' => $users->total(),
            'data' => $users->toArray()['data']
        ]);
    }
}
