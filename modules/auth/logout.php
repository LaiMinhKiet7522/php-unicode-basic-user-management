<?php
if (!defined('_INCODE')) {
    die('Access denied');
}
if (isLogin()) {
    $token = getSession('loginToken');
    delete('login_token', "token='$token'");
    removeSession('loginToken');
    setFlashData('msg','Đăng xuất thành công!');
    setFlashData('msg_type','success');
    redirect('?module=auth&action=login');
}