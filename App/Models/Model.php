<?php

declare(strict_types=1);

namespace App\Models;

/**
 * Базовый класс для моделей строки логов
 * Class Model
 * @package App\Models
 */
abstract class Model
{
    /**
     * Вернуть массив данных модели
     * @return array
     */
    abstract public function getData(): array;
}