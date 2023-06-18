<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            DB::statement("ALTER TABLE conversations MODIFY COLUMN status ENUM('pending', 'open', 'hold', 'closed') DEFAULT 'pending'");
        });
    }
};

