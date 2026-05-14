<?php

$firebaseURL = "https://uts-ecommerce-default-rtdb.asia-southeast1.firebasedatabase.app/";

function firebaseGet($path)
{
    global $firebaseURL;

    $url = $firebaseURL . $path . '.json';

    $data = file_get_contents($url);

    return json_decode($data, true);
}

function firebasePost($path, $data)
{
    global $firebaseURL;

    $url = $firebaseURL . $path . '.json';

    $options = [
        'http' => [
            'method' => 'POST',
            'header' => "Content-type: application/json",
            'content' => json_encode($data)
        ]
    ];

    $context = stream_context_create($options);

    return file_get_contents($url, false, $context);
}

?>