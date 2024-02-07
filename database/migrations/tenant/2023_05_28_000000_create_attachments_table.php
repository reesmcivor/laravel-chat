<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use ReesMcIvor\Chat\Models\Message;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversation_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Message::class);
            $table->string('filename');
            $table->softDeletes();
            $table->timestamps();
        });
    }
};

