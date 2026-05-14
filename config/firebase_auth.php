<?php

require_once '../vendor/autoload.php';

use Kreait\Firebase\Factory;

$factory = (new Factory)
    ->withServiceAccount(__DIR__ . '/firebase_credentials.json');

$auth = $factory->createAuth();

?>