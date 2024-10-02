<?php

/** @var SergiX44\Nutgram\Nutgram $bot */

use App\Telegram\Actions\GlobalMessageHandlerAction;
use App\Telegram\Actions\OpenAI\ChatGPT\AskKimiAction;
use App\Telegram\Commands\OpenAI\Chat\AskKimiCommand;
use App\Telegram\Commands\OpenAI\Chat\InteractiveCommand;
use App\Telegram\Commands\OpenAI\Chat\ResetCommand;
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

$bot->registerCommand(AskKimiCommand::class);
$bot->registerCommand(ResetCommand::class);

$bot->group(function (Nutgram $bot) {
    $bot->onMessage(GlobalMessageHandlerAction::class);

    $bot->registerCommand(StartTelegramCommand::class);
    $bot->registerCommand(InteractiveCommand::class);

    $bot->onText('(.*)(kimi|KIMI|Kimi|Кими|кими|КИМИ)!(.*)', AskKimiAction::class);
})->middleware(StoreTelegramRequestInDatabaseMiddleware::class);
