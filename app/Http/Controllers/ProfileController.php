<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        return view('profile.show', [
            'user' => Auth::user()
        ]);
    }

    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user()
        ]);
    }

   public function update(Request $request)
{
   

    $user = User::find(Auth::id());

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        'phone' => 'nullable|string|max:15',
        'address' => 'nullable|string|max:255',
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    // Proses upload avatar jika ada
   if ($request->hasFile('avatar')) {
    if ($user->avatar && $user->avatar !== 'default.png') {
        Storage::delete('public/profile_pictures/' . $user->avatar);
    }
    
    $validated['avatar'] = $request->file('avatar')->store('profile_pictures', 'public');
}




    $user->update($validated); // sudah mencakup avatar dan field lainnya

    return redirect()->route('profile.show')
        ->with('success', 'Profil berhasil diperbarui');
}


    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::find(Auth::id());

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password saat ini tidak sesuai');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('profile.show')
            ->with('success', 'Password berhasil diubah');
    }
}