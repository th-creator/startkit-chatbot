<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AdminUser extends Controller
{
    public function index()
    {
        $users=User::all();
        return response()->json(['users'=>$users], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $Request)
    {
        $user = $Request->validate([
            'firstName'=> 'required | string',
            'lastName'=> 'required | string',
            'email'=>'required | email',
            'password'=>'required | min:5 | confirmed',
        ]);
        User::create([
            'firstName'=>$Request->firstName,
            'lastName'=>$Request->lastName,
            'email'=>$Request->email,
            'password'=>bcrypt($Request->password),
            'path'=>'default_profile.png'
        ]);
        return response()->json(['user'=>$user], 200);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user=User::find($id);
        return response()->json(['user'=>$user], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $Request, $id)
    {
        $Request->validate([
            'firstName'=> 'required | string',
            'lastName'=> 'required | string',
            'email'=>'required | email',
            'password'=>'required | min:5 | confirmed',
            'image'=>'required | image',
            'active'=>'required | boolean',
        ]);

        $imageName=time().'.'. $request->image->extension();
        $request->image->move(public_path('profile') ,$imageName);

        User::where('id','=',$id)->update([
            'firstname'=>$Request->firstName,
            'lastname'=>$Request->lastName,
            'email'=>$Request->email,
            'password'=>bcrypt($Request->password),
            'path'=>$Request->imageName,
            'active'=>$Request->active,
            
        ]);
        return response()->json(['message'=>'user has been updated'], 200);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User::find($id)->delete();
        return response()->json(['message'=>'user has been deleted'], 200);
    }

}
