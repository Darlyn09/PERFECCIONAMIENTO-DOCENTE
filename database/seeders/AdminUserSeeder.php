<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar si el usuario ya existe para no duplicarlo o causar error
        if (!User::where('name', 'admin')->exists()) {
            User::create([
                'name' => 'admin',
                'email' => 'admin@admin.com', // Email ficticio
                'password' => Hash::make('admin123'),
            ]);
        }
    }
}
