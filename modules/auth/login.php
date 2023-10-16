<?php
if (!defined('_INCODE')) {
    die('Access denied');
}
$data = [
    'pageTitle' => 'Đăng nhập hệ thống'
];
layout('header-login', $data);
if(isPost()){
    $body = getBody();
    echo '<pre>';
    print_r($body);
    echo '</pre>';
}
$checkEmail = isEmail('minhkiet@gmail.com');
var_dump($checkEmail);
$checkInt = isNumber(100,['min_range'=>1, 'max_range'=>20]);
var_dump($checkInt);
$checkFloat = isFloat('a');
var_dump($checkFloat);
?>
<div class="row">
    <div class="col-6" style="margin: 20px auto;">
        <h3 class="text-center text-uppercase">Đăng nhập hệ thống</h3>
        <form action="" method="post">
            <div class="form-group">
                <label for="">Email</label>
                <input type="text" class="form-control" name="email" placeholder="Địa chỉ email...">
            </div>
            <div class="form-group">
                <label for="">Mật khẩu</label>
                <input type="password" class="form-control" name="password" placeholder="Mật khẩu...">
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