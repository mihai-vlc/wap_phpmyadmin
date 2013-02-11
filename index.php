<?php
// wap phpmyadmin
// ionutvmi@gmail.com
// master-land.net

include "lib/settings.php";
$pma->title=$lang->WELCOME;

if($_GET['host']) 
{
	if(trim($_GET['host']) !='' && trim($_GET['user']) !='') 
	{
		$db = @new mysqli(trim($_GET['host']), trim($_GET['user']), trim($_GET['pass']));

		/* check connection */
		if ($db->connect_errno) {
			$_err=1; $_msg=$db->connect_error;
		} else {
		$link="main.php";
		$_SESSION['host']=trim($_GET['host']);
		$_SESSION['user']=trim($_GET['user']);
		$_SESSION['pass']=trim($_GET['pass']);
		$_SESSION['db']=trim($_GET['db']);
		}
	
		
	} else {$_err=1; $_msg=$lang->Empty;}
}


include $pma->tpl."header.tpl";
include $pma->tpl."index.tpl";
include $pma->tpl."footer.tpl";