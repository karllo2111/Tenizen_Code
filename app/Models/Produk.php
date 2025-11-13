<?php
// app/Models/Produk.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;
    protected $table = 'produk';
    protected $primaryKey = 'idproduk';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'idproduk',
        'namaproduk',
        'jumlah',
        'harga',
        'barcode',
    ];
}
