<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::firstOrCreate(['name' => 'admin']);

        $user = new User();
        $user->firstName = 'test';
        $user->lastName = 'example';
        $user->email = 'test@example.com';
        $user->password = bcrypt('example');
        
        $user->save();

        $user->roles()->attach($role);
    }
}
