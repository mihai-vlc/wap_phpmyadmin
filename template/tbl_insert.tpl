<?php
// wap phpmyadmin
// ionutvmi@gmail.com
// master-land.net

echo "<div class='left'>".$_var->home."<a href='tables.php?db=".urlencode($db_name)."'>".pma_img('database.png').htmlentities($db_name)."</a> &#187; <a href='table.php?$_url'>".pma_img('b_tbl.png').htmlentities($tb_name)."</a> &#187; ".pma_img("b_insrow.png")."$lang->Insert </div><hr size='1px'>
<div class='topbar'>
	<span><a href='tbl_browse.php?$_url'>".pma_img("b_tbl.png")." $lang->Browse </a></span>
	<span><a href='table.php?$_url'>".pma_img("b_props.png")." $lang->Structure </a></span>
	<span><a href='tbl_search.php?$_url'>".pma_img("b_search.png")." $lang->Search </a></span>
	</div>
";


if($_POST){

		echo "<div class='query'>".highlight_sql($_q)."</div>";
		echo $_err ? "<div class='notice'> ".pma_img('s_error.png')." $lang->Error : $_err </div>" : "<div class='success'> ".pma_img('s_success.png')." $lang->sql_ok </div>";
}else{
	echo "<form action='?$_url' method='post'>
	<table class='tb_border' cellspacing='0' width='100%'>";
	foreach($col as $k => $v){
		echo "<tr><td><b>".$k."</b></td> <td>".$v['type']."</td><td>";
		if($v['type'] == 'text'){
			echo "<textarea name='i[]' rows='3'>".htmlentities($v['Default'])."</textarea>";
			}elseif($v['type'] == 'enum' || $v['type'] == 'set'){
				echo make_select(0,"i[]",$v['enum_set_values'],'test4',"<option></option>");
			}else{
				echo "<input type='text' name='i[]' value='".htmlentities($v['Default'],ENT_QUOTES)."'>";
			}
	
		echo "</td></tr>";
	}
	echo "</table>
	<input type='submit' name='ok' value='$lang->Insert'>
	</form>";
}

echo "<div class='footbar'>	
	<span class='selected'><a href='tbl_insert.php?$_url'>".pma_img("b_insrow.png")." $lang->Insert </a></span>
	<span><a href='table.php?".$_url."&act=empty'>".pma_img("bd_empty.png")." $lang->Empty </a></span>
	<span><a href='table.php?".$_url."&act=drop'>".pma_img("b_drop.png")." $lang->Drop </a></span>
	</div>";