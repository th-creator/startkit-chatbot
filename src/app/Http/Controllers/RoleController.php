<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class RoleController extends Controller
{
    public function store(Request $Request)
    {
        $role = $Request->validate([
            'name'=> 'required|string|max:255|unique:roles,name,',
        ]);
        Role::create([
            'name'=>$Request->name,
        ]);

        return response()->json(['message' => 'Role created successfully', 'role' => $role], 201);
    }
    public function SetRole(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            //the role here could be an array
            'role_id' => 'array|nullable|exists:roles,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
    
        $user = User::find($request->input('user_id'));
        $role = Role::find($request->input('role_id'));
    
        if ($user && $role) {
            $user->roles()->attach($role);
            return response()->json(['message' => 'Role attached to user successfully']);
        }
    
        return response()->json(['error' => 'User or Role not found'], 404);
    
    }
    public function RemoveRole(Request $request){
        $user = User::find($request->input('user_id'));
        $user->roles()->detach($request->input('role_id'));
        return response()->json(['message'=>'role detached successfully'], 200);
        
    }
    public function updateRole($roleId ,Request $request)
    {
        $role = Role::findOrFail($roleId);

        $role->update([
            'name' => $request->input('name'),
            // Update other attributes as necessary
        ]);
    
        return response()->json(['message' => 'Role updated successfully']);
    }
    public function updateRoleUser($userId, $roleId)
    {
        $roleId->validate([
            'name'=> 'array|nullable|string|max:255|unique:roles,name,',
        ]);
        $user=User::where('id',$userId)->first();

        $user->roles()->detach();
 
        $user->roles()->attach($roleId);

        return response()->json(['message' => 'Role updated for user successfully'], 200);
    }
    public function RemoveAllRoles(Request $request){
        $user = User::find($request->input('user_id'));
        $user->roles()->detach();
        return response()->json(['message'=>'Roles deleted for user successfully'], 200);
    }
    public function destroy($roleId)
    {
        $role = Role::findOrFail($roleId);
        $role->delete();
        return response()->json(['message' => 'Role deleted successfully'], 200);
    }
}
