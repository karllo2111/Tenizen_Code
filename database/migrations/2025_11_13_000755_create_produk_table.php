<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_produk_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->string('idproduk')->primary();
            $table->string('namaproduk');
            $table->integer('jumlah');
            $table->decimal('harga', 10, 2);
            $table->string('barcode')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('produk');
    }
};