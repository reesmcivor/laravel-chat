<?php

use App\Http\Middleware\CheckSubscription;
use App\Http\Middleware\OwnerOnly;
use Illuminate\Support\Facades\Route;

use ReesMcIvor\Chat\Http\Controllers\Api as ApiControllers;
use ReesMcIvor\Chat\Http\Controllers as Controllers;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware(['auth:sanctum'])->group(function () {

    Route::prefix('api/chat')->name('api.')->group(function() {
        Route::get('conversations', [ApiControllers\ConversationController::class, 'list'])->name('conversations.list');
        Route::get('conversations/view/{conversationId}', [ApiControllers\ConversationController::class, 'view'])->name('conversations.show');
        Route::get('conversations/close/{conversation}', [ApiControllers\ConversationController::class, 'close'])->name('conversations.close');
        Route::post('conversations/create', [ApiControllers\ConversationController::class, 'create'])->name('conversations.create');
        Route::post('conversations/{conversationId}/messages/create', [ApiControllers\MessagesController::class, 'create'])->name('messages.create');
    });
});

$adminMiddleware = ['web', 'auth:sanctum'];
Route::middleware($adminMiddleware)->name('tenant.')->group(function () {
    Route::resource('conversations', Controllers\ConversationController::class);
    Route::get('conversations/{conversation}/join', [Controllers\ConversationController::class, 'join'])->name('conversations.join');
    Route::get('conversations/{conversation}/leave', [Controllers\ConversationController::class, 'leave'])->name('conversations.leave');
    Route::post('messages/store/{conversation}', [Controllers\MessagesController::class, 'store'])->name('messages.store');
});
