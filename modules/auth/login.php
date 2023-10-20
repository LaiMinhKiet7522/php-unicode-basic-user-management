<?php
if (!defined('_INCODE')) {
    die('Access denied');
}
$data = [
    'pageTitle' => 'Đăng nhập hệ thống'
];
layout('header-login', $data);
//Kiểm tra trạng thái đăng nhập
if (isLogin()) {
    redirect('?module=users');
}
//Xử lý đăng nhập
if (isPost()) {
    $body = getBody();
    if (!empty(trim($body['email'])) && !empty(trim($body['password']))) {
        //Kiểm tra đăng nhập
        $email = trim($body['email']);
        $password = trim($body['password']);
        //Truy vấn lấy thông tin user theo email
        $userQuery = firstRaw("SELECT id,password FROM users WHERE email='$email'");
        if (!empty($userQuery)) {
            $userId = $userQuery['id'];
            $passwordHash = $userQuery['password'];
            if (password_verify($password, $passwordHash)) {
                //Tạo token login
                $tokenLogin = sha1(uniqid() . time());
                //Insert dữ liệu vào bảng login_token
                $dataToken = [
                    'userId' => $userId,
                    'token' => $tokenLogin,
                    'createAt' => date('Y-m-d H:i:s')
                ];
                $insertTokenStatus = insert('login_token', $dataToken);
                if ($insertTokenStatus) {
                    //Insert token thành công

                    //Lưu loginToken vào session
                    setSession('loginToken', $tokenLogin);

                    //Chuyển hướng qua trang quản lý users
                    redirect('?module=users');
                } else {
                    setFlashData('msg', 'Lỗi hệ thống bạn không thể đăng nhập vào lúc này!');
                    setFlashData('msg_type', 'danger');
                }
            } else {
                setFlashData('msg', 'Mật khẩu vừa nhập không đúng. Vui lòng kiểm tra lại!');
                setFlashData('msg_type', 'danger');
                setFlashData('old', $body);
            }
        } else {
            setFlashData('msg', 'Email không tồn tại. Vui lòng kiểm tra lại!');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Vui lòng nhập email và mật khẩu');
        setFlashData('msg_type', 'danger');
    }
    redirect('?module=auth&action=login');
}
$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$old = getFlashData('old');
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<div class="row">
    <div class="col-6" style="margin: 20px auto;">
        <h3 class="text-center text-uppercase">Đăng nhập hệ thống</h3>
        <?php getMessage($msg, $msg_type); ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="">Email</label>
                <input type="text" class="form-control" name="email" placeholder="Địa chỉ email..." value="<?php echo old('email', $old); ?>">
            </div>
            <div class="form-group">
                <label>Mật khẩu</label>
                <div class="input-group" id="show_hide_password">
                    <input type="password" name="password" class="form-control" id="password" placeholder="Mật khẩu...">
                    <a href="javascript:;" class="input-group-text bg-transparent"><i style="width: 25px;" class='fa-solid fa-eye-slash'></i></a>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
            <hr>
            <p class="text-center"><a href="?module=auth&action=forgot">Quên mật khẩu</a></p>
            <p class="text-center"><a href="?module=auth&action=register">Đăng ký tài khoản</a></p>
        </form>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#show_hide_password a').on('click', function(e) {
            e.preventDefault();
            if ($('#show_hide_password input').attr('type') == 'text') {
                $('#show_hide_password input').attr('type', 'password');
                $('#show_hide_password i').addClass("fa-eye-slash");
                $('#show_hide_password i').removeClass("fa-eye");
            } else if ($('#show_hide_password input').attr('type') == 'password') {
                $('#show_hide_password input').attr('type', 'text');
                $('#show_hide_password i').removeClass("fa-eye-slash");
                $('#show_hide_password i').addClass("fa-eye");
            }
        });
    });
</script>
<?php
layout('footer-login');
?>