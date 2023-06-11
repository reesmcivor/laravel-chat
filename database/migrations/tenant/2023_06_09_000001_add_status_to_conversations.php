<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dateTimeTz('last_replied_at')->nullable();
            $table->dateTimeTz('closed_at')->after('updated_at')->nullable();
            $table->enum('status', ['open', 'closed'])->default('open');
        });
    }
};

