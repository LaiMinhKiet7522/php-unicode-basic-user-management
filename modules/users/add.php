<?php
if (!defined('_INCODE')) die('Access Deined...');
/*File này dùng để thêm người dùng*/
$data = [
    'pageTitle' => 'Thêm người dùng'
];

layout('header', $data);

//Xử lý thêm người dùng
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
        $dataInsert = [
            'email' => $body['email'],
            'fullname' => $body['fullname'],
            'phone' => $body['phone'],
            'password' => password_hash($body['password'], PASSWORD_DEFAULT),
            'status' => $body['status'],
            'createAt' => date('Y-m-d H:i:s')
        ];
        $insertStatus = insert('users', $dataInsert);
        if ($insertStatus) {
            setFlashData('msg', 'Thêm mới người dùng thành công!');
            setFlashData('msg_type', 'success');
            redirect('?module=users');
        } else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố vui lòng thử lại sau!');
            setFlashData('msg_type', 'danger');
            redirect('?module=users&action=add');
        }
    } else {
        //Có lỗi xảy ra
        setFlashData('errors', $errors);
        setFlashData('old', $body);
        redirect('?module=users&action=add');
    }
}
$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$errors = getFlashData('errors');
$old = getFlashData('old');
?>
<div class="container">
    <hr />
    <h3><?php echo $data['pageTitle']; ?></h3>
    <?php
    getMessage($msg, $msg_type);
    ?>
    <form action="" method="post">
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="">Họ tên</label>
                    <input type="text" class="form-control" name="fullname" placeholder="Họ tên..." value="<?php echo old('fullname', $old); ?>">
                    <?php echo form_error('fullname', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for="">Số điện thoại</label>
                    <input type="text" class="form-control" name="phone" placeholder="Điện thoại..." value="<?php echo old('phone', $old); ?>">
                    <?php echo form_error('phone', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for="">Email</label>
                    <input type="text" class="form-control" name="email" placeholder="Email..." value="<?php echo old('email', $old); ?>">
                    <?php echo form_error('email', $errors, '<span class="error">', '</span>'); ?>
                </div>

            </div>

            <div class="col">
                <div class="form-group">
                    <label for="">Mật khẩu</label>
                    <input type="password" class="form-control" name="password" placeholder="Mật khẩu...">
                    <?php echo form_error('password', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for="">Nhập lại mật khẩu</label>
                    <input type="password" class="form-control" name="confirm_password" placeholder="Nhập lại mật khẩu...">
                    <?php echo form_error('confirm_password', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for="">Trạng thái</label>
                    <select name="status" class="form-control">
                        <option value="0" <?php echo (old('status', $old) == 0) ? 'selected' : false; ?>>Chưa kích hoạt</option>
                        <option value="1" <?php echo (old('status', $old) == 1) ? 'selected' : false; ?>>Kích hoạt</option>
                    </select>
                </div>

            </div>

        </div>
        <hr>
        <button type="submit" class="btn btn-primary">Thêm người dùng</button>
        <a href="?module=users" class="btn btn-success">Quay lại</a>
    </form>
</div>
<?php
layout('footer');
