<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        // Nilai tetap
        $judul = trans('auth.profile_settings');
        $user = Auth::user();

        return view('admin.profile', compact('judul', 'user'));
    }

    public function update(Request $request, int $id)
    {
        $data = User::firstWhere('id', $id);

        $rules = [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,'.$data->id,
            'password' => 'required|confirmed',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all);
        }
        else {
            if (Hash::check($request->input('password'), $data->password))
            {
                $data->name = $request->input('name');
                $data->email = $request->input('email');

                $data->save();

                return back()->with('success', 'Data Berhasil Diubah!');
            }
            else{
                return back()->with('error', 'Password Salah!');
            }

        }
    }
}
