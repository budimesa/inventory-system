<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index() {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function getUserList(Request $request)
    {
        if ($request->ajax()) {
            $data = User::orderBy('id', 'DESC')->get();
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {                   
                    $buttons = '
                        <div class="g-2">
                        <a class="btn modal-effect text-primary btn-sm btn-edit" data-toggle="modal" href="#Umodaldemo8" data-toggle="tooltip" data-original-title="Edit" data-edit=\''.json_encode($row).'\'><span class="fas fa-edit text-success fs-14"></span></a>
                        <a class="btn modal-effect text-danger btn-sm" data-toggle="modal" href="#modalDemoDestroy" onclick=confirmDeleteItem(' . $row->id . ')><span class="fas fa-trash fs-14"></span></a>
                        </div>
                    ';
                    return $buttons;
                })
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('Y-m-d');
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'email'     => 'required|unique:users,email',
            'password'  => 'required',
        ]);

        User::create([
            'name' => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
        ]);

        return response()->json(['success' => 'Berhasil']);
    }

    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'name'  => 'required',
            'email' => [
            'required',
                Rule::unique('users', 'email')->ignore($id)
            ],
        ]);

        $data = User::findOrFail($id);
        $data->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        return response()->json(['success' => 'Berhasil']);
    }

    public function deleteUser(Request $request, $id)
    {
        $data = User::findOrFail($id);
        $data->delete();

        return response()->json(['message' => 'Data deleted successfully']);
    }

    // Method untuk menampilkan modal ganti password
    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }

    // Method untuk mengubah password
    public function changePassword(Request $request)
    {
        // Validasi form
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|different:current_password',
            'new_password_confirmation' => 'required|string|min:8|same:new_password',
        ]);

        // Mengambil user saat ini
        $user = Auth::user();

        // Memeriksa apakah password saat ini cocok
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['error' => 'Password saat ini salah.'], 422);
        }

        // Mengubah password user
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['success' => 'Password berhasil diubah.']);
    }
}
