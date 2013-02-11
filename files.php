<?php
/* 
// wap phpmyadmin
// ionutvmi@gmail.com
// master-land.net
 */
include "lib/settings.php";
include "lib/pagination.class.php";
connect_db($db);

$db_name=$_GET['db'];

/* //perp */
if(isset($_GET['perp'])) {
	$_SESSION['perp'] = (int)$_POST['perp'];
}
/* // search */
if(isset($_GET['search']))
	$search = "*".$_GET['search']."*";
else 
	$search = "*";
	
	
$act=$_GET['act'];
if($act=='delete'){
	$ff = $_POST['i'];
	if(!$ff) header("Location: ?");
		foreach($ff as $f){
			if($_POST['ok']){
				@unlink($f);
				$_msg.=basename($f).", ";
			}else{
				$_m.= "<input type='hidden' name='i[]' value='$f'>";
			}
		}
}else{
	$files = glob("data/$search");
	$_total = count($files) - 1; // files - index.php
	if($_total > 0) {
		// pagination
		$perP = (int)$_SESSION['perp'] == 0 ? "10" : (int)$_SESSION['perp'];
		$_pag = new pagination;
		$f_dt = $_pag->generate($files,$perP);
		foreach($f_dt as $f_dt){
			if($f_dt != 'data/index.php')
				$fl_dt[]= $f_dt;
		}	
	}
}
	
$pma->title=$lang->Files;


include $pma->tpl."header.tpl";
include $pma->tpl."files.tpl";
include $pma->tpl."footer.tpl";

?>