<?php
if (!defined('_INCODE')) {
    die('Access denied');
}
layout('header-login');
echo '<div class="container text-center"><br />';
$token = getBody()['token'];
if (!empty($token)) {
    //Truy vấn kiểm tra token với database
    $tokenQuery = firstRaw("SELECT id, email FROM users WHERE forgotToken = '$token'");
    if (!empty($tokenQuery)) {
        $userId = $tokenQuery['id'];
        $userEmail = $tokenQuery['email'];
        if (isPost()) {
            $body = getBody();

            $errors = []; //Mảng chứa các lỗi

            //Validate Mật khẩu: Bắt buộc nhập, Độ dài lớn hơn hoặc bằng 8 ký tự
            if (empty(trim($body['password']))) {
                $errors['password']['required'] = 'Vui lòng nhập mật khẩu mới';
            } else {
                if (strlen(trim($body['password'])) < 8) {
                    $errors['password']['min'] = 'Mật khẩu không được nhỏ hơn 8 ký tự';
                }
            }

            //Validate Nhập lại Mật khẩu: Bắt buộc nhập, phải giống trường mật khẩu đã nhập trước đó
            if (empty(trim($body['confirm_password']))) {
                $errors['confirm_password']['required'] = 'Vui lòng nhập lại xác nhận mật khẩu mới';
            } else {
                if (trim($body['password']) != trim($body['confirm_password'])) {
                    $errors['confirm_password']['match'] = 'Hai mật khẩu không khớp nhau';
                }
            }

            if (empty($errors)) {
                //Xử lý update mật khẩu
                $passwordHash = password_hash($body['password'], PASSWORD_DEFAULT);
                $dataUpdate = [
                    'password' => $passwordHash,
                    'forgotToken' => NULL,
                    'updateAt' => date('Y-m-d H:i:s')
                ];
                $updateStatus = update('users', $dataUpdate, "id=$userId");
                if ($updateStatus) {
                    setFlashData('msg', 'Thay đổi mật khẩu thành công!');
                    setFlashData('msg_type', 'success');

                    //Gửi mail thông báo đổi mật khẩu thành công
                    $subject = 'Bạn vừa đổi mật khẩu';
                    $content = 'Chúc mừng bạn đã đổi mật khẩu thành công! <br/>';
                    sendMail($userEmail, $subject, $content);
                    redirect('?module=auth&action=login');
                } else {
                    setFlashData('msg', 'Có lỗi xảy ra! Vui lòng thử lại sau.');
                    setFlashData('msg_type', 'danger');
                    redirect('?module=auth&action=reset&token=' . $token);
                }
            } else {
                //Có lỗi xảy ra
                setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
                setFlashData('msg_type', 'danger');
                setFlashData('errors', $errors);
                redirect('?module=auth&action=reset&token=' . $token); //Load lại trang reset password   
            }
        }
        $msg = getFlashData('msg');
        $msg_type = getFlashData('msg_type');
        $errors = getFlashData('errors');
?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
        <div class="row text-left">
            <div class="col-6" style="margin: 20px auto;">
                <h3 class="text-center text-uppercase">Đặt lại mật khẩu</h3>
                <?php
                getMessage($msg, $msg_type);
                ?>
                <form action="" method="post">
                    <div class="form-group">
                        <label>Mật khẩu mới</label>
                        <div class="input-group" id="show_hide_password">
                            <input type="password" name="password" class="form-control" id="password" placeholder="Mật khẩu mới...">
                            <a href="javascript:;" class="input-group-text bg-transparent"><i style="width: 25px;" class='fa-solid fa-eye-slash'></i></a>
                        </div>
                        <?php echo form_error('password', $errors, '<span class="error">', '</span>'); ?>
                    </div>
                    <div class="form-group">
                        <label>Nhập lại mật khẩu mới</label>
                        <div class="input-group" id="show_hide_confirm_password">
                            <input type="password" name="confirm_password" class="form-control" id="confirm_password" placeholder="Nhập lại mật khẩu mới...">
                            <a href="javascript:;" class="input-group-text bg-transparent"><i style="width: 25px;" class='fa-solid fa-eye-slash'></i></a>
                        </div>
                        <?php echo form_error('confirm_password', $errors, '<span class="error">', '</span>'); ?>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Xác nhận</button>
                    <hr>
                    <p class="text-center"><a href="?module=auth&action=login">Đăng nhập</a></p>
                    <p class="text-center"><a href="?module=auth&action=register">Đăng ký tài khoản</a></p>
                    <input type="hidden" name="token" value="<?php echo $token; ?>">
                </form>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                $("#show_hide_password a").on('click', function(event) {
                    event.preventDefault();
                    if ($('#show_hide_password input').attr("type") == "text") {
                        $('#show_hide_password input').attr('type', 'password');
                        $('#show_hide_password i').addClass("fa-eye-slash");
                        $('#show_hide_password i').removeClass("fa-eye");
                    } else if ($('#show_hide_password input').attr("type") == "password") {
                        $('#show_hide_password input').attr('type', 'text');
                        $('#show_hide_password i').removeClass("fa-eye-slash");
                        $('#show_hide_password i').addClass("fa-eye");
                    }
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                $("#show_hide_confirm_password a").on('click', function(event) {
                    event.preventDefault();
                    if ($('#show_hide_confirm_password input').attr("type") == "text") {
                        $('#show_hide_confirm_password input').attr('type', 'password');
                        $('#show_hide_confirm_password i').addClass("fa-eye-slash");
                        $('#show_hide_confirm_password i').removeClass("fa-eye");
                    } else if ($('#show_hide_confirm_password input').attr("type") == "password") {
                        $('#show_hide_confirm_password input').attr('type', 'text');
                        $('#show_hide_confirm_password i').removeClass("fa-eye-slash");
                        $('#show_hide_confirm_password i').addClass("fa-eye");
                    }
                });
            });
        </script>
<?php
    } else {
        getMessage('Liên kết không tồn tại hoặc đã hết hạn', 'danger');
    }
} else {
    getMessage('Liên kết không tồn tại hoặc đã hết hạn', 'danger');
}
echo '</div>';
layout('footer-login');
?>