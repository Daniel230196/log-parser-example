<?php

declare(strict_types=1);

namespace App\Adapters;


use App\Exceptions\LogParserAdapterException;
use App\Handlers\LogHandlerInterface;
use App\Models\Model;

/**
 * Interface LogAdapterInterface
 * @package App\Adapters
 */
interface LogAdapterInterface
{
    /**
     * @param $model
     */
    public function handleModel(Model $model): void;
    /**
     * Путь к файлу лога
     * @return string
     * @throws LogParserAdapterException
     */
    public function getPath(): string;

    /**
     * Создать модель строки
     * @param array $modelArguments
     * @return Model
     */
    public function createModel(array $modelArguments): Model;

    /**
     * Регулярка строки
     * @return string
     */
    public function getLinePattern(): string;

    /**
     * Добавить обработчика
     * @param LogHandlerInterface $handler
     */
    public function setHandler(LogHandlerInterface $handler): void;

    /**
     * Получить результат обработки запроса в определенном формате
     * @param string $outputType
     * @return mixed
     * @throws LogParserAdapterException
     */
    public function resolve(string $outputType = 'json');
}