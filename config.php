<?php
//File này chứa các hằng số cấu hình
const _MODULE_DEFAULT = 'home'; //Module mặc định
const _ACTION_DEFAULT = 'lists'; //Action mặc định

const _INCODE = true; //Ngăn chặn hành vi truy cập trực tiếp vào file

//Thiết lập host
define('_WEB_HOST_ROOT', 'http://' . $_SERVER["HTTP_HOST"] . '/' . 'php-unicode-basic-user-management/'); //Địa chỉ trang chủ
define('_WEB_HOST_TEMPLATE', _WEB_HOST_ROOT . 'templates/');

//Thiết lập path
define('_WEB_PATH_ROOT', __DIR__);
define('_WEB_PATH_TEMPLATE', _WEB_PATH_ROOT . '/templates');
