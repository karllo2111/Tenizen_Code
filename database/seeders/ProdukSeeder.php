<?php
// database/seeders/ProdukSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdukSeeder extends Seeder
{
    public function run()
    {
        $produks = [
            [
                'idproduk' => 'PRD001',
                'namaproduk' => 'Buku Tulis 38 Lembar',
                'jumlah' => 100,
                'harga' => 5000.00,
                'barcode' => 'default_barcode.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idproduk' => 'PRD002',
                'namaproduk' => 'Pensil 2B',
                'jumlah' => 150, 
                'harga' => 2500.00,
                'barcode' => 'default_barcode.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idproduk' => 'PRD003',
                'namaproduk' => 'Pulpen Standard',
                'jumlah' => 200,
                'harga' => 3000.00,
                'barcode' => 'default_barcode.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idproduk' => 'PRD004',
                'namaproduk' => 'Penghapus',
                'jumlah' => 80,
                'harga' => 2000.00,
                'barcode' => 'default_barcode.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idproduk' => 'PRD005',
                'namaproduk' => 'Penggaris 30cm',
                'jumlah' => 60,
                'harga' => 4000.00,
                'barcode' => 'default_barcode.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($produks as $produk) {
            DB::table('produk')->insert($produk);
        }

        echo "Produk seeder berhasil! 5 data produk telah ditambahkan.\n";
    }
}