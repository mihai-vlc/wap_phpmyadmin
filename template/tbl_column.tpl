<?php
// wap phpmyadmin
// ionutvmi@gmail.com
// master-land.net
echo "<div class='left'>".$_var->home."<a href='tables.php?db=".urlencode($db_name)."'>".pma_img('database.png').htmlentities($db_name)."</a> &#187; <a href='table.php?$_url'>".pma_img('b_tbl.png').htmlentities($tb_name)."</a> ";


if($act=='drop'){
echo "&#187; <a href='?$_url'>".pma_img('b_column.png').htmlentities($col_name)."</a> &#187; ".pma_img('b_drop.png')." $lang->Drop </div><hr size='1px'>";

	if($_POST['ok']){
		echo "<div class='query'>".highlight_sql($_q)."</div>";
		echo $_err ? "<div class='notice'> ".pma_img('s_error.png')." $lang->Error : $_msg </div>" : "<div class='success'> ".pma_img('s_success.png')." ".str_replace("%s",$_msg,$lang->Column_cl_droped)."</div>"; 
	}else {	
		echo "<form action='?act=drop&$_url' method='post'>
		$lang->DROP_DELETE_COLUMN <br/>
		<input type='submit' value='$lang->Yes' name='ok'> <a href='?$_url'> $lang->No </a>
		</form>";
	}
}elseif($act=='edit'){
echo "&#187; <a href='?$_url'>".pma_img('b_column.png').htmlentities($col_name)."</a> &#187; ".pma_img('b_edit.png')." $lang->Edit </div><hr size='1px'>";


if($_POST['ok']){
	echo "<div class='query'>".highlight_sql($_q)."</div>";
	echo $_err ? "<div class='notice'> ".pma_img('s_error.png')." $lang->Error : $_msg </div>" : "<div class='success'> ".pma_img('s_success.png')." ".str_replace("%s",$_msg,$lang->Column_saved)."</div>"; 
	}else {	

echo "<form action='?act=edit&$_url' method='post'>
$lang->colunm_name : <input type='text' name='name' value='".htmlentities($col_name,ENT_QUOTES)."'><br/>
$lang->type : ".make_select(0,'type',$_var->ColumnTypes,$type)."<br/>
$lang->Length : <input type='text' name='length' value='".htmlentities($length,ENT_QUOTES)."'><br/>
$lang->Default : ".make_select(1,'default',$_var->default_options,$col_data['DefaultType'])."<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;
 <input type='text' name='default2' value='".htmlentities($col_data['DefaultValue'],ENT_QUOTES)."'><br/>
$lang->Collation : ".make_select(0,'collation',getChar(),$collation,"<option value=''></option>")."<br/>
$lang->Attributes : ".make_select(0,'attribute',$_var->AttributeTypes,$attribute)."<br/>
<input type='checkbox' name='null'".($isnull == 1 ? " checked='checked'" : "")." value='1'>$lang->null<br/>
<input type='checkbox' name='auto' ".($isAI == 1 ? "checked='checked'" : "")." value='1'>$lang->AUTO_INCREMENT  <br/> 
$lang->Comments : <input type='text' name='comments' value='".htmlentities($comment,ENT_QUOTES)."'><br/>
<!-- <input type='radio' name='pos' value='0'> $lang->At_End_Of_Table <br/> -->
<input type='radio' name='pos' value='1'> $lang->At_Beginning_Of_Table <br/>
<input type='radio' name='pos' value='2'> $lang->After ".make_select(0,'pos2',$_cols)."<br/>

<br/>
<input type='submit' name='ok' value='$lang->create'>
</form>";
}


}else {
echo "&#187; ".pma_img('b_column.png').htmlentities($col_name)." </div><hr size='1px'>";
echo "<div align='left'><a href='?act=edit&$_url'>".pma_img('b_edit.png')."$lang->Edit</a> | <a href='?act=drop&$_url'>".pma_img('b_drop.png')."$lang->delete</a> | <a href='tbl_browse.php?$_url'>".pma_img("b_tbl.png")." $lang->Browse </a>
<br/><br/>
<table class='tb_border' cellspacing='0'>";

foreach($col_data as $c => $d)
	echo "<tr><td>".htmlentities($c)."</td><td>".htmlentities(str_replace(",",", ",$d))."</td></tr>"; // i added srt_replace because on mobile(opera mini) if the row is longer then the width of the screen and it has no spaces it goes offscreen

echo "</table></div>";


}