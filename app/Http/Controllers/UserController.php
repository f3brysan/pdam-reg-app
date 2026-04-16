<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('roles')->get();
        $roles = Role::whereNotIn('name', ['user'])->get();

        if ($request->ajax()) {
            return DataTables::of($users)
                ->addColumn('role', function ($row) {
                    return $row->roles->pluck('name')->implode(', ');
                })
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $action = '';
                    if (! $row->hasRole('user')) {
                        $action .= '<a href="javascript:void(0)" data-id="'.$row->id.'" class="btn btn-primary btn-sm mr-1 edit-btn">Ubah</a>';
                        $action .= '<a href="javascript:void(0)" class="btn btn-danger btn-sm delete-btn" data-id="'.$row->id.'">Hapus</a>';
                    }
                    return $action;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('users.index', compact('roles'));
    }

    public function show($id)
    {
        try {
            $user = User::with('roles')->findOrFail($id);
            $firstRole = $user->roles->first();
            $user->role = $firstRole ? $firstRole->name : null;

            return response()->json([
                'success' => true,
                'message' => 'User berhasil ditemukan',
                'data' => $user,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'role' => 'required|string|exists:roles,name',
            ];
            
            if (empty($request->id)) {
                $rules['password'] = 'required|string|min:8|confirmed';
            } else {
                $rules['email'] = 'required|email|unique:users,email,'.$request->id;
                $rules['password'] = 'nullable|string|min:8|confirmed';
            }

            $validate = Validator::make($request->all(), $rules);

            if ($validate->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validate->errors()->first(),
                ], 400);
            }

            if ($request->filled('id')) {
                $user = User::findOrFail($request->id);
                $user->name = $request->name;
                $user->email = $request->email;
                if ($request->filled('password')) {
                    $user->password = Hash::make($request->password);
                }
                $user->save();
            } else {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);
            }

            $user->syncRoles([$request->role]);

            return response()->json([
                'success' => true,
                'message' => 'User berhasil disimpan',
                'data' => $user,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            if ($user->id === auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus user yang sedang login.',
                ], 400);
            }
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User berhasil dihapus',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
