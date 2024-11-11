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
                'name' => 'Owner',
                'email' => 'admin@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kepala Gudang',
                'email' => 'gudang123@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin Gudang',
                'email' => 'gudang@gmail.com',
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
                'nama_role' => 'Owner',
                'kode_role' => 'owner',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_role' => 'Kepala Gudang',
                'kode_role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_role' => 'Gudang',
                'kode_role' => 'gudang',
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
            [
                'role_id' => 4,
                'user_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('satuan')->insert([
            [
                'name' => 'PCS',
                'user_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'SET',
                'user_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('barang')->insert([
            ['user_id' => 2, 'deskripsi' => 'GRACO Pump G3 12 L , 24 VDC', 'part_number' => '96G199', 'stok' => 0, 'satuan_id' => 1, 'created_at' => '2024-11-05 08:21:10', 'updated_at' => '2024-11-06 16:28:16'],
            ['user_id' => 2, 'deskripsi' => 'Panel Electric', 'part_number' => 'R-PNL-220-24 G3', 'stok' => 10, 'satuan_id' => 1, 'created_at' => '2024-11-05 08:21:28', 'updated_at' => '2024-11-08 12:38:11'],
            ['user_id' => 2, 'deskripsi' => 'GRACO Element Pump', 'part_number' => '571041', 'stok' => 20, 'satuan_id' => 1, 'created_at' => '2024-11-05 08:21:40', 'updated_at' => '2024-11-08 16:58:10'],
            ['user_id' => 2, 'deskripsi' => 'GRACO Pressure Reliefe Valve', 'part_number' => '563161', 'stok' => 80, 'satuan_id' => 1, 'created_at' => '2024-11-05 08:23:36', 'updated_at' => '2024-11-08 16:58:10'],
            ['user_id' => 2, 'deskripsi' => 'GRACO Adapter Kit', 'part_number' => '571058', 'stok' => 0, 'satuan_id' => 1, 'created_at' => '2024-11-05 08:23:49', 'updated_at' => '2024-11-06 17:19:27'],
            ['user_id' => 2, 'deskripsi' => 'Box Pump 12 Liter', 'part_number' => 'BOX-12-ATLB', 'stok' => 0, 'satuan_id' => 1, 'created_at' => '2024-11-05 08:24:02', 'updated_at' => '2024-11-06 20:09:06'],
            ['user_id' => 2, 'deskripsi' => 'Braket Box Pump', 'part_number' => 'BRK-12-ATLB', 'stok' => 0, 'satuan_id' => 1, 'created_at' => '2024-11-05 08:24:15', 'updated_at' => '2024-11-06 20:09:06'],
            ['user_id' => 2, 'deskripsi' => 'Braket Panel Electric', 'part_number' => 'BRK-EL-ATLB', 'stok' => 0, 'satuan_id' => 1, 'created_at' => '2024-11-05 08:24:27', 'updated_at' => '2024-11-05 08:24:27'],
            ['user_id' => 2, 'deskripsi' => 'GRACO Controller GLC2200', 'part_number' => '24N468', 'stok' => 20, 'satuan_id' => 1, 'created_at' => '2024-11-05 08:24:38', 'updated_at' => '2024-11-08 16:58:10'],
            ['user_id' => 2, 'deskripsi' => 'GRACO Multiple Connector GLC2200', 'part_number' => '24P686', 'stok' => 0, 'satuan_id' => 1, 'created_at' => '2024-11-05 08:24:49', 'updated_at' => '2024-11-05 08:24:49'],
            ['user_id' => 2, 'deskripsi' => 'GRACO MSP Baseplate 3 Section', 'part_number' => '24G485', 'stok' => 0, 'satuan_id' => 1, 'created_at' => '2024-11-05 08:25:00', 'updated_at' => '2024-11-05 08:25:00'],
            ['user_id' => 2, 'deskripsi' => 'GRACO MSP Working Section 30 T', 'part_number' => '562725', 'stok' => 0, 'satuan_id' => 1, 'created_at' => '2024-11-05 08:25:21', 'updated_at' => '2024-11-05 08:25:21'],
            ['user_id' => 2, 'deskripsi' => 'MALE CONNECTOR', 'part_number' => 'A2-GE10L1/4NPTCF', 'stok' => 0, 'satuan_id' => 1, 'created_at' => '2024-11-05 08:27:21', 'updated_at' => '2024-11-05 08:27:21'],
            ['user_id' => 2, 'deskripsi' => 'MALESTUD ELBOW 90', 'part_number' => 'A2-WE10L1/4NPTCF', 'stok' => 0, 'satuan_id' => 1, 'created_at' => '2024-11-05 08:27:33', 'updated_at' => '2024-11-05 08:27:33'],
            ['user_id' => 2, 'deskripsi' => 'MALE CONNECTOR', 'part_number' => 'A2-GE10L1/8NPTCF', 'stok' => 0, 'satuan_id' => 1, 'created_at' => '2024-11-05 08:27:46', 'updated_at' => '2024-11-05 08:27:46'],
            ['user_id' => 2, 'deskripsi' => 'UNION STRAIGHT', 'part_number' => 'A2-G10LCF', 'stok' => 0, 'satuan_id' => 1, 'created_at' => '2024-11-05 08:28:29', 'updated_at' => '2024-11-05 08:28:29'],
            ['user_id' => 2, 'deskripsi' => 'ADAPTER', 'part_number' => 'A2-4FTX-S', 'stok' => 0, 'satuan_id' => 1, 'created_at' => '2024-11-05 08:28:42', 'updated_at' => '2024-11-05 08:28:42'],
            ['user_id' => 2, 'deskripsi' => 'ADAPTER M8 X JIC 1/4', 'part_number' => 'A2-4XM08', 'stok' => 0, 'satuan_id' => 1, 'created_at' => '2024-11-05 08:30:38', 'updated_at' => '2024-11-05 08:30:38'],
            ['user_id' => 2, 'deskripsi' => 'CLAMP 10 MM', 'part_number' => 'RAP2-310', 'stok' => 0, 'satuan_id' => 1, 'created_at' => '2024-11-05 08:30:52', 'updated_at' => '2024-11-05 08:30:52'],
            ['user_id' => 2, 'deskripsi' => 'HOSE ASSY 1/4" RO10L X JIC 04 L WITH WIRECOVER 70 CM', 'part_number' => 'HS-RO10L-04JIC-ATLB', 'stok' => 0, 'satuan_id' => 2, 'created_at' => '2024-11-05 08:31:13', 'updated_at' => '2024-11-05 08:31:13'],
            ['user_id' => 2, 'deskripsi' => 'BRAKET MSP BENDING', 'part_number' => 'BRK-MSP-ATLB', 'stok' => 0, 'satuan_id' => 1, 'created_at' => '2024-11-05 08:31:24', 'updated_at' => '2024-11-06 17:19:27'],
            ['user_id' => 2, 'deskripsi' => 'SEAMLESS PIPE 10 MM', 'part_number' => 'R10X1,5X6000', 'stok' => 0, 'satuan_id' => 1, 'created_at' => '2024-11-05 08:31:38', 'updated_at' => '2024-11-05 08:31:38'],
        ]);
    }
}
