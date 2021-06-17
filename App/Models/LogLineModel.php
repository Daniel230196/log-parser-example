<?php

declare(strict_types=1);

namespace App\Models;

/**
 * Модель строки access_log
 * Class LogLineModel
 * @package App\Models
 */
class LogLineModel extends Model
{
    private string $url;
    private int $status;
    private int $contentSize;
    private string $referrer;
    private string $userAgent;

    public function __construct(
        string $url,
        int $status,
        int $contentSize,
        string $referrer,
        string $userAgent
    ){
        $this->url         = $url;
        $this->status      = $status;
        $this->contentSize = $contentSize;
        $this->referrer    = $referrer;
        $this->userAgent   = $userAgent;
    }


    /**
     * Данные модели строки в виде ассоциатинвого массива
     * @return array
     */
    public function getData(): array
    {
        return [
            'url'         => $this->url,
            'status'      => $this->status,
            'contentSize' => $this->contentSize,
            'referrer'    => $this->referrer,
            'userAgent'   => $this->userAgent,
        ];
    }

}