<?php
if (!defined('_INCODE')) {
    die('Access denied');
}
$data = [
    'pageTitle' => 'Đăng nhập hệ thống'
];
layout('header-login', $data);
// sendMail('laiminhkiet07052002@gmail.com', 'Test mail', 'Mail content');
?>
<div class="row">
    <div class="col-6" style="margin: 20px auto;">
        <h3 class="text-center text-uppercase">Đăng nhập hệ thống</h3>
        <form action="" method="post">
            <div class="form-group">
                <label for="">Email</label>
                <input type="text" class="form-control" name="" placeholder="Địa chỉ email...">
            </div>
            <div class="form-group">
                <label for="">Mật khẩu</label>
                <input type="password" class="form-control" name="" placeholder="Mật khẩu...">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
            <hr>
            <p class="text-center"><a href="?module=auth&action=forgot">Quên mật khẩu</a></p>
            <p class="text-center"><a href="?module=auth&action=register">Đăng ký tài khoản</a></p>
        </form>
    </div>
</div>
<?php
layout('footer-login');
?>