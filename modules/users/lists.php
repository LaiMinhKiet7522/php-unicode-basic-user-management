<?php
if (!defined('_INCODE')) {
    die('Access denied');
}
$data = [
    'pageTitle' => 'Quản lý người dùng'
];
layout('header', $data);
//Truy vấn lấy tất cả bản ghi
$listAllUser = getRaw("SELECT * FROM users ORDER BY createAt");
?>
<div class="container">
    <hr>
    <h3>Quản lý người dùng</h3>
    <p style="text-align: end;"><a href="" class="btn btn-success btn-sm"><i class="fa fa-plus"></i>&nbsp; Thêm người dùng</a></p>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th width="5%">STT</th>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Điện thoại</th>
                <th>Trạng thái</th>
                <th width="5%">Sửa</th>
                <th width="5%">Xóa</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($listAllUser)) :
                $count = 0;
                foreach ($listAllUser as $item) :
                    $count++;
            ?>
                    <tr>
                        <td><?php echo $count; ?></td>
                        <td><?php echo $item['fullname']; ?></td>
                        <td><?php echo $item['email']; ?></td>
                        <td><?php echo $item['phone']; ?></td>
                        <td><?php echo $item['status'] == 1 ? '<span class="badge rounded-pill bg-success"
                                            style="font-size: 15px; color: #fff;">Đã kích hoạt</span>' : '<span class="badge rounded-pill bg-dark"
                                            style="font-size: 15px; color: #fff;">Chưa kích hoạt</span>'; ?></td>
                        <td><a href="#" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a></td>
                        <td><a href="#" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a></td>
                    </tr>
                <?php
                endforeach;
            else : ?>
                <tr>
                    <td colspan="7" class="text-center">
                        <div class="alert alert-danger text-center">
                            Không có dữ liệu
                        </div>
                    </td>
                </tr>
            <?php
            endif;
            ?>
        </tbody>
    </table>
    <hr>
</div>
<?php
layout('footer');
