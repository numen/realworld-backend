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
        Schema::create('profiles', function (Blueprint $table) {
            //$table->id()->primary();
            $table->uuid('user_id')->unique(); // Clave foránea
            $table->string('name');
            $table->string('bio')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();

            // Definir la relación de clave foránea
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
