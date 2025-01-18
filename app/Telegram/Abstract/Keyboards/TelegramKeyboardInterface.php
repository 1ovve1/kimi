<?php

declare(strict_types=1);

namespace App\Telegram\Abstract\Keyboards;

use App\Telegram\Abstract\Keyboards\Buttons\TelegramButtonInterface;
use SergiX44\Nutgram\Nutgram;

/**
 * Keyboard interface that provides methods for:
 * - build keyboard layouts;
 * - customize descriptions;
 * - listen callback actions.
 */
interface TelegramKeyboardInterface
{
    /**
     * Append buttons in columns layout
     */
    public function addColumn(TelegramButtonInterface ...$button): self;

    /**
     * Append buttons in row layout
     */
    public function addRow(TelegramButtonInterface ...$button): self;

    /**
     * Store description for keyboard
     */
    public function setDescription(string $description): self;

    /**
     * Get description for keyboard
     */
    public function getDescription(): string;

    /**
     * Create keyboard instance
     */
    public function make(): mixed;

    /**
     * Listen keyboard buttons callbacks
     */
    public function listen(Nutgram $nutgram): void;
}
