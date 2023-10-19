<?php
if (!defined('_INCODE')) {
    die('Access denied');
}
layout('header-login');
echo '<div class="container text-center"><br />';
$token = getBody()['token'];
if (!empty($token)) {
    //Truy vấn kiểm tra token với database
    $tokenQuery = firstRaw("SELECT id, fullname, email FROM users WHERE activeToken = '$token'");
    if (!empty($tokenQuery)) {
        $userid = $tokenQuery['id'];
        $dateUpdate = [
            'status' => 1,
            'activeToken' => NULL
        ];
        $updateStatus = update('users', $dateUpdate, "id=$userid");
        if ($updateStatus) {
            setFlashData('msg', 'Kích hoạt tài khoản thành công! Bạn có thể đăng nhập ngay bây giờ.');
            setFlashData('msg_type', 'success');

            //Tạo link login
            $loginLink = _WEB_HOST_ROOT . '?module=auth&action=login';
            //Gửi mail cho việc kích hoạt tài khoản thành công
            $subject = 'Kích hoạt tài khoản thành công';
            $content = 'Chúc mừng ' . $tokenQuery['fullname'] . ' đã kích hoạt thành công.<br/>';
            $content .= 'Bạn có thể đăng nhập tại link sau: ' . $loginLink . '<br/>';
            $content .= 'Trân trọng!';
            sendMail($tokenQuery['email'], $subject, $content);
        } else {
            setFlashData('msg', 'Kích hoạt tài khoản thất bại! Vui lòng liên hệ quản trị viên.');
            setFlashData('msg_type', 'danger');
        }
        redirect('?module=auth&action=login');
    } else {
        getMessage('Liên kết không tồn tại hoặc đã hết hạn', 'danger');
    }
} else {
    getMessage('Liên kết không tồn tại hoặc đã hết hạn', 'danger');
}
echo '</div>';
layout('footer-login');
