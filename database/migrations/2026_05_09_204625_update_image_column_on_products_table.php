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
         Schema::table('products', function (Blueprint $table) {

            // hapus kolom image lama
            $table->dropColumn('image');

        });

        Schema::table('products', function (Blueprint $table) {

            // ganti menjadi json multiple images
            $table->json('image');

            // description sekarang wajib
            $table->text('description')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('products', function (Blueprint $table) {

            $table->dropColumn('image');

        });

        Schema::table('products', function (Blueprint $table) {

            $table->string('image')->nullable();

            $table->text('description')->nullable()->change();
        });
    }
};
