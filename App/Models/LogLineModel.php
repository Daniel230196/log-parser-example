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
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return property_exists($this,$name) ? $this->$name : null;
    }

}