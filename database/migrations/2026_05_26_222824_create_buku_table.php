<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('buku', function (Blueprint $table) {
            $table->id();
            $table->string('kode_buku', 20)->unique();
            $table->string('judul', 200);
            $table->enum('kategori', [
                'Programming', 
                'Database', 
                'Web Design', 
                'Networking',
                'Data Science'
            ]);
            $table->string('pengarang', 100);
            $table->string('penerbit', 100);
            $table->year('tahun_terbit');
            $table->string('isbn', 20)->nullable();
            $table->decimal('harga', 10, 2);
            $table->integer('stok')->default(0);
            $table->text('deskripsi')->nullable();
            $table->string('bahasa', 20)->default('Indonesia');
            $table->timestamps();
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};