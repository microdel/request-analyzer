<?php

use Microdel\RequestAnalyzer\Request;

/**
 * The class for generation requests.
 */
class RequestGenerator
{
    /**
     * The array of available URL addresses.
     *
     * @var array
     */
    private $urls;

    /**
     * The minimum execution time of a request in milliseconds which will be generated for a request.
     *
     * @var int
     */
    protected $minTime = 50;

    /**
     * The maximum execution time of a request in milliseconds which will be generated for a request.
     *
     * @var int
     */
    protected $maxTime = 10000;

    /**
     * RequestGenerator constructor.
     *
     * @param string[] $urls The array of available URL addresses
     */
    public function __construct(array $urls)
    {
        if (empty($urls)) {
            throw new LogicException('No URL addresses available');
        }

        $this->urls = $urls;
    }

    /**
     * Returns the request with random address and time.
     *
     * @return Request
     */
    public function generateRequest(): Request
    {
        return new Request(
            $this->getRandomUrl(),
            $this->getRandomTime()
        );
    }

    /**
     * Returns the random available URL address.
     *
     * @return string
     */
    protected function getRandomUrl(): string
    {
        $randomKey = array_rand($this->urls, 1);

        return $this->urls[$randomKey];
    }

    /**
     * Returns the random query params.
     *
     * @return string
     */
    protected function getRandomParam(): string
    {
        $alphabet = range('a', 'z');
        $length = rand(2, 10);

        $paramName = '';

        for ($i = 0; $i < $length; $i++) {
            $paramName .= $alphabet[mt_rand(0, count($alphabet) - 1)];
        }

        return http_build_query([$paramName => rand(1, 1000)]);
    }

    /**
     * Returns the random time for a request.
     *
     * @return int
     */
    protected function getRandomTime(): int
    {
        return mt_rand($this->minTime, $this->maxTime);
    }

    /**
     * Returns the specified number of generated requests.
     *
     * @param int $number The number of requests to return
     *
     * @return Iterator
     */
    public function getRequests(int $number): Iterator
    {
        for ($i = 0; $i < $number; $i++) {
            yield $this->generateRequest();
        }
    }
}