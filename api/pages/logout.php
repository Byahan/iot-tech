<?php
session_start();

session_destroy();

header('Location: /iot-tech/index.php');
exit;
?>