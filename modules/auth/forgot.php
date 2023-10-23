<?php
if (!defined('_INCODE')) {
    die('Access denied');
}
$data = [
    'pageTitle' => 'Đặt lại mật khẩu'
];
layout('header-login', $data);
//Kiểm tra trạng thái đăng nhập
if (isLogin()) {
    redirect('?module=users');
}
//Xử lý đăng nhập
if (isPost()) {
    $body = getBody();
    if (!empty($body['email'])) {
        $email = $body['email'];
        $queryUser = firstRaw("SELECT id FROM users WHERE email='$email'");
        if (!empty($queryUser)) {
            $userId = $queryUser['id'];

            //Tạo forgotToken 
            $forgotToken = sha1(uniqid() . time());
            $dataUpdate = [
                'forgotToken' => $forgotToken
            ];
            $updateStatus = update('users', $dataUpdate, "id=$userId");
            if ($updateStatus) {
                //Tạo link khôi phục
                $linkReset = _WEB_HOST_ROOT . '?module=auth&action=reset&token=' . $forgotToken;
                //Thiết lập gửi mail
                $subject = 'Yêu cầu khôi phục mật khẩu';
                $content = 'Chào bạn: ' . $email.'<br />';
                $content .= 'Chúng tôi nhận được yêu cầu khôi phục mật khẩu từ bạn, vui lòng click vào link sau để khôi phục <br />';
                $content .= $linkReset . '<br />';
                $content .= 'Trân trọng!';
                //Tiến hành gửi mail
                $sendStatus = sendMail($email, $subject, $content);
                if ($sendStatus) {
                    setFlashData('msg', 'Vui lòng kiểm tra email để xem hướng dẫn đặt lại mật khẩu.');
                    setFlashData('msg_type', 'success');
                } else {
                    setFlashData('msg', 'Có lỗi xảy ra trong quá trình gửi Mail! Vui lòng thử lại sau.');
                    setFlashData('msg_type', 'danger');
                }
            } else {
                setFlashData('msg', 'Lỗi hệ thống! Vui lòng thử lại sau.');
                setFlashData('msg_type', 'danger');
            }
        } else {
            setFlashData('msg', 'Địa chỉ email không tồn tại trong hệ thống! Vui lòng kiểm tra lại.');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Vui lòng nhập địa chỉ email');
        setFlashData('msg_type', 'danger');
    }
    redirect('?module=auth&action=forgot');
}
$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$old = getFlashData('old');
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<div class="row">
    <div class="col-6" style="margin: 20px auto;">
        <h3 class="text-center text-uppercase">Đặt lại mật khẩu</h3>
        <?php getMessage($msg, $msg_type); ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="">Email</label>
                <input type="text" class="form-control" name="email" placeholder="Địa chỉ email..." value="<?php echo old('email', $old); ?>">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Xác nhận</button>
            <hr>
            <p class="text-center"><a href="?module=auth&action=login">Đăng nhập</a></p>
            <p class="text-center"><a href="?module=auth&action=register">Đăng ký tài khoản</a></p>
        </form>
    </div>
</div>
<?php
layout('footer-login');
?>