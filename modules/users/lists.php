<?php
if (!defined('_INCODE')) {
    die('Access denied');
}
//Kiểm tra trạng thái đăng nhập
if (!isLogin()) {
    redirect('?module=auth&action=login');
}