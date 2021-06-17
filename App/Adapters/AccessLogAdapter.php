<?php

declare(strict_types=1);

namespace App\Adapters;


use App\Exceptions\LogHandlerException;
use App\Exceptions\LogParserAdapterException;
use App\Handlers\LogHandlerInterface;
use App\Models\LogLineModel;
use App\Models\Model;

/**
 * Адаптер для парсера.
 * Определяет паттерн, по которому будет разбита каждая строка лога,
 * создаёт нужную модель, делигирует обработку хендлеру
 * Class AccessLogAdapter
 * @package App\Adapters
 */
class AccessLogAdapter extends LogAdapter
{
    protected static string $linePattern = "/(\S+) (\S+) (\S+) \[([^:]+):(\d+:\d+:\d+) ([^\]]+)\] \"(\S+) (.*?) (\S+)\" (\S+) (\S+) (\".*?\") (\".*?\")/";
    protected string $path;
    protected LogHandlerInterface $handler;
    protected ?array $handledResult = null;

    public function __construct(string $filePath)
    {
        $this->path = $filePath;
    }

    /**
     * @return string
     * @throws LogParserAdapterException
     */
    public function getPath(): string
    {
        if(!file_exists($this->path)){
            throw new LogParserAdapterException('log file not found', 404);
        }

        return $this->path;
    }

    /**
     * @param Model $model
     * @throws LogHandlerException
     */
    public function handleModel(Model $model): void
    {
        $this->handledResult = $this->handler->handle($model);
    }

    /**
     * Определяет аргументы модели, в соотв с шаблоном,
     * создаёт модель
     * @param array $modelArguments
     * @return LogLineModel
     */
    public function createModel(array $modelArguments): LogLineModel
    {
        $url         = (string)$modelArguments[8];
        $status      = (int)$modelArguments[10];
        $contentSize = (int)$modelArguments[11];
        $referrer    = (string)$modelArguments[12];
        $agent       = (string)$modelArguments[13];

        return new LogLineModel($url, $status, $contentSize, $referrer, $agent);
    }

    /**
     * Разрешить р-тат. Тип вывода, в соотв с аргументом
     * @param string $outputType
     * @return void
     * @throws LogParserAdapterException
     */
    public function resolve(string $outputType = 'json'): void
    {
        if(is_null($this->handledResult)){
            throw new LogParserAdapterException('No handled result found.' . __CLASS__ .  '::handleModel should be called', 400);
        }

        switch ($outputType){
            case 'array':
                break;
            case  'json':
                echo json_encode($this->handledResult, JSON_PRETTY_PRINT);
                break;
            default:
                throw new LogParserAdapterException('Adapter conflict - output type \'' . $outputType .  '\' not supported', 400);
        }
    }
}
