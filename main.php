<?php
// wap phpmyadmin
// ionutvmi@gmail.com
// master-land.net


include "lib/settings.php";
include "lib/pagination.class.php";
connect_db($db);

// if user selected a db redirect to that
if($_SESSION['db']){
	$db = $_SESSION['db'];
	unset($_SESSION['db']);
	header("Location: tables.php?db=".$db);
	exit;
}
//perp
if(isset($_GET['perp'])) {
$_SESSION['perp'] = (int)$_POST['perp'];
}
// search
if(isset($_GET['search'])) {
$search = $db->real_escape_string(trim($_GET['search']));
$search = "LIKE '%$search%'";
}

// new db
if($_GET['act'] == 'newdb') {
$db_name = trim($_POST['db']);
if($db_name != '') {
	if($result = $db->query("CREATE DATABASE ".PMA_bkq($db_name))) 
	{
		$_msg = htmlentities($db_name);
	} else {
		$_err=1; $_msg=$db->error;
	}

}else {$_err=1; $_msg=$lang->Empty;}
} 
// drop db
elseif($_GET['act'] == 'dropdb') {
	$db_nm = $_POST['i'];
	if(!$db_nm) header("Location: ?");
	foreach($db_nm as $db_name) 
	{
		if($db_name != '') 
		{
		if(!$_POST['ok']) {
			$_msg[0].="<i>".htmlentities($db_name)."</i><br/> ";
			$_msg[1].="<input type='hidden' name='i[]' value='".htmlentities($db_name)."'>\n";
		} else 
		{
			if($result = $db->query("DROP DATABASE IF EXISTS ".PMA_bkq($db_name))) 
			{
				$_msg .= htmlentities($db_name).", ";
			} else {
				$_err=1; $_msg=$db->error;
			}
		}

		}
		
	}
	
} else {

// get all dbs
$_q="SHOW DATABASES $search;";
if($data = $db->query($_q)) {
	while($_d = $data->fetch_array()) {
	$db_data[]=$_d; 
	}
$_total=count($db_data);
}

if($_total > 0) 
{
	// pagination
	$perP = (int)$_SESSION['perp'] == 0 ? "10" : (int)$_SESSION['perp'];

	$_pag = new pagination;
	$db_dt = $_pag->generate($db_data,$perP);
		foreach($db_dt as $db_d) {
			$db_dat[]=$db_d;
		}
	}
}
$pma->title=$lang->databases;
include $pma->tpl."header.tpl";
include $pma->tpl."main.tpl";
include $pma->tpl."footer.tpl";