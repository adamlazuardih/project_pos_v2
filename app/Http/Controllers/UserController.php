<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        return view('user.index');
    }

    public function data()
    {
        $user = User::isNotAdmin()->orderBy('id', 'DESC')->get();

        return datatables()
            ->of($user)
            ->addIndexColumn()
            ->addColumn('created_at', function ($user) {
                return tanggal_indonesia($user->created_at, true, true);
            })
            ->addColumn('aksi', function ($user) {
                return '
                <button type="button" onclick="editForm(`' . route('user.update', $user->id) . '`)" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i></button>
                <button type="button" onclick="deleteData(`' . route('user.destroy', $user->id) . '`)" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                ';
            })
            ->rawColumns(['aksi', 'nominal'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user =  new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->level = 2;
        $user->foto = '/images/avatar.png';
        $user->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::find($id);
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user =  User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->has('password') && $request->password != '')
            $user->password = $request->password;
        $user->update();

        return response()->json('Data berhasil di update', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        return response(null, 204);
    }

    public function profil()
    {
        $profil = Auth::user();
        return view('user.profil', compact('profil'));
    }

    public function update_profil(Request $request)
    {
        $user = $request->user();

        $user->name = $request->name;
        if ($request->has('password') && $request->password != "") {
            if (Hash::check($request->old_password, $user->password)) {
                if ($request->password == $request->password_confirmation) {
                    $user->password = bcrypt($request->password);
                } else {
                    return response()->json('Konfirmasi password tidak sesuai', 422);
                }
            } else {
                return response()->json('Password lama tidak sesuai', 422);
            }
        }

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $nama = 'profil-' . date('Y-m-dHis') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/images'), $nama);

            $user->foto = "/images/$nama";
        }

        $user->update();
        return response()->json($user, 200);
    }
}
