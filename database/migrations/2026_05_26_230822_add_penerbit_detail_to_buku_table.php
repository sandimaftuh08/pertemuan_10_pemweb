<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('buku', function (Blueprint $table) {
            $table->string('negara_penerbit', 50)->nullable()->after('penerbit');
            $table->string('kota_penerbit', 50)->nullable()->after('negara_penerbit');
        });
    }
 
    public function down(): void
    {
        Schema::table('buku', function (Blueprint $table) {
            $table->dropColumn(['negara_penerbit', 'kota_penerbit']);
        });
    }
};