<?php

require_once 'config.php';
require_once 'data.php';
require_once 'helpers.php';
require_once 'functions.php';
require_once 'models.php';

session_start();

$current_user = $_SESSION['current_user'] ?? NULL;

define('CACHE_DIR', basename(__DIR__ . DIRECTORY_SEPARATOR . 'cache'));
define('UPLOAD_PATH', basename(__DIR__ . DIRECTORY_SEPARATOR . 'uploads'));

$db_cfg = array_values($db_cfg);
$con = mysqli_connect(...$db_cfg);

if (!$con) {
    show_error(mysqli_connect_error);
}

mysqli_set_charset($con, 'utf8mb4');
