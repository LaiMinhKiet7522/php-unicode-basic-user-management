<?php
if (!defined('_INCODE')) {
    die('Access denied');
}
$data = [
    'pageTitle' => 'Đăng ký tài khoản'
];
layout('header-login', $data);
?>
<div class="row">
    <div class="col-6" style="margin: 20px auto;">
        <h3 class="text-center text-uppercase">Đăng ký tài khoản</h3>
        <form action="" method="post">
            <div class="form-group">
                <label for="">Họ và tên</label>
                <input type="text" class="form-control" name="" placeholder="Họ và tên...">
            </div>
            <div class="form-group">
                <label for="">Điện thoại</label>
                <input type="text" class="form-control" name="" placeholder="Số điện thoại...">
            </div>
            <div class="form-group">
                <label for="">Email</label>
                <input type="text" class="form-control" name="" placeholder="Địa chỉ email...">
            </div>
            <div class="form-group">
                <label for="">Mật khẩu</label>
                <input type="password" class="form-control" name="" placeholder="Mật khẩu...">
            </div>
            <div class="form-group">
                <label for="">Nhập lại mật khẩu</label>
                <input type="password" class="form-control" name="" placeholder="Nhập lại mật khẩu...">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Đăng ký</button>
            <hr>
            <p class="text-center"><a href="?module=auth&action=login">Đăng nhập hệ thống</a></p>
        </form>
    </div>
</div>
<?php
layout('footer-login');
?>