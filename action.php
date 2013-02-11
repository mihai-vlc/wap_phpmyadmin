<?php
// wap phpmyadmin
// ionutvmi@gmail.com
// master-land.net

session_start();

if($_GET['act'] == 'logout'){
session_destroy();
header("Location: index.php");
exit;
}elseif($_GET['act'] == 'noimg') {
$_SESSION['noimg'] = '1';
header("Location: ".$_SERVER["HTTP_REFERER"]);
}elseif($_GET['act'] == 'img') {
$_SESSION['noimg'] = '';
header("Location: ".$_SERVER["HTTP_REFERER"]);
}