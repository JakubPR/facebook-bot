<?php
use App\Http\Controllers\BotManController;

$botman = resolve('botman');

$botman->hears('GET_STARTED', BotManController::class.'@startConversation');
