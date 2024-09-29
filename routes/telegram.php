<?php

/** @var SergiX44\Nutgram\Nutgram $bot */

use App\Telegram\Actions\OpenAI\ChatGPT\AskKimiAction;
use App\Telegram\Actions\TestAction;
use App\Telegram\Commands\ChatGPT\AskTelegramCommand;
use App\Telegram\Commands\StartTelegramCommand;
use App\Telegram\Middlewares\StoreTelegramRequestInDatabaseMiddleware;
use SergiX44\Nutgram\Nutgram;

/*
|--------------------------------------------------------------------------
| Nutgram Handlers
|--------------------------------------------------------------------------
|
| Here is where you can register telegram handlers for Nutgram. These
| handlers are loaded by the NutgramServiceProvider. Enjoy!
|
*/

$bot->registerCommand(StartTelegramCommand::class);

$bot->group(function (Nutgram $bot) {
    $bot->onText('(.*)(kimi|KIMI|Kimi|Кими|кими|КИМИ)!(.*)', AskKimiAction::class);
    $bot->registerCommand(AskTelegramCommand::class);
})->middleware(StoreTelegramRequestInDatabaseMiddleware::class);
