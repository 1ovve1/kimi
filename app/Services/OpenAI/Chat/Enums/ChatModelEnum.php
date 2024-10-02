<?php

declare(strict_types=1);

namespace App\Services\OpenAI\Chat\Enums;

use App\Exceptions\Services\OpenAI\GPTModelNotFoundedException;
use Illuminate\Support\Collection;

enum ChatModelEnum: string
{
    case GPT_4o = 'gpt-4o';
    case GPT_4o_MINI = 'gpt-4o-mini';
    case GPT_4 = 'gpt-4';
    case GPT_3_5_TURBO = 'gpt-3.5-turbo';

    /**
     * Return default gpt model from config (look config/gpt.php)
     */
    public static function default(): self
    {
        $model = config('gpt.default');

        return self::tryFrom($model) ?? throw new GPTModelNotFoundedException($model);
    }

    /**
     * Return model definition (look config/gpt.php)
     *
     * @return Collection<array{
     *     limit: int
     * }>
     */
    public function details(): Collection
    {
        $model = $this->value;

        return collect(config('gpt.models')[$model] ?? throw new GPTModelNotFoundedException($this->value));
    }
}
