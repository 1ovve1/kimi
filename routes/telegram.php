<?php

/** @var SergiX44\Nutgram\Nutgram $bot */

use App\Telegram\Actions\KimiReplyHandler;
use App\Telegram\Actions\OpenAI\ChatGPT\AskKimiAction;
use App\Telegram\Commands\OpenAI\Chat\AskKimiCommand;
use App\Telegram\Commands\OpenAI\Chat\DanKimiCommand;
use App\Telegram\Commands\StartTelegramCommand;
use App\Telegram\Keyboards\CharacterListKeyboardFactory;
use App\Telegram\Keyboards\StartKeyboardFactory;
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
$bot->registerCommand(DanKimiCommand::class);

$bot->group(function (Nutgram $bot) {
    $bot->onMessage(KimiReplyHandler::class);

    $bot->onText('(.*)(kimi|KIMI|Kimi|Кими|кими|КИМИ)!(.*)', AskKimiAction::class);
})->middleware(StoreTelegramRequestInDatabaseMiddleware::class);

(new StartKeyboardFactory())->get()->listen($bot);
(new CharacterListKeyboardFactory())->get()->listen($bot);

