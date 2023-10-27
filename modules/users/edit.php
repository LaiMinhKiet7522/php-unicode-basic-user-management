<?php
if (!defined('_INCODE')) die('Access Deined...');
/*File này dùng để thêm người dùng*/
$data = [
    'pageTitle' => 'Cập nhật người dùng'
];

layout('header', $data);

//Lấy dữ liệu cũ của người dùng
$body = getBody();

if (!empty($body['id'])) {
    $userId = $body['id'];
    //Kiểm tra userId có tồn tại trong Database hay không?
    //Nếu tồn tại => Lấy ra thông tin
    //Nếu không tồn tại => Chuyển hướng về trang lists
    $userDetails = firstRaw("SELECT * FROM users WHERE id = $userId");
    if (!empty($userDetails)) {
        setFlashData('userDetails', $userDetails);
    } else {
        redirect('?module=users');
    }
} else {
    redirect('?module=users');
}

//Xử lý sửa người dùng
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
            $sql = "SELECT id FROM users WHERE email='$email' AND id<>$userId";
            if (getRows($sql) > 0) {
                $errors['email']['unique'] = 'Email vừa nhập đã tồn tại';
            }
        }
    }

    //Validate Nhập lại Mật khẩu: Bắt buộc nhập, phải giống trường mật khẩu đã nhập trước đó
    if (!empty(trim($body['password']))) {
        //Chỉ validate confirm_password nếu password được nhập
        if (empty(trim($body['confirm_password']))) {
            $errors['confirm_password']['required'] = 'Vui lòng nhập lại xác nhận mật khẩu';
        } else {
            if (trim($body['password']) != trim($body['confirm_password'])) {
                $errors['confirm_password']['match'] = 'Hai mật khẩu không khớp nhau';
            }
        }
    }


    //Kiểm tra mảng errors
    if (empty($errors)) {
        //Không có lỗi xảy ra
        $dataUpdate = [
            'email' => $body['email'],
            'fullname' => $body['fullname'],
            'phone' => $body['phone'],
            'status' => $body['status'],
            'updateAt' => date('Y-m-d H:i:s')
        ];
        if (!empty(trim($body['password']))) {
            $dataUpdate['password'] = password_hash($body['password'], PASSWORD_DEFAULT);
        }
        $condition = "id=$userId";
        $updateStatus = update('users', $dataUpdate, $condition);
        if ($updateStatus) {
            setFlashData('msg', 'Cập nhật thông tin người dùng thành công!');
            setFlashData('msg_type', 'success');
            redirect('?module=users');
        } else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố vui lòng thử lại sau!');
            setFlashData('msg_type', 'danger');
            redirect('?module=users&action=edit&id='.$userId);
        }
    } else {
        //Có lỗi xảy ra
        setFlashData('errors', $errors);
        setFlashData('old', $body);
        redirect('?module=users&action=edit&id='.$userId);
    }
}
$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$errors = getFlashData('errors');
$old = getFlashData('old');
$userDetails = getFlashData('userDetails');
if (!empty($userDetails)) {
    $old = $userDetails;
}
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
                    <input type="password" class="form-control" name="password" placeholder="Mật khẩu (Không nhập nếu không thay đổi)...">
                    <?php echo form_error('password', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for="">Nhập lại mật khẩu</label>
                    <input type="password" class="form-control" name="confirm_password" placeholder="Nhập lại mật khẩu (Không nhập nếu không thay đổi)...">
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
        <button type="submit" class="btn btn-primary">Cập nhật người dùng</button>
        <a href="?module=users" class="btn btn-success">Quay lại</a>
        <input type="hidden" name="id" value="<?php echo $userId; ?>">
    </form>
</div>
<?php
layout('footer');
