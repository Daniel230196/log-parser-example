<?php

declare(strict_types=1);

namespace App\Handlers;


use App\Exceptions\LogHandlerException;
use App\Models\Model;

/**
 * Interface LogHandlerInterface
 * @package App\Handlers
 */
interface LogHandlerInterface
{
    /**
     * Обработка модели, возвращает готовый р-тат
     * @param Model $model
     * @return array
     * @throws LogHandlerException
     */
    public function handle(Model $model): array;
}