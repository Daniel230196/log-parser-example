<?php

declare(strict_types=1);

namespace App\Adapters;


use App\Exceptions\LogParserAdapterException;
use App\Handlers\LogHandlerInterface;
use App\Models\Model;

/**
 * Class LogAdapter
 * @package App\Adapters
 */
abstract class LogAdapter
{
    /**
     * @param Model $model
     */
    abstract public function handleModel(Model $model): void;

    /**
     * Путь к файлу лога
     * @return string
     * @throws LogParserAdapterException
     */
    abstract public function getPath(): string;

    /**
     * Создать модель строки
     * @param array $modelArguments
     * @return Model
     */
    abstract public function createModel(array $modelArguments): Model;

    /**
     * Регулярка строки
     * @return string
     */
    final public function getLinePattern(): string
    {
        return static::$linePattern;
    }

    /**
     * Добавить обработчик
     * @param LogHandlerInterface $handler
     */
    final public function setHandler(LogHandlerInterface $handler): void
    {
        $this->handler = $handler;
    }

    /**
     * Получить результат обработки запроса в определенном формате
     * @param string $outputType
     * @return mixed
     * @throws LogParserAdapterException
     */
    abstract public function resolve(string $outputType = 'json');
}