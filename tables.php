<?php
// wap phpmyadmin
// ionutvmi@gmail.com
// master-land.net


include "lib/settings.php";
include "lib/pagination.class.php";
connect_db($db);


$check = $db->query("SHOW DATABASES LIKE '".$db->real_escape_string($_GET['db'])."'");
$check = $check->num_rows;

$db_name=trim($_GET['db']);
// if no db exit
if($db_name == '' OR $check == 0) { 
	header("Location: main.php"); exit;}

// select db
$db->select_db($db_name);
	

//perp
if(isset($_GET['perp'])) {
$_SESSION['perp'] = (int)$_POST['perp'];
}
// search
if(isset($_GET['search'])) {
$search = $db->real_escape_string(trim($_GET['search']));
$search = "LIKE '%$search%'";
}

// new tb
if($_GET['act'] == 'newtb') {
$tb_name = trim($_POST['tb']);
if($tb_name != '') {
	if($_POST['ok']) {
	$length=trim($_POST['length']) != "" ? "(".trim($_POST['length']).")" : "";
	$null = $_POST['null'] == 1 ? "NULL" : "NOT NULL";
	if($_POST['default'] == "USER_DEFINED") {
	$default= "DEFAULT '".$db->real_escape_string($_POST['default2'])."'";
	} elseif($_POST['default'] !='NONE') {
	$default="DEFAULT ".$_POST['default'];
	}
	if(trim($_POST['collation']) !='') {
	$coll=explode("_",$_POST['collation']);
	$collation = "CHARACTER SET ".$coll[0]." COLLATE ".$_POST['collation'];
	}
	if($_POST['auto'] == 1) $auto='AUTO_INCREMENT';
	if($_POST['index'] != '---') $index=", ".$_POST['index']." KEY (".PMA_bkq($_POST['name']).")";
	if(trim($_POST['comments']) !='') $comments="COMMENT  '".$db->real_escape_string($_POST['comments'])."'"; 
	$_q="CREATE TABLE ".PMA_bkq($tb_name)." (".PMA_bkq($_POST['name'])." ".$_POST['type']."$length ".trim($_POST['attribute'])." $collation $null $default $auto $comments $index);";
	if($result = $db->query($_q)) 
		{
			$_msg = htmlentities($tb_name);
		} else {
			$_err=1; $_msg=$db->error;
		}
	}
}else {$_err=1; $_msg=$lang->Empty;}
} 
// drop db
elseif($_GET['act'] == 'multi') {
	$tb_nm = $_POST['i'];
	if(!$tb_nm) header("Location: ?db=".urlencode($db_name));
	foreach($tb_nm as $tb_name) 
	{
		if($tb_name != '') 
		{
		if($_POST['do'] == 'export')
		// set export var, if we select export we don't need to ask 'are you sure'
		$_SESSION['selected'][] = $tb_name;
			
		if(!$_POST['ok']) {
			$_msg[0].="<i>".htmlentities($tb_name)."</i><br/> ";
			$_msg[1].="<input type='hidden' name='i[]' value='".urlencode($tb_name)."'>\n";
		} else 
		{

			if($_POST['do'] == 'empty') 
			{

				if($result = $db->query("TRUNCATE ".PMA_bkq($tb_name))) 
				{
					$_msg[]= htmlentities($tb_name)." ".$lang->Empty;
				} else {
					$_err=1; $_msg[]=$db->error;
				}
			}
			if($_POST['do'] == 'drop') 
			{

				if($result = $db->query("DROP TABLE ".PMA_bkq($tb_name))) 
				{
					$_msg[]= str_replace("%s",htmlentities($tb_name),$lang->Table_droped);
				} else {
					$_err=1; $_msg[]=$db->error;
				}
			}
			if($_POST['do'] == 'check') 
			{
				if($result = $db->query("CHECK TABLE ".PMA_bkq($tb_name))) 
				{
				$result = $result->fetch_object();
					$_msg[$tb_name]=array($result->Msg_type,$result->Msg_text);
				} 
			}
			if($_POST['do'] == 'optimize') 
			{
				if($result = $db->query("OPTIMIZE TABLE ".PMA_bkq($tb_name))) 
				{
				$result = $result->fetch_object();
					$_msg[$tb_name]=array($result->Msg_type,$result->Msg_text);
				}
			}
			if($_POST['do'] == 'repair') 
			{
				if($result = $db->query("REPAIR TABLE ".PMA_bkq($tb_name))) 
				{
				$result = $result->fetch_object();
					$_msg[$tb_name]=array($result->Msg_type,$result->Msg_text);
				} 
			}
			if($_POST['do'] == 'analyze') 
			{
				if($result = $db->query("ANALYZE TABLE ".PMA_bkq($tb_name))) 
				{
				$result = $result->fetch_object();
					$_msg[$tb_name]=array($result->Msg_type,$result->Msg_text);
				} 
			}
			
		}

		}
		
	}
	// for export function
	if($_SESSION['selected']){header("Location: export.php?".$_SERVER['QUERY_STRING']); exit;}
	
} else {

// get all tbs
$_q="SHOW TABLE STATUS $search;";
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
$pma->title=$lang->tables;
include $pma->tpl."header.tpl";
include $pma->tpl."tables.tpl";
include $pma->tpl."footer.tpl";