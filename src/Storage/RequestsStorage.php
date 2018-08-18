<?php

namespace Microdel\RequestAnalyzer\Storage;

use Countable;

/**
 * The storage of a request data.
 *
 * The storage sorted from minimum execution time to maximum execution time.
 */
class RequestsStorage implements Countable
{
    /**
     * The list of records sorted by total execution time.
     * The list sorted from the highest to the lowest.
     *
     * @var StorageRecord[]
     */
    private $sortedList = [];

    /**
     * The list of all records used for getting the information about exists records by URL.
     *
     * @var StorageRecord[]
     */
    private $recordsList = [];

    /**
     * The minimal execution time in the storage.
     *
     * @var float
     */
    private $minTime;

    /**
     * The maximum execution time in the storage.
     *
     * @var float
     */
    private $maxTime;

    /**
     * Returns the data of a request by URL.
     *
     * @param string $url The URL of a request
     *
     * @return StorageRecord|null
     */
    public function getRecordByUrl(string $url): ?StorageRecord
    {
        return $this->recordsList[$url] ?? null;
    }

    /**
     * Saves the new row to the storage.
     *
     * @param StorageRecord $newRecord The new storage record
     *
     * @return void
     */
    public function save(StorageRecord $newRecord): void
    {
        $record = $this->getRecordByUrl($newRecord->getUrl());

        if ($newRecord != $record) {
            if ($record) {
                $this->removeFromList($record);
                $this->removeFromSortedList($record);
            }

            $this->addToList($newRecord);
            $this->addToSortedList($newRecord);
            $this->refreshBoundaryTime();
        }
    }

    /**
     * Adds the records to list of records.
     *
     * @param StorageRecord $record The record to add
     *
     * @return void
     */
    protected function addToList(StorageRecord $record): void
    {
        $this->recordsList[$record->getUrl()] = $record;
    }

    /**
     * Adds the record to the sorted list and returns the index of row in the sorted list.
     *
     * @param StorageRecord $record The record to add
     *
     * @return int
     */
    protected function addToSortedList(StorageRecord $record)
    {
        $index = $this->getPositionInSortedList($record);

        array_splice($this->sortedList, $index, 0, [$record]);

        return $index;
    }

    /**
     * Returns the index in the sorted list for adding a new record.
     *
     * @param StorageRecord $record The record to add
     *
     * @return int
     */
    protected function getPositionInSortedList(StorageRecord $record): int
    {
        if (empty($this->sortedList) || $record->getTotalTime() <= $this->minTime) {
            return 0;
        } elseif ($record->getTotalTime() >= $this->maxTime) {
            return count($this->sortedList);
        } else {
            return $this->searchIndex($record, 0, count($this->sortedList) - 1);
        }
    }

    /**
     * Searches the index for a place to place an element in the sorted list.
     *
     * @param StorageRecord $record The record to add
     * @param int $startIndex The start index to search
     * @param int $endIndex The end index to search
     *
     * @return int
     */
    protected function searchIndex(StorageRecord $record, int $startIndex, int $endIndex): int
    {
        $length = $endIndex - $startIndex + 1;

        if ($length < 3) {
            $minTime = $this->sortedList[$startIndex]->getTotalTime();
            $maxTime = $this->sortedList[$endIndex]->getTotalTime();

            if ($record->getTotalTime() <= $minTime) {
                return $startIndex;
            } elseif ($record->getTotalTime() >= $maxTime) {
                return $endIndex + 1;
            } else {
                return $endIndex;
            }
        } else {
            $middleIndex = $startIndex + (int)ceil(($endIndex - $startIndex) / 2);
            $middleTime = $this->sortedList[$middleIndex]->getTotalTime();

            if ($record->getTotalTime() == $middleTime) {
                return $middleIndex + 1;
            } elseif ($record->getTotalTime() > $middleTime) {
                return $this->searchIndex($record, $middleIndex, $endIndex);
            } else {
                return $this->searchIndex($record, $startIndex, $middleIndex);
            }
        }
    }

    /**
     * Removes the record from the list of records.
     *
     * @param StorageRecord $record The record to deleting
     *
     * @return void
     */
    protected function removeFromList(StorageRecord $record): void
    {
        unset($this->recordsList[$record->getUrl()]);
    }

    /**
     * Removes the record from the sorted list.
     *
     * @param StorageRecord $record The record to deleting
     *
     * @return void
     */
    protected function removeFromSortedList(StorageRecord $record): void
    {
        $indexOrRecord = array_search($record, $this->sortedList);

        if ($indexOrRecord !== false) {
            array_splice($this->sortedList, $indexOrRecord, 1);
        }
    }

    /**
     * Updates the minimum and maximum time of the sorted list.
     */
    private function refreshBoundaryTime(): void
    {
        $numberOfRecords = count($this->sortedList);

        if ($numberOfRecords === 0) {
            $this->minTime = null;
            $this->maxTime = null;
        } elseif ($numberOfRecords === 1) {
            $record = $this->sortedList[0];

            $this->minTime = $record->getTotalTime();
            $this->maxTime = $record->getTotalTime();
        } else {
            $this->minTime = $this->sortedList[0]->getTotalTime();
            $this->maxTime = $this->sortedList[$numberOfRecords - 1]->getTotalTime();
        }
    }

    /**
     * Returns the number of item in the storage.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->sortedList);
    }

    /**
     * Returns the array of records with maximum execution time.
     *
     * @param int $number The number of records to return
     * @return array
     */
    public function max(int $number)
    {
        $records = array_slice($this->sortedList, $this->count() - $number, $number);

        return array_map(function (StorageRecord $record) {
            return clone $record;
        }, $records);
    }

    /**
     * Returns the array of records with minimum execution time.
     *
     * @param int $number The number of records to return
     * @return array
     */
    public function min(int $number)
    {
        $records = array_slice($this->sortedList, 0, $number);

        return array_map(function (StorageRecord $record) {
            return clone $record;
        }, $records);
    }
}
