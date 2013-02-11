<?php
// wap phpmyadmin
// ionutvmi@gmail.com
// master-land.net

include "lib/settings.php";
connect_db($db);

$check = $db->query("SHOW DATABASES LIKE '".$db->real_escape_string($_GET['db'])."'");
$check = $check->num_rows;
$db_name=trim($_GET['db']);
// if no db exit
if($db_name == '' OR $check == 0) { 
	header("Location: main.php"); exit;}
// select db
$db->select_db($db_name);
// check tb
$check = $db->query("SHOW TABLE STATUS LIKE '".$db->real_escape_string($_GET['tb'])."'");
$check = $check->num_rows;
$tb_name=trim($_GET['tb']);
// if no tb exit
if($tb_name == '' OR $check == 0) { 
	header("Location: tables.php?db=".urlencode($db_name)); exit;}

// define url query
$_url="db=".urlencode($db_name)."&tb=".urlencode($tb_name);


$act=$_GET['act'];


// search
if(isset($_GET['search'])) {
$search = $db->real_escape_string(trim($_GET['search']));
$search = "LIKE '%$search%'";
}

if($act=='empty') {
	
	if($_POST['ok']){
		$_q="TRUNCATE TABLE ".PMA_bkq($tb_name);
		if($result = $db->query($_q)) 
			{
				$_msg = htmlentities($tb_name);
			} else {
				$_err=1; $_msg=$db->error;
			}
	}
	
}elseif($act=='drop') {
	
	if($_POST['ok']){
		$_q="DROP TABLE ".PMA_bkq($tb_name);
		if($result = $db->query($_q)) 
			{
				$_msg = htmlentities($tb_name);
			} else {
				$_err=1; $_msg=$db->error;
			}
	}
	
}elseif($act=='settings') {
		$_q="SHOW FULL COLUMNS FROM ".PMA_bkq($tb_name);
		if($data = $db->query($_q)) {
			while($_d = $data->fetch_object()) {
				$_cols[]=$_d->Field;
			}
		}
		$_q="SHOW DATABASES";
		if($data = $db->query($_q)) {
			while($_d = $data->fetch_object()) {
				$_dbs[]=$_d->Database;
			}
		}
		if($_POST['newnm']){
			$_q = "RENAME TABLE  ".PMA_bkq($db_name).".".PMA_bkq($tb_name)." TO ".PMA_bkq($_POST['db_nm']).".".PMA_bkq($_POST['newnm']).";";
			if($db->query($_q) !== TRUE)
				$_err=$db->error;
				else{
				$_SESSION['_q']= $_q;
				header("Location: ?act=settings&db=".urlencode($_POST['db_nm'])."&tb=".urlencode($_POST['newnm'])."&ok=ionutvmi");exit;
				}
		}
		elseif($_POST['copy']){
		$_q="";
			if($_POST['type'] == 0 || $_POST['type'] == 1)
			$_q .= "CREATE TABLE ".PMA_bkq($_POST['db_nm']).".".PMA_bkq($_POST['copy'])." LIKE ".PMA_bkq($db_name).".".PMA_bkq($tb_name).";";
			if($_POST['type'] == 0 || $_POST['type'] == 2)
			$_q.="SET SQL_MODE =  'NO_AUTO_VALUE_ON_ZERO'; INSERT INTO ".PMA_bkq($_POST['db_nm']).".".PMA_bkq($_POST['copy'])." SELECT * FROM ".PMA_bkq($db_name).".".PMA_bkq($tb_name).";";
			if($db->multi_query($_q) !== TRUE)
				$_err=$db->error;
				
		}elseif($_POST['cl_nm']){
		if($_POST['type']== "desc") $desc= "DESC";
			$_q = "ALTER TABLE  ".PMA_bkq($tb_name)." ORDER BY ".PMA_bkq($_POST['cl_nm'])." $desc;";
			if($db->query($_q) !== TRUE)
				$_err=$db->error;		
		}
	

}elseif($act=='new') {
	$_q="SHOW FULL COLUMNS FROM ".PMA_bkq($tb_name);
	if($data = $db->query($_q)) {
		while($_d = $data->fetch_object()) {
		$_cols[]=$_d->Field;
		}
	}
	if($_POST['ok']){
		$length=(trim($_POST['length']) != "" ? "(".trim($_POST['length']).")" : ""); 
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
		if($_POST['index'] != '---'){ 
		if($_POST['index'] == "PRIMARY")
		$index = "PRIMARY KEY";
		else
		$index=", ADD ".$_POST['index']." (".PMA_bkq($_POST['name']).")";
		}
		if($_POST['pos'] == 1) 
			$pos="FIRST";
			elseif($_POST['pos'] == 2)
				$pos="AFTER ".PMA_bkq($_POST['pos2']);
		
		if(trim($_POST['comments']) !='') $comments="COMMENT  '".$db->real_escape_string($_POST['comments'])."'"; 
		$_q="ALTER TABLE ".PMA_bkq($tb_name)." ADD ".PMA_bkq($_POST['name'])." ".$_POST['type']."$length ".trim($_POST['attribute'])." $collation $null $default $auto $comments $index $pos";
		if($result = $db->query($_q)) 
			{
				$_msg = htmlentities($_POST['name']);
			} else {
				$_err=1; $_msg=$db->error;
			}
		}

}elseif($act=='delind') {
		$cols = $_GET['name'];
		if(!$cols) header("Location: ?$_url");
		if($_POST['ok']) {
			$_q = "DROP INDEX ".PMA_bkq($_GET['name'])." ON ".PMA_bkq($tb_name);
				if($db->query($_q) !== TRUE)
					$_err=$db->error;
		}

}elseif($act=='multi') {
	$cols = $_POST['i'];
	if(!$cols) header("Location: ?$_url");
	// drop
	if($_POST['todo'] =='drop') {
		foreach($cols as $col){
			$qq[]="DROP ".PMA_bkq($col);
			$_msg[0].="<i>".htmlentities($col)."</i><br/>";
			$_msg[1].="<input type='hidden' name='i[]' value='$col'><br/>";
		}
		if($_POST['ok']) {
		$_q = "ALTER TABLE ".PMA_bkq($tb_name)." ".implode(',',$qq);
		if($db->query($_q) !== TRUE)
			$_err=$db->error;
		}
	} else {
		foreach($cols as $col){
			$qq[]=PMA_bkq($col);
			$_msg[0].="<i>".htmlentities($col)."</i><br/>";
			$_msg[1].="<input type='hidden' name='i[]' value='$col'><br/>";
		}
		if($_POST['ok']) {
		$_q = "ALTER TABLE ".PMA_bkq($tb_name)." ADD ".$_POST['todo']." (".implode(',',$qq).");";
		if($db->query($_q) !== TRUE)
			$_err=$db->error;
		}
	}

}else {
	// get all cols
	$_q="SHOW FULL COLUMNS FROM ".PMA_bkq($tb_name)." $search;";
	if($data = $db->query($_q)) {
		while($_d = $data->fetch_object()) {
		if($_d->Default != '') $_d->Default="default '".htmlentities($_d->Default)."'";
		$_d->Null = ($_d->Null == "NO" ? "not null" : "null");
			$col_data[]=$_d;
		}
	}
	// get all indexes
	$_q2="SHOW INDEXES FROM ".PMA_bkq($tb_name);
	if($data = $db->query($_q2)) {
		while($_d = $data->fetch_object()) {
			$ind_data[]=$_d;
		}
	}
}

$pma->title=$lang->Table;
include $pma->tpl."header.tpl";
include $pma->tpl."table.tpl";
include $pma->tpl."footer.tpl";