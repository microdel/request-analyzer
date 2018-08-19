<?php

namespace Microdel\RequestAnalyzer;

use Microdel\RequestAnalyzer\Storage\RequestsStorage;
use Microdel\RequestAnalyzer\Storage\StorageRecord;

/**
 * The class for analyzing the logs and getting the information about slow requests.
 */
class RequestAnalyzer
{
    /**
     * The storage of
     *
     * @var RequestsStorage
     */
    private $storage;


    public function __construct()
    {
        $this->storage = new RequestsStorage();
    }

    /**
     * Add a new request to storage.
     *
     * @param Request $request
     *
     * @return void
     */
    public function addRequest(Request $request): void
    {
        $storageRecord = $this->storage->getRecordByUrl($request->getUrl());

        if ($storageRecord) {
            $totalTime = $storageRecord->getTotalTime() + $request->getTimeInMilliseconds();
            $numberOfRequests = $storageRecord->getNumberOfRequest() + 1;
        } else {
            $totalTime = $request->getTimeInMilliseconds();
            $numberOfRequests = 1;
        }

        $newRecord = new StorageRecord($request->getUrl(), $totalTime, $numberOfRequests);

        $this->storage->save($newRecord);
    }

    /**
     * Returns the list of records with maximum execution time.
     *
     * @param int $count
     *
     * @return StorageRecord[]
     */
    public function getTop(int $count): array
    {
        return $this->storage->max($count);
    }
}