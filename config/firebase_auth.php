<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Kreait\Firebase\Factory;

/* IMPORTANT: correct path to JSON */
$factory = (new Factory)
    ->withServiceAccount(__DIR__ . '/firebase_credentials.json')
    ->withDatabaseUri('https://uts-ecommerce-default-rtdb.asia-southeast1.firebasedatabase.app');

$auth = $factory->createAuth();
$database = $factory->createDatabase();