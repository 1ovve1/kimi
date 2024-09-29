<?php

declare(strict_types=1);

namespace App\Services\OpenAI;

use App\Exceptions\Services\OpenAI\GPTModelNotFoundedException;
use Illuminate\Support\Collection;

enum AIModelsEnum: string
{
    case GPT_4o = 'gpt-4o';
    case GPT_4o_MINI = 'gpt-4o-MINI';
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

        return config('gpt.models')[$model] ?? throw new GPTModelNotFoundedException($this->value);
    }
}
