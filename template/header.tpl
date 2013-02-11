<?php
// wap phpmyadmin
// ionutvmi@gmail.com
// master-land.net
header("Content-type: text/html; charset=iso-8859-1");
?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd"> <html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo $pma->title; ?> - WapPHPMyAdmin <?php echo $pma->version; ?> by ionutvmi</title>
<link rel="stylesheet" type="text/css" href="<?php echo $pma->tpl;?>style/style.css">
<link rel="shortcut icon" href="./favicon.ico" type="image/x-icon" />
</head>
<body>	
<div class='header'> 
<div style='padding:5px;'>wap phpmyadmin <?php echo $pma->version; ?><br/>
<?php echo $pma->host ? htmlentities($pma->user."@".$pma->host) : '';?>
</div>

<div class='subh'>
<span class='<?php echo $issql ? "selected" : "";?>'><a href='sql.php?<?php echo $_SERVER['QUERY_STRING']; ?>'>&nbsp;<?php echo $lang->Sql;?> </a></span> &nbsp; <span class='<?php echo $isexport ? "selected" : "";?>'><a href='export.php?<?php echo $_SERVER['QUERY_STRING']; ?>'>&nbsp;<?php echo $lang->Export;?> </a></span> &nbsp; <span class='<?php echo $isimport ? "selected" : "";?>'><a href='import.php?<?php echo $_SERVER['QUERY_STRING']; ?>'>&nbsp;<?php echo $lang->Import;?> </a>&nbsp;</span>
</div>
</div>
<div class='content'>