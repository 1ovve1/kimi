<?php

declare(strict_types=1);

namespace App\Telegram\Keyboards\Buttons\Characters;

use App\Enums\Models\CharacterEnum;
use App\Exceptions\Repositories\Telegram\Chat\ChatNotFoundException;
use App\Exceptions\Repository\OpenAI\Character\CharacterNotFoundException;
use App\Repositories\OpenAI\Chat\Character\CharacterRepositoryInterface;
use App\Repositories\Telegram\Chat\ChatRepositoryInterface;
use App\Services\Telegram\TelegramData\TelegramDataServiceInterface;
use App\Services\Telegram\TelegramServiceInterface;
use App\Telegram\Abstract\Keyboards\Buttons\AbstractTelegramButton;
use App\Telegram\Keyboards\StartKeyboardFactory;

abstract class AbstractCharacterButton extends AbstractTelegramButton
{
    public function __construct(
        readonly private CharacterEnum $characterEnum,
    )
    {
    }

    /**
     * @throws ChatNotFoundException
     * @throws CharacterNotFoundException
     */
    public function handle(
        TelegramServiceInterface $telegramService,
        TelegramDataServiceInterface $telegramDataService,
        CharacterRepositoryInterface $characterRepository,
        ChatRepositoryInterface $chatRepository
    ): void
    {
        $chat = $telegramDataService->resolveChat();
        $character = $characterRepository->findByEnum($this->characterEnum);

        if ($characterRepository->findForChat($chat)->name !== $character->name) {
            $chatRepository->setCharacter($chat, $character);
            $telegramService->updateKeyboard((new StartKeyboardFactory())->get());
        }

    }

    /**
     * @throws ChatNotFoundException
     * @throws CharacterNotFoundException
     */
    public function text(
        TelegramDataServiceInterface $telegramDataService,
        CharacterRepositoryInterface $characterRepository
    ): string
    {
        $chat = $telegramDataService->resolveChat();
        $character = $characterRepository->findByEnum($this->characterEnum);

        return __('telegram.keyboards.buttons.character.name', ['name' => $this->characterEnum->value]) . (($characterRepository->findForChat($chat)->name === $character->name) ? ' ✅' : '');
    }
}
