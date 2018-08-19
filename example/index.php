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
    $requestAnalyzer->addRequest($request);
}

var_dump($requestAnalyzer->getTop(10));