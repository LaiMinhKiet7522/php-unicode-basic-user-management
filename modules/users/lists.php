<?php
if (!defined('_INCODE')) {
    die('Access denied');
}
$data = [
    'pageTitle' => 'Quản lý người dùng'
];
layout('header', $data);

//Xử lý lọc dữ liệu
$filter = '';
if (isGet()) {
    $body = getBody();
    if (!empty($body['status'])) {
        $status = $body['status'];
        if ($status == 2) {
            $statusSql = 0;
        } else {
            $statusSql = $status;
        }
        if (!empty($filter) && strpos($filter, 'WHERE') >= 0) {
            $operator = 'AND';
        } else {
            $operator = 'WHERE';
        }
        $filter .= "WHERE status=$statusSql";
    }

    //Xử lý lọc theo từ khóa
    if (!empty($body['keyword'])) {
        $keyword = $body['keyword'];
        if (!empty($filter) && strpos($filter, 'WHERE') >= 0) {
            $operator = 'AND';
        } else {
            $operator = 'WHERE';
        }
        $filter .= " $operator fullname LIKE '%$keyword%'";
    }
}

//Xử lý phân trang

$allUserNum = getRows("SELECT id FROM users $filter");

//1. Xác định được số lượng bản ghi trên 1 trang
$perPage = 3; //Mỗi trang có 3 bản ghi

//2. Tính số trang
$maxPage = ceil($allUserNum / $perPage); //Có tổng cộng 2 trang để chứa 3 bản ghi trên 1 trang

//3. Xử lý số trang dựa vào phương thức GET
if (!empty(getBody()['page'])) {
    $page = getBody()['page'];
    if ($page < 1 || $page > $maxPage) {
        $page = 1;
    }
} else {
    $page = 1;
}

//4. Tính toán offset trong limit dựa vào biến $page
// $page = 1 => offset = 0 = ($page-1)*$perPage = (1-1)*3 = 0
// $page = 2 => offset = 3 = ($page-1)*$perPage = (2-1)*3 = 3
// $page = 3 => offset = 6 = ($page-1)*$perPage = (3-1)*3 = 6
$offset = ($page - 1) * $perPage;

//Truy vấn lấy tất cả bản ghi
$listAllUser = getRaw("SELECT * FROM users $filter ORDER BY createAt DESC LIMIT $offset, $perPage");

//Xử lý Query String tìm kiếm với phân trang
$queryString = null;
if (!empty($_SERVER['QUERY_STRING'])) {
    $check = getBody();
    if (!isset($check['status']) && !isset($check['keyword'])) {
        $queryString = $_SERVER['QUERY_STRING'];
        $queryString = str_replace('module=users', '', $queryString);
        $queryString = str_replace('&page=' . $page, '', $queryString);
        $queryString = trim($queryString, '&');
    } else {
        $queryString = $_SERVER['QUERY_STRING'];
        $queryString = str_replace('module=users', '', $queryString);
        $queryString = str_replace('&page=' . $page, '', $queryString);
        $queryString = trim($queryString, '&');
        $queryString = '&' . $queryString;
    }
}
$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
?>
<div class="container">
    <hr>
    <h3>Quản lý người dùng</h3>
    <div class="container" style="display: flex; justify-content: space-between; padding: 0; margin-top: 20px;">
        <div>
            <p style="text-align: end; display: inline-block;"><a href="?module=users&action=add" class="btn btn-success btn-sm"><i class="fa fa-plus"></i>&nbsp; Thêm người dùng</a></p>
        </div>
        <form action="" method="get" style="margin-bottom: 0;">
            <input type="hidden" name="module" value="users">
            <div class="row" style="width: 800px;">
                <div class="col-4">
                    <div class="form-group">
                        <select name="status" class="form-control">
                            <option value="0">Chọn trạng thái</option>
                            <option value="1" <?php echo (!empty($status) && $status == 1) ? 'selected' : ''; ?>>Kích hoạt</option>
                            <option value="2" <?php echo (!empty($status) && $status == 2) ? 'selected' : ''; ?>>Chưa kích hoạt</option>
                        </select>
                    </div>
                </div>
                <div class="col-6">
                    <input type="search" name="keyword" class="form-control" placeholder="Từ khóa tìm kiếm..." value="<?php echo (!empty($keyword) ? $keyword : ''); ?>">
                </div>
                <div class="col-2" style="margin: 0;">
                    <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                </div>
            </div>
        </form>
    </div>
    <?php
    getMessage($msg, $msg_type);
    ?>
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
                        <td><a href="<?php echo _WEB_HOST_ROOT . '?module=users&action=edit&id=' . $item['id']; ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a></td>
                        <td><a href="<?php echo _WEB_HOST_ROOT . '?module=users&action=delete&id=' . $item['id']; ?>" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a></td>
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
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <?php
            if ($page > 1) {
                $prevPage = $page - 1;
                echo '<li class="page-item"><a class="page-link" href="' . _WEB_HOST_ROOT . '?module=users' . $queryString . '&page=' . $prevPage . '">Trước</a></li>';
            }
            ?>
            <?php
            $begin = $page - 2;
            if ($begin < 1) {
                $begin = 1;
            }
            $end = $page + 2;
            if ($end > $maxPage) {
                $end = $maxPage;
            }
            for ($index = $begin; $index <= $end; $index++) :
            ?>
                <li class="page-item <?php echo ($index == $page) ? 'active' : 'false'; ?>"><a class="page-link" href="<?php echo _WEB_HOST_ROOT . '?module=users' . $queryString . '&page=' . $index; ?>"><?php echo $index; ?></a></li>
            <?php
            endfor;
            ?>
            <?php
            if ($page < $maxPage) {
                $nextPage = $page + 1;
                echo '<li class="page-item"><a class="page-link" href="' . _WEB_HOST_ROOT . '?module=users' . $queryString . '&page=' . $nextPage . '">Sau</a></li>';
            }
            ?>
        </ul>
    </nav>
    <hr>
</div>
<?php
layout('footer');
