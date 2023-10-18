<?php
if (!defined('_INCODE')) {
    die('Access denied');
}
$data = [
    'pageTitle' => 'Đăng ký tài khoản'
];
layout('header-login', $data);
if (isPost()) {

    //Validate form
    $body = getBody();

    $errors = []; //Mảng chứa các lỗi

    //Validate họ tên: Bắt buộc nhập, lớn hơn 5 ký tự
    if (empty(trim($body['fullname']))) {
        $errors['fullname']['required'] = 'Vui lòng nhập họ và tên';
    } else if (strlen(trim($body['fullname'])) < 5) {
        $errors['fullname']['min'] = 'Họ tên phải từ 5 ký tự trở lên';
    }

    //Validate số điện thoại: Bắt buộc nhập, Đúng định dạng
    if (empty(trim($body['phone']))) {
        $errors['phone']['required'] = 'Vui lòng nhập số điện thoại';
    } else {
        if (!isPhone(trim($body['phone']))) {
            $errors['phone']['isPhone'] = 'Số điện thoại vừa nhập không hợp lệ';
        }
    }

    //Validate Email: Bắt buộc nhập, đúng định dạng, email phải duy nhất
    if (empty(trim($body['email']))) {
        $errors['email']['required'] = 'Vui lòng nhập email';
    } else {
        //Kiểm tra email hợp lệ
        if (!isEmail($body['email'])) {
            $errors['email']['isEmail'] = 'Email vừa nhập không hợp lệ';
        } else {
            //Kiểm tra email có tồn tại trong database chưa
            $email = trim($body['email']);
            $sql = "SELECT id FROM users WHERE email='$email'";
            if (getRows($sql) > 0) {
                $errors['email']['unique'] = 'Email vừa nhập đã tồn tại';
            }
        }
    }

    //Validate Mật khẩu: Bắt buộc nhập, Độ dài lớn hơn hoặc bằng 8 ký tự
    if (empty(trim($body['password']))) {
        $errors['password']['required'] = 'Vui lòng nhập mật khẩu';
    } else {
        if (strlen(trim($body['password'])) < 8) {
            $errors['password']['min'] = 'Mật khẩu không được nhỏ hơn 8 ký tự';
        }
    }

    //Validate Nhập lại Mật khẩu: Bắt buộc nhập, phải giống trường mật khẩu đã nhập trước đó
    if (empty(trim($body['confirm_password']))) {
        $errors['confirm_password']['required'] = 'Vui lòng nhập lại xác nhận mật khẩu';
    } else {
        if (trim($body['password']) != trim($body['confirm_password'])) {
            $errors['confirm_password']['match'] = 'Hai mật khẩu không khớp nhau';
        }
    }

    //Kiểm tra mảng errors
    if (empty($errors)) {
        //Không có lỗi xảy ra
        $activeToken = sha1(uniqid() . time());
        $dataInsert = [
            'email' => $body['email'],
            'fullname' => $body['fullname'],
            'phone' => $body['phone'],
            'password' => password_hash($body['password'], PASSWORD_DEFAULT),
            'activeToken' => $activeToken,
            'createAt' => date('Y-m-d H:i:s')
        ];
        $insertStatus = insert('users', $dataInsert);
        if ($insertStatus) {

            //Tạo link gửi mail
            $linkActive = _WEB_HOST_ROOT . '?module=auth&action=active&token=' . $activeToken;

            //Thiết lập gửi mail
            $subject = $body['fullname'] . ' vui lòng kích hoạt tài khoản';
            $content = 'Chào bạn: ' . $body['fullname'] . '<br />';
            $content .= 'Vui lòng click vào link dưới đây để kích hoạt tài khoản: ' . '<br />';
            $content .= $linkActive . '<br />';
            $content .= 'Trân trọng!';

            //Tiến hành gửi mail
            $sendStatus = sendMail($body['email'], $subject, $content);
            if ($sendStatus) {
                setFlashData('msg', 'Đăng ký tài khoản thành công. Vui lòng kiểm tra email để kích hoạt tài khoản!');
                setFlashData('msg_type', 'success');
            } else {
                setFlashData('msg', 'Hệ thống đang gặp sự cố vui lòng thử lại sau!');
                setFlashData('msg_type', 'danger');
            }
        } else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố vui lòng thử lại sau!');
            setFlashData('msg_type', 'danger');
        }
        redirect('?module=auth&action=register');
    } else {
        //Có lỗi xảy ra
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('errors', $errors);
        setFlashData('old', $body);
        redirect('?module=auth&action=register'); //Load lại trang đăng ký
    }
}
$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$errors = getFlashData('errors');
$old = getFlashData('old');
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<div class="row">
    <div class="col-6" style="margin: 20px auto;">
        <h3 class="text-center text-uppercase">Đăng ký tài khoản</h3>
        <?php
        getMessage($msg, $msg_type);
        ?>
        <form action="" method="post">
            <div class="form-group">
                <label>Họ và tên</label>
                <input type="text" class="form-control" name="fullname" placeholder="Họ và tên..." value="<?php echo old('fullname', $old); ?>">
                <?php echo form_error('fullname', $errors, '<span class="error">', '</span>'); ?>
            </div>
            <div class="form-group">
                <label>Điện thoại</label>
                <input type="text" class="form-control" name="phone" placeholder="Số điện thoại..." value="<?php echo old('phone', $old); ?>">
                <?php echo form_error('phone', $errors, '<span class="error">', '</span>'); ?>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="text" class="form-control" name="email" placeholder="Địa chỉ email..." value="<?php echo old('email', $old); ?>">
                <?php echo form_error('email', $errors, '<span class="error">', '</span>'); ?>
            </div>
            <div class="form-group">
                <label>Mật khẩu</label>
                <div class="input-group" id="show_hide_password">
                    <input type="password" name="password" class="form-control" id="password" placeholder="Mật khẩu...">
                    <a href="javascript:;" class="input-group-text bg-transparent"><i style="width: 25px;" class='fa-solid fa-eye-slash'></i></a>
                </div>
                <?php echo form_error('password', $errors, '<span class="error">', '</span>'); ?>
            </div>
            <div class="form-group">
                <label>Nhập lại mật khẩu</label>
                <div class="input-group" id="show_hide_confirm_password">
                    <input type="password" name="confirm_password" class="form-control" id="confirm_password" placeholder="Nhập lại mật khẩu...">
                    <a href="javascript:;" class="input-group-text bg-transparent"><i style="width: 25px;" class='fa-solid fa-eye-slash'></i></a>
                </div>
                <?php echo form_error('confirm_password', $errors, '<span class="error">', '</span>'); ?>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Đăng ký</button>
            <hr>
            <p class="text-center"><a href="?module=auth&action=login">Đăng nhập hệ thống</a></p>
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
layout('footer-login');
?>