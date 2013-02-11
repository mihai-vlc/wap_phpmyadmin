<?php
// wap phpmyadmin
// ionutvmi@gmail.com
// master-land.net
@ini_set('memory_limit', '64M');
@set_time_limit(0);

include "lib/settings.php";
// force download
if($_POST['tosave']) {
$file = 'dump_textarea-'.date("d-M").'-'.time().'.sql'; // name of the file
$content=$_POST['tosave'];
echo $content;
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename='.basename($file));
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: ' . strlen($content));
exit;
}

include "lib/pagination.class.php";
connect_db($db);
if(isset($_GET['db'])) 
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
if(isset($_GET['tb'])){

	$check = $db->query("SHOW TABLE STATUS LIKE '".$db->real_escape_string($_GET['tb'])."'");
	$check = $check->num_rows;
	$tb_name=trim($_GET['tb']);
	// if no tb exit
	if($tb_name == '' OR $check == 0) { 
		header("Location: main.php"); exit;}
$_SESSION['selected'][]=$tb_name;
}

if($_POST['data']) {

	include_once('lib/phpMyDumper.php');
	$filename  = ""; // Filename of dump, default: "dump.php"
	$compress  = false; // Dump as a compressed file, default: false

if($db_name) {
$tbls=$_POST['data'];
$records=false;
$query=false;
	if($_POST['_q']){
		$query=base64_decode($_POST['_q']);
	}
	if($_SESSION['records']){
		$records=$_SESSION['records'];
		unset($_SESSION['records']);
	}

	$dump = new phpMyDumper($db_name,$filename,$compress,$tbls,$records,$query);
	if(!$_POST['dset'])
	$dump->dropTable = false; // Dump DROP TABLE statement, default: true
	if($_POST['export'] == 0 || $_POST['export'] == 1)
	$dump->createTable = true; // Dump CREATE TABLE statement, default: false
	if($_POST['export'] == 0 || $_POST['export'] == 2)
	$dump->tableData = true; // Dump table data, default: false
	
	$dump->doDump();
	$_final_data .= $dump->filestream;
}else {
$tbls=array();
	foreach($_POST['data'] as $dbname) {
		$dump = new phpMyDumper($dbname,$filename,$compress,$tbls);
		if(!$_POST['dset'])
		$dump->dropTable = false; // Dump DROP TABLE statement, default: true
		if($_POST['export'] == 0 || $_POST['export'] == 1)
		$dump->createTable = true; // Dump CREATE TABLE statement, default: false
		if($_POST['export'] == 0 || $_POST['export'] == 2)
		$dump->tableData = true; // Dump table data, default: false
		
		$dump->doDump(true,++$i);
		$_final_data .= $dump->filestream."\n\n";
	}
}

if($_POST['output'] == '0' || $_POST['output'] == '1') {
// write the sql file
	$file_name="data/dump-".date("d-M")."-".time().".sql";
	$fp = fopen($file_name,"w");
	fwrite_stream($fp,$_final_data);
	fclose($fp);
if($_POST['output'] == '1') {
// zip it
	create_zip(array($file_name),$file_name.".zip");
	@unlink($file_name); // delete the sql
	$file_name.=".zip";
}


} // end output

	
}else {
	if(isset($_GET['db'])){
		if($db_name){
			$lt=$db->query("SHOW TABLE STATUS;");
			while($lst=$lt->fetch_array()){
				$list[]=$lst[0];
			}
		}
	}else{
		$lt=$db->query("SHOW DATABASES;");
		while($lst=$lt->fetch_array()){
			$list[]=$lst[0];
		}
	}
	if(count($list) == 0)		$_err=1;}

$isexport=true;
$pma->title=$lang->Export;
include $pma->tpl."header.tpl";
include $pma->tpl."export.tpl";
include $pma->tpl."footer.tpl";

?>