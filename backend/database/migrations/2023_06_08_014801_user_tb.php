<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_tb', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('role');
            $table->string('username');
            $table->string('password'); 
            $table->timestamps();
        });

        // Create admin account
        DB::table('user_tb')->insert([
            'role' => 'admin',
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('user_tb');
    }
};
