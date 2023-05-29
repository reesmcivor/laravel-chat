<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained();
            $table->foreignId('user_id')->constrained('users');
            $table->text('content');
            $table->foreignId('attachment_id')->nullable()->constrained('attachments');
            $table->softDeletes();
            $table->timestamps();
        });
    }
};

