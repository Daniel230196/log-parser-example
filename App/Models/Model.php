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

    public function __get($name)
    {
        return property_exists($this,$name) ? $this->$name : null;
    }

}