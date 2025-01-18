<?php

declare(strict_types=1);

namespace App\Telegram\Abstract\Keyboards;

use App\Services\Telegram\TelegramServiceInterface;
use App\Telegram\Abstract\Keyboards\Buttons\TelegramButtonInterface;
use Illuminate\Support\Collection;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

class TelegramKeyboard implements TelegramKeyboardInterface
{
    /** @var Collection<TelegramButtonInterface> */
    protected Collection $buttonsBuffer;

    protected Collection $actionsHistory;

    protected string $description = '';

    public function __construct()
    {
        $this->buttonsBuffer = new Collection;
        $this->actionsHistory = new Collection;
    }

    public function addColumn(TelegramButtonInterface ...$button): TelegramKeyboardInterface
    {
        $this->appendInBuffer(...$button);

        foreach ($button as $item) {
            $this->addRow($item);
        }

        return $this;
    }

    public function addRow(TelegramButtonInterface ...$button): TelegramKeyboardInterface
    {
        $this->appendInBuffer(...$button);

        $this->actionsHistory->push(
            fn (InlineKeyboardMarkup $markup) => $markup->addRow(...collect($button)->map(fn (TelegramButtonInterface $x) => $x->make())->toArray())
        );

        return $this;
    }

    public function setDescription(string $description): TelegramKeyboardInterface
    {
        $this->description = $description;

        return $this;
    }

    public function make(): InlineKeyboardMarkup
    {
        $markup = InlineKeyboardMarkup::make();

        return $this->actionsHistory->reduce(fn (InlineKeyboardMarkup $markup, callable $action) => $action($markup), $markup);
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function listen(Nutgram $nutgram): void
    {
        /** @var TelegramButtonInterface $button */
        foreach ($this->buttonsBuffer as $button) {
            $nutgram->onCallbackQueryData($button::name(), $button);
        }
    }

    protected function appendInBuffer(TelegramButtonInterface ...$button): void
    {
        $this->buttonsBuffer = $this->buttonsBuffer->merge($button);
    }
}
