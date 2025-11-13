<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_siswa_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->string('nis')->primary();
            $table->string('namasiswa');
            $table->enum('jk', ['L', 'P']);
            $table->text('alamat');
            $table->date('tanggallahir');
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('siswa');
    }
};