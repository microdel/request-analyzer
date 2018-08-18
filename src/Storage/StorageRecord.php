<?php

namespace Microdel\RequestAnalyzer\Storage;

/**
 * The record of storage for saving information about requests.
 */
class StorageRecord
{
    /**
     * The request URL.
     *
     * @var string
     */
    private $url;

    /**
     * The total execution time of all requests.
     *
     * @var int
     */
    private $totalTime;

    /**
     * The number of saved requests.
     *
     * @var int
     */
    private $numberOfRequest;

    /**
     * StorageRecord constructor.
     *
     * @param string $url The request URL
     * @param int $totalTime The total execution time of all requests
     * @param int $numberOfRequest The number of saved requests
     */
    public function __construct(string $url, int $totalTime, int $numberOfRequest)
    {

        $this->url = $url;
        $this->totalTime = $totalTime;
        $this->numberOfRequest = $numberOfRequest;
    }

    /**
     * Returns the request URL.
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Returns the total execution time of all requests.
     *
     * @return int
     */
    public function getTotalTime(): int
    {
        return $this->totalTime;
    }

    /**
     * Returns the number if requests.
     *
     * @return int
     */
    public function getNumberOfRequest(): int
    {
        return $this->numberOfRequest;
    }
}