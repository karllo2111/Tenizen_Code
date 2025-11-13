<?php
// database/seeders/SiswaSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiswaSeeder extends Seeder
{
    public function run()
    {
        $siswas = [
            [
                'nis' => '2024001',
                'namasiswa' => 'Ahmad Rizki',
                'jk' => 'L',
                'alamat' => 'Jl. Merdeka No. 123, Jakarta',
                'tanggallahir' => '2008-03-15',
                'foto' => 'default_siswa.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nis' => '2024002', 
                'namasiswa' => 'Siti Nurhaliza',
                'jk' => 'P',
                'alamat' => 'Jl. Sudirman No. 45, Bandung',
                'tanggallahir' => '2008-07-22',
                'foto' => 'default_siswa.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nis' => '2024003',
                'namasiswa' => 'Budi Santoso',
                'jk' => 'L', 
                'alamat' => 'Jl. Gajah Mada No. 67, Surabaya',
                'tanggallahir' => '2007-11-08',
                'foto' => 'default_siswa.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nis' => '2024004',
                'namasiswa' => 'Maya Sari',
                'jk' => 'P',
                'alamat' => 'Jl. Thamrin No. 89, Medan',
                'tanggallahir' => '2008-01-30',
                'foto' => 'default_siswa.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nis' => '2024005',
                'namasiswa' => 'Rizki Pratama',
                'jk' => 'L',
                'alamat' => 'Jl. Diponegoro No. 12, Yogyakarta',
                'tanggallahir' => '2007-12-10',
                'foto' => 'default_siswa.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($siswas as $siswa) {
            DB::table('siswa')->insert($siswa);
        }

        echo "Siswa seeder berhasil! 5 data siswa telah ditambahkan.\n";
    }
}