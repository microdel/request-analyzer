<?php

namespace Microdel\RequestAnalyzer;

/**
 * The model of a request.
 * Contains the URL and time of request.
 */
class Request
{
    /**
     * The URL of the request.
     *
     * @var string
     */
    protected $url;

    /**
     * The execution time of the request in milliseconds.
     *
     * @var float
     */
    protected $time;

    /**
     * The model of a request.
     *
     * @param string $url The URL of the request
     * @param float $time The execution time of the request in milliseconds
     */
    public function __construct(string $url, float $time)
    {
        $this->url = $url;
        $this->time = $time;
    }

    /**
     * Returns the URL of the request.
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Returns the execution time of the request in milliseconds.
     *
     * @return int
     */
    public function getTimeInMilliseconds(): int
    {
        return $this->time;
    }
}