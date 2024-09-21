<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Super Admin',
                'email' => 'admin123@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('admindemo'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kasir',
                'email' => 'kasir@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('roles')->insert([
            [
                'nama_role' => 'Super Admin',
                'kode_role' => 'superadmin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_role' => 'Admin',
                'kode_role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_role' => 'Kasir',
                'kode_role' => 'kasir',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('privilages')->insert([
            [
                'role_id' => 1,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 2,
                'user_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 3,
                'user_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
