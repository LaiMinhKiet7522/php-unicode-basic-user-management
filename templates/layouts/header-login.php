<?php
if (!defined('_INCODE')) {
    die('Access denied');
}
?>
<html>

<head>
    <title><?php echo !empty($data['pageTitle']) ? $data['pageTitle'] : 'Unicode'; ?></title>
    <meta charset="utf8" />
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE; ?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE; ?>css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE; ?>css/style.css?ver=<?php echo rand(); ?>">
</head>

<body>