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
        //
          Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->change();
            $table->string('phone')->nullable()->change();
            $table->string('alamat_lengkap')->nullable()->change();
            $table->string('provinsi')->nullable()->change();
            $table->string('kota')->nullable()->change();
            $table->enum('role', ['admin', 'customer'])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
