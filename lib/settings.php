<?php
error_reporting(E_ALL ^ E_NOTICE);
session_start();
$pma = new stdClass();
$pma->tpl="template/";
$pma->version="211";
$pma->user=$_SESSION['user'];
$pma->host=$_SESSION['host'];
$pma->pass=$_SESSION['pass'];

	/// mysql fix
	if(!extension_loaded('mysqli')){
		require_once dirname(dirname(__FILE__))."/lib/mysqli.so.php";		
	}


header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

include dirname(dirname(__FILE__))."/lang/index.php";
$lang=(object)$lang;
include dirname(dirname(__FILE__))."/lib/vars.php";
include dirname(dirname(__FILE__))."/lib/functions.php";
remove_magic_quotes();