<?php

declare(strict_types=1);

namespace App;


use App\Adapters\LogAdapter;
use App\Exceptions\LogHandlerException;
use App\Exceptions\LogParserAdapterException;
use App\Handlers\LogHandlerInterface;

/**
 * Class Parser
 * @package App
 */
class Parser
{
    protected LogAdapter $adapter;

    public function __construct(LogAdapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Передаёт обработчик адаптеру
     * @param LogHandlerInterface $handler
     * @return $this
     */
    public function setHandler(LogHandlerInterface $handler): self
    {
        $this->adapter->setHandler($handler);

        return $this;
    }

    /**
     * Основной метод. Обработка и вывод результата
     * @param string $outputType
     */
    public function parse(string $outputType = 'json'): void
    {
        try{
            $path         = $this->adapter->getPath();
            $log          = fopen($path,'r');
            $fileIterator = $this->lineByLine($log);

            foreach ($fileIterator as $lineModel){
                $this->adapter->handleModel($lineModel);
            }

            fclose($log);

            $this->adapter->resolve($outputType);
        }catch (LogParserAdapterException $exception){
            echo 'Error: ' . $exception->getCode() . PHP_EOL . $exception->getMessage() . PHP_EOL;
            exit;
        }catch (LogHandlerException $exception){
            echo 'Handler crushed with status ' . $exception->getCode() . PHP_EOL . 'Reason: ' . $exception->getMessage() . PHP_EOL;
            exit;
        }

    }

    /**
     * Разюиваает лог построчно и возвращает модель строки, в соответствии с установленным адаптером
     * @param $fileDescriptor
     * @return iterable
     */
    public function lineByLine($fileDescriptor): iterable
    {
        $pattern = $this->adapter->getLinePattern();

        while(!feof($fileDescriptor)){
            $line =  fgets($fileDescriptor);

            if(!$line){
                break;
            }

            preg_match($pattern,$line,$result);

            yield $this->adapter->createModel($result);
        }

    }


}