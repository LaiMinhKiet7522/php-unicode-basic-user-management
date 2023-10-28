<?php
if (!defined('_INCODE')) {
    die('Access denied');
}
?>
<html>

<head>
    <title>Hệ thống quản trị</title>
    <meta charset="utf8" />
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE; ?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE; ?>css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE; ?>css/style.css?ver=<?php echo rand(); ?>">
</head>

<body>
    <div class="container" style="display: flex; align-items: center; flex-direction: column; justify-content: center; height: 100vh;">
        <h1 class="text-center">HỆ THỐNG QUẢN TRỊ</h1>
        <p class="text-center"><a href="?module=users" class="btn btn-success btn-lg">Vào hệ thống</a></p>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="<?php echo _WEB_HOST_TEMPLATE; ?>js/bootstrap.min.js"></script>
    <script src="<?php echo _WEB_HOST_TEMPLATE; ?>js/custom.js"></script>
</body>

</html>