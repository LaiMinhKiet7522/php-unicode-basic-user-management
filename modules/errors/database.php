<?php
if (!defined('_INCODE')) {
    die('Access denied');
}
?>
<div class="" style="width: 600px; padding: 20px 30px; text-align: center; margin: 0 auto;">
    <h2 style="text-transform: uppercase;">Lỗi liên quan đến Cơ sở dữ liệu!</h2>
    <hr>
    <p><?php echo $e->getMessage(); ?></p>
    <p>File: <?php echo $e->getFile(); ?></p>
    <p>Line: <?php echo $e->getLine(); ?></p>
</div>