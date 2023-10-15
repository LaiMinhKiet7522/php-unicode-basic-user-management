<?php
if (!defined('_INCODE')) {
    die('Access denied');
}

//Thông tin kết nối
try {
    if (class_exists('PDO')) {
        $dsn = _DRIVER . ':host=' . _HOST . ';dbname=' . _DB;
        $options = [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];
        $conn = new PDO($dsn, _USER, _PASSWORD, $options);
    }
} catch (Exception $e) {
    require_once "modules/errors/database.php"; //Import error
    die();
}
