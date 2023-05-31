<?php

use App\Http\Middleware\CheckSubscription;
use App\Http\Middleware\OwnerOnly;
use Illuminate\Support\Facades\Route;

use ReesMcIvor\Chat\Http\Controllers\Api\ConversationController;
use ReesMcIvor\Chat\Http\Controllers\Api\MessagesController;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware([
    'api',
    'auth:sanctum',
    \Stancl\Tenancy\Middleware\InitializeTenancyByDomainOrSubdomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {

    Route::prefix('api/chat')->group(function() {
        Route::get('conversations', [ConversationController::class, 'list'])->name('conversations.list');
        Route::get('conversations/view/{conversation}', [ConversationController::class, 'view'])->name('conversations.show');
        Route::post('conversations/create', [ConversationController::class, 'create'])->name('conversations.create');
        Route::post('conversations/{conversation}/messages/create', [MessagesController::class, 'create'])->name('messages.create');
    });
});
