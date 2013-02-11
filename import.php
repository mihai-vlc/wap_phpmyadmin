<?php
// wap phpmyadmin
// ionutvmi@gmail.com
// master-land.net
@ini_set('memory_limit', '64M');
@set_time_limit(0);
include "lib/settings.php";
include "lib/pagination.class.php";
connect_db($db);
if(isset($_GET['db']) && $_GET['db'] !='') 
{
	$check = $db->query("SHOW DATABASES LIKE '".$db->real_escape_string($_GET['db'])."'");
	$check = $check->num_rows;

	$db_name=trim($_GET['db']);
	// if no db exit
	if($db_name == '' OR $check == 0) { 
		header("Location: main.php"); exit;}

	// select db
	$db->select_db($db_name);
}

if($_POST) {
$file_name="data/import-".date("d-M")."-".time().".sql";
if($_POST['url']){
	$_sql=file_get_contents($_POST['url']);
	// we assume that the file is sql
	$fp = fopen($file_name,"w");
	fwrite_stream($fp,$_sql);
	fclose($fp);
}else {
	move_uploaded_file($_FILES['file']['tmp_name'],$file_name);
	$_sql=file_get_contents($file_name);
}
	// check if it's zip
	if(zipIsValid($file_name)) {
		$zip = new ZipArchive;
		$res = $zip->open($file_name);
		if ($res === TRUE) {
		$_sql =	$zip->getFromIndex(0);
			$zip->close();
		// we rewrite the file with the sql data
			$fp = fopen($file_name,"w");
			fwrite_stream($fp,$_sql);
			fclose($fp);
		} else {
			$_err[]=$lang->error_zip;
		}
	}

	// sending query
	$result = $db->multi_query($_sql);
	if (!$result) {
		$_err[] = $db->error;
	} else {
		$_sql_nr=0;
			while ($db->next_result())
				$_sql_nr++;
			 if ($db->error) { 
		  $_err[] = $db->error; 
		} 
	}// end else


if(!$_POST['sv']) @unlink($file_name);
}
$isimport=true;
$pma->title=$lang->Import;
include $pma->tpl."header.tpl";
include $pma->tpl."import.tpl";
include $pma->tpl."footer.tpl";
