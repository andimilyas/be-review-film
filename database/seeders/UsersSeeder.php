<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Roles; 
use App\Models\Users;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $AdminRoles = Roles::where('name', 'admin')->first();

        Users::create(
            [
                'name' => 'Andi',
                'email' => 'andi@yahoo.com',
                'password' => Hash::make('password'),
                'password_confirmation' => Hash::make('password'),
                'role_id' => $AdminRoles->id
            ]
        );
    }
}
