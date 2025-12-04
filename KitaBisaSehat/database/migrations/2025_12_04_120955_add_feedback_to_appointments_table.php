<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->tinyInteger('rating')->nullable()->after('status'); // 1-5 Bintang
            $table->text('feedback')->nullable()->after('rating'); // Ulasan teks
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['rating', 'feedback']);
        });
    }
};