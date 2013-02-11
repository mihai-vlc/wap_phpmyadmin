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
$check = $db->query("SHOW TABLE STATUS LIKE '".$db->real_escape_string($_GET['tb'])."'");
$check = $check->num_rows;
$tb_name=trim($_GET['tb']);
// if no tb exit
if($tb_name == '' OR $check == 0) { 
	header("Location: main.php"); exit;}
	
// define url query
$_url="db=".urlencode($db_name)."&tb=".urlencode($tb_name);

$cl = $db->query("SHOW FULL COLUMNS FROM ".PMA_bkq($tb_name)); 


if(!$_POST['ok']){
	if($_GET['unq']){
		$cl = $db->query("SHOW FULL COLUMNS FROM ".PMA_bkq($tb_name)); 
		
		
		$_q="SELECT * FROM ".PMA_bkq($tb_name)." WHERE ".base64_decode($_GET['unq']);
		if($data = $db->query($_q)) {
		if($data->num_rows < 1){
			header("Location: ?$_url"); exit;}

			$r_data = $data->fetch_assoc();
		}

			while($c = $cl->fetch_assoc()){
				$arr=PMA_extractFieldSpec($c['Type']);
			   // strip the "BINARY" attribute, except if we find "BINARY(" because
			   // this would be a BINARY or VARBINARY field type
			   $arr['type']   = preg_replace('@BINARY([^\(])@i', '', $arr['type']);
			   $arr['type']   = preg_replace('@ZEROFILL@i', '', $arr['type']);
			   $arr['type']   = preg_replace('@UNSIGNED@i', '', $arr['type']);
				// some types, for example longtext, are reported as
				// "longtext character set latin7" when their charset and / or collation
				// differs from the ones of the corresponding database.
				$tmp = strpos($arr['type'], 'character set');
				if ($tmp) {
				$arr['type'] = substr($arr['type'], 0, $tmp - 1);
				}		
				
				$c['Default'] = $r_data[$c['Field']];			
				$col[$c['Field']] = array_merge($arr,array('Default' => $c['Default']));
			}
	}else {
		while($c = $cl->fetch_assoc()){
			$arr=PMA_extractFieldSpec($c['Type']);
		
		   // strip the "BINARY" attribute, except if we find "BINARY(" because
		   // this would be a BINARY or VARBINARY field type
		   $arr['type']   = preg_replace('@BINARY([^\(])@i', '', $arr['type']);
		   $arr['type']   = preg_replace('@ZEROFILL@i', '', $arr['type']);
		   $arr['type']   = preg_replace('@UNSIGNED@i', '', $arr['type']);
			// some types, for example longtext, are reported as
			// "longtext character set latin7" when their charset and / or collation
			// differs from the ones of the corresponding database.
			$tmp = strpos($arr['type'], 'character set');
			if ($tmp) {
			$arr['type'] = substr($arr['type'], 0, $tmp - 1);
			}
			
			
			if($arr['type'] =='date' AND $c['Default'] == '')
				$c['Default']=date("Y-m-d",time());	
			if($arr['type'] =='datetime' AND $c['Default'] == '')
				$c['Default']=date("Y-m-d H:i:s",time());
			
			$col[$c['Field']] = array_merge($arr,array('Default' => $c['Default']));
		}
	}
}else{
	$dat=$_POST['i'];
	$i=0;
	while($c = $cl->fetch_object()){
		if(isSqlFunction($dat[$i]))
		$tq[]=PMA_bkq($c->Field)." = ".$dat[$i];
		else
		$tq[]=PMA_bkq($c->Field)." = '".$db->real_escape_string($dat[$i])."'";
		++$i;

	}
	$_q="INSERT INTO ".PMA_bkq($tb_name)." SET ".implode(',',$tq);
		if($db->query($_q) !== TRUE) 
			$_err=$db->error;
}




$pma->title=$lang->Insert;
include $pma->tpl."header.tpl";
include $pma->tpl."tbl_insert.tpl";
include $pma->tpl."footer.tpl";