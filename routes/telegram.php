<?php

/** @var SergiX44\Nutgram\Nutgram $bot */

use App\Telegram\Actions\KimiReplyHandler;
use App\Telegram\Actions\OpenAI\ChatGPT\AskKimiAction;
use App\Telegram\Commands\OpenAI\Chat\AskKimiCommand;
use App\Telegram\Commands\OpenAI\Chat\InteractiveCommand;
use App\Telegram\Commands\OpenAI\Chat\ResetCommand;
use App\Telegram\Commands\StartTelegramCommand;
use App\Telegram\Keyboards\Buttons\InteractiveButton;
use App\Telegram\Keyboards\Buttons\ResetButton;
use App\Telegram\Middlewares\AutoDeleteMessagesMiddleware;
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
$bot->registerCommand(AskKimiCommand::class);

$bot->group(function (Nutgram $bot) {
    $bot->registerCommand(ResetCommand::class);
    $bot->registerCommand(InteractiveCommand::class);
})->middleware(AutoDeleteMessagesMiddleware::class);

$bot->group(function (Nutgram $bot) {
    $bot->onMessage(KimiReplyHandler::class);

    $bot->onText('(.*)(kimi|KIMI|Kimi|Кими|кими|КИМИ)!(.*)', AskKimiAction::class);
})->middleware(StoreTelegramRequestInDatabaseMiddleware::class);

$bot->onCallbackQueryData(InteractiveButton::name(), InteractiveButton::class);
$bot->onCallbackQueryData(ResetButton::name(), ResetButton::class);
