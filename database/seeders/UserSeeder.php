<?php
// database/seeders/UserSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'username' => 'admin',
                'email' => 'admin@sekolah.sch.id',
                'password' => md5('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'guru1',
                'email' => 'guru1@sekolah.sch.id', 
                'password' => md5('guru123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'siswa1',
                'email' => 'siswa1@sekolah.sch.id',
                'password' => md5('siswa123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            DB::table('user')->insert($user);
        }

        echo "User seeder berhasil! 3 user telah ditambahkan.\n";
    }
}