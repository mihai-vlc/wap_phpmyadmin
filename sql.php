<?php
// wap phpmyadmin
// ionutvmi@gmail.com
// master-land.net

include "lib/settings.php";
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

if($_POST) {
// sending query
$result = $db->multi_query($_POST['sql']);
if (!$result) {
    $_err[] = $db->error;
} else {
	$how_many=0;
	$how_many_html=0;
	do {
	++$how_many;
	$result = $db->store_result();
	$_html.= "<div class='success'> ".pma_img('s_success.png')." $lang->sql_ok </div>";
	
		$fields_num = $result->field_count;
	if($fields_num > 0) 
	{
	
		$_html.= "<table class='tb_border' cellspacing='0'><tr>";
		// printing table headers
		for($i=0; $i<$fields_num; $i++)
		{
			$field = $result->fetch_field();
			$_html.= "<th>{$field->name}</th>";
		}
		$_html.= "</tr>\n";
		// printing table rows
		while($row = $result->fetch_row())
		{
			$_html.= "<tr>";

			// $row is array... foreach( .. ) puts every element
			// of $row to $cell variable
			foreach($row as $cell)
				$_html.= "<td>".htmlentities($cell)."</td>";

			$_html.= "</tr>\n";
		}
		$_html.="</table>";
	}
	 } while ($db->next_result());
	 if ($db->error) { 
  $_err[] = $db->error;
} 
 
 }// end else
	// check if query is SELECT...FROM..
	if(preg_match('/^SELECT(.*)FROM(.*)/i',strtoupper($_POST['sql'])) && $how_many == 1){
		$how_many_html = array_pop($db->query("SELECT FOUND_ROWS()")->fetch_row());
		$new_tb = $db->query("EXPLAIN ".$_POST['sql'])->fetch_array(); $new_tb=$new_tb[2];
	
	}
}
$issql=true;
$pma->title=$lang->Sql;
include $pma->tpl."header.tpl";
include $pma->tpl."sql.tpl";
include $pma->tpl."footer.tpl";
