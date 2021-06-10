<?php

declare(strict_types=1);

namespace App\Handlers;


use App\Exceptions\LogHandlerException;
use App\Models\LogLineModel;
use App\Models\Model;

/**
 * Class RoistatLogHandler
 * @package App\Handlers
 */
class RoistatLogHandler implements LogHandlerInterface
{
    /**
     * Модель, для которой предназначается обработчик
     * @var string
     */
    protected static string $targetModelClass = LogLineModel::class;

    /**
     * Шаблон результата
     * @var array
     */
    protected array $result = [
        'views'     => 0,
        'urls'      => 0,
        'traffic'   => 0,
        'crawlers'  => [
            'Google' => 0,
            'Bing'   => 0,
            'Baidu'  => 0,
            'Yandex' => 0
        ],
        'statusCodes' => []
    ];

    private array $uniqUrls = [];

    /**
     * @param Model $lineModel
     * @return array
     * @throws LogHandlerException
     */
    public function handle(Model $lineModel): array
    {
        if(!$lineModel instanceof static::$targetModelClass){
            throw new LogHandlerException(
                'Expected model instance: ' . static::$targetModelClass . '. Current: ' . get_class($lineModel) ,
                400
            );
        }

        $this->handleUrl((string)$lineModel->url)
            ->handleStatus((int)$lineModel->status)
            ->handleCrawlers($lineModel);
        $this->result['views']++;
        $this->result['urls'] = count($this->uniqUrls);
        $this->result['traffic'] += ($lineModel->status >= 200 && $lineModel->status < 300) ?
            $lineModel->contentSize :
            0;
        return $this->result;
    }

    /**
     * Учет уникальных урлов
     * @param string $url
     * @return $this
     */
    private function handleUrl(string $url): self
    {
        in_array($url, $this->uniqUrls) ?: $this->uniqUrls[] = $url;
        return $this;
    }

    /**
     * Учет количества HTTP-статусов ответа
     * @param int $status
     * @return $this
     */
    private function handleStatus(int $status): self
    {
        array_key_exists($status, $this->result['statusCodes']) ?
            $this->result['statusCodes'][$status]++ :
            $this->result['statusCodes'][$status] = 1;

        return $this;
    }

    /**
     * При наличии крулеров, учитывает их в результате
     * @param Model $model
     * @return $this
     */
    private function handleCrawlers(Model $model): self
    {
        $matches = preg_grep('/(bot|spider|Bot)/', explode(' ', $model->userAgent));
        if(count($matches) > 0){
            foreach ($matches as $crawlerName){
                if(preg_match('/(google)/i', $crawlerName)){
                    $this->result['crawlers']['Google']++;
                }elseif (preg_match('/(bing)/i', $crawlerName)){
                    $this->result['crawlers']['Bing']++;
                }elseif (preg_match('/(baidu)/i', $crawlerName)){
                    $this->result['crawlers']['Baidu']++;
                }elseif (preg_match('/(yandex)/i', $crawlerName)){
                    $this->result['crawlers']['Yandex']++;
                }
            }
        }
        return $this;
    }

}