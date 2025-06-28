<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:user_profiles',
            'phone' => 'required|unique:user_profiles',
            'password' => 'required|confirmed',
            'document' => 'file|mimes:pdf,jpg,png|max:2048',
        ]);

        $documentPath = $request->file('document')?->store('documents');

        UserProfile::create([
            'name' => $request->name,
            'address' => $request->address,
            'email' => $request->email,
            'phone' => $request->phone,
            'document_path' => $documentPath,
            'password' => Hash::make($request->password),
            'security_question' => $request->security_question,
            'security_answer' => $request->security_answer,
        ]);

        return redirect()->route('register')->with('message', 'Registered successfully!');
    }

    public function dashboard($id)
    {
        return response()->json(UserProfile::findOrFail($id));
    }
}
