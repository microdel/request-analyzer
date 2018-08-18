<?php

use Microdel\RequestAnalyzer\RequestAnalyzer;

require_once (__DIR__ . '/../vendor/autoload.php');
require_once (__DIR__ . '/RequestGenerator.php');

/**
 * The list of available URL addresses.
 */
$availableURL = [
    '/post/111111/',
    '/post/111112/',
    '/post/111113/',
    '/post/111114/',
    '/post/111115/',
    '/post/111116/',
    '/post/111117/',
    '/post/111118/',
    '/post/111119/',
    '/post/111120/',
];

$requestGenerator = new RequestGenerator($availableURL);
$requestAnalyzer = new RequestAnalyzer();

$requests = $requestGenerator->getRequests(1000000);

foreach ($requests as $request) {
    $start = microtime(true);
    $requestAnalyzer->addRequest($request);
    $time = microtime(true) - $start;

//    var_dump($time);

    if ($time > 0.0001) {
        $requestAnalyzer->count();
        var_dump($time);
        var_dump('tttt');
    }
}

//var_dump($requestAnalyzer->getTop(10));