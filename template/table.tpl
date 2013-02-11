<?php
// wap phpmyadmin
// ionutvmi@gmail.com
// master-land.net
if($act=='empty')
{
	echo "<div class='left'>".$_var->home."<a href='tables.php?db=".urlencode($db_name)."'>".pma_img('database.png').htmlentities($db_name)."</a> &#187; <a href='?$_url'>".pma_img('b_tbl.png').htmlentities($tb_name)."</a> &#187; ".pma_img('bd_empty.png')." $lang->Empty </div><hr size='1px'>
	<div class='topbar'>
	<span><a href='tbl_browse.php?$_url'>".pma_img("b_tbl.png")." $lang->Browse </a></span>
	<span><a href='?$_url'>".pma_img("b_props.png")." $lang->Structure </a></span>
	<span><a href='tbl_search.php?$_url'>".pma_img("b_search.png")." $lang->Search </a></span>
	</div><br/><br/>";

	if($_POST['ok']){
		echo "<div class='query'>".highlight_sql($_q)."</div>";
		echo $_err ? "<div class='notice'> ".pma_img('s_error.png')." $lang->Error : $_msg </div>" : "<div class='success'> ".pma_img('s_success.png')."<b> $_msg </b>".$lang->Was_emptyed."</div>"; 
	}else {	
		echo "<form action='?act=empty&$_url' method='post'>
		$lang->TRUNCATE_TABLE <br/>
		<input type='submit' value='$lang->Yes' name='ok'> <a href='?$_url'> $lang->No </a>
		</form>";
	}
	echo "<br/><br/>
	<div class='footbar'>	
	<span><a href='tbl_insert.php?$_url'>".pma_img("b_insrow.png")." $lang->Insert </a></span>
	<span class='selected'><a href='?".$_url."&act=empty'>".pma_img("bd_empty.png")." $lang->Empty </a></span>
	<span><a href='?".$_url."&act=drop'>".pma_img("b_drop.png")." $lang->Drop </a></span>
	</div>";

}elseif($act=='drop')
{
	echo "<div class='left'>".$_var->home."<a href='tables.php?db=".urlencode($db_name)."'>".pma_img('database.png').htmlentities($db_name)."</a> &#187; <a href='?$_url'>".pma_img('b_tbl.png').htmlentities($tb_name)."</a> &#187; ".pma_img('b_drop.png')." $lang->Drop </div><hr size='1px'>
	<div class='topbar'>
	<span><a href='tbl_browse.php?$_url'>".pma_img("b_tbl.png")." $lang->Browse </a></span>
	<span><a href='?$_url'>".pma_img("b_props.png")." $lang->Structure </a></span>
	<span><a href='tbl_search.php?$_url'>".pma_img("b_search.png")." $lang->Search </a></span>
	</div><br/><br/>";

	if($_POST['ok']){
		echo "<div class='query'>".highlight_sql($_q)."</div>";
		echo $_err ? "<div class='notice'> ".pma_img('s_error.png')." $lang->Error : $_msg </div>" : "<div class='success'> ".pma_img('s_success.png')." ".str_replace("%s",$_msg,$lang->Table_droped)."</div>"; 
	}else {	
		echo "<form action='?act=drop&$_url' method='post'>
		$lang->DROP_DELETE_TABLE <br/>
		<input type='submit' value='$lang->Yes' name='ok'> <a href='?$_url'> $lang->No </a>
		</form>";
	}
	echo "<br/><br/>
	<div class='footbar'>	
	<span><a href='tbl_insert.php?$_url'>".pma_img("b_insrow.png")." $lang->Insert </a></span>
	<span><a href='?".$_url."&act=empty'>".pma_img("bd_empty.png")." $lang->Empty </a></span>
	<span class='selected'><a href='?".$_url."&act=drop'>".pma_img("b_drop.png")." $lang->Drop </a></span>
	</div>";

}elseif($act=='settings')
{
echo "<div class='left'>".$_var->home."<a href='tables.php?db=".urlencode($db_name)."'>".pma_img('database.png').htmlentities($db_name)."</a> &#187; <a href='?$_url'>".pma_img('b_tbl.png').htmlentities($tb_name)."</a> &#187; ".pma_img('b_tblops.png')." $lang->operations </div><hr size='1px'>";
if($_POST['ok'] || $_GET['ok']){
	if($_SESSION['_q']){ $_q=$_SESSION["_q"];$_SESSION["_q"]='';}
		echo "<div class='query'>".highlight_sql($_q)."</div>";
		echo $_err ? "<div class='notice'> ".pma_img('s_error.png')." $lang->Error : $_err </div>" : "<div class='success'> ".pma_img('s_success.png')." ".$lang->sql_ok."</div>"; 
	}
	// rename/copy/alter
		echo "
		<form action='?act=settings&$_url' method='post'>
		".ucfirst($lang->rename_table).": <br/>".make_select(0,'db_nm',$_dbs,$db_name)." . <input type='text' name='newnm' value='".htmlentities($tb_name,ENT_QUOTES)."'>
		<br/><input type='submit' name='ok' value='$lang->Rename'></form><hr size='1px'>
		
		
		<form action='?act=settings&$_url' method='post'>
		".ucfirst($lang->copy_table).": <br/>".make_select(0,'db_nm',$_dbs,$db_name)." . <input type='text' name='copy' value='".htmlentities($tb_name,ENT_QUOTES)."'><br/>
		<select name='type'>	
		<option value='0'>$lang->structure_data </option>
		<option value='1'>$lang->structure_only </option>
		<option value='2'>$lang->data_only </option>
		</select>
		<br/><input type='submit' name='ok' value='$lang->Copy'></form><hr size='1px'>
		
		
		<form action='?act=settings&$_url' method='post'>
		".ucfirst($lang->alter_table_order_by).": <br/>".make_select(0,'cl_nm',$_cols)."
		<select name='type'>
		<option value='asc'>$lang->Asc</option>
		<option value='desc'>$lang->Desc</option>
		</select>
		<br/><input type='submit' name='ok' value='$lang->Go'></form>
		";




}elseif($act=='new')
{
echo "<div class='left'>".$_var->home."<a href='tables.php?db=".urlencode($db_name)."'>".pma_img('database.png').htmlentities($db_name)."</a> &#187; <a href='?$_url'>".pma_img('b_tbl.png').htmlentities($tb_name)."</a> &#187; $lang->add_column </div><hr size='1px'>";
if($_POST['ok']){
	echo "<div class='query'>".highlight_sql($_q)."</div>";
	echo $_err ? "<div class='notice'> ".pma_img('s_error.png')." $lang->Error : $_msg </div>" : "<div class='success'> ".pma_img('s_success.png')." ".str_replace("%s",$_msg,$lang->column_created)."</div>"; 
	}else {	

echo "<form action='?act=new&$_url' method='post'>
$lang->colunm_name : <input type='text' name='name' value='id'><br/>
$lang->type : ".make_select(0,'type',$_var->ColumnTypes)."<br/>
$lang->Length : <input type='text' name='length'><br/>
$lang->Default : ".make_select(1,'default',$_var->default_options)."<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;
 <input type='text' name='default2'><br/>
$lang->Collation : ".make_select(0,'collation',getChar(),false,"<option value=''></option>")."<br/>
$lang->Attributes : ".make_select(0,'attribute',$_var->AttributeTypes)."<br/>
$lang->null : <input type='checkbox' name='null' value='1'><br/>
$lang->index : ".make_select(0,'index',$_var->index)."<br/>
$lang->AUTO_INCREMENT : <input type='checkbox' name='auto' value='1'><br/> 
$lang->Comments : <input type='text' name='comments'><br/>
<input type='radio' name='pos' value='0'> $lang->At_End_Of_Table <br/>
<input type='radio' name='pos' value='1'> $lang->At_Beginning_Of_Table <br/>
<input type='radio' name='pos' value='2'> $lang->After ".make_select(0,'pos2',$_cols)."<br/>

<br/>
<input type='submit' name='ok' value='$lang->create'>
</form>";
}

}elseif($act=='delind')
{
if($_POST['ok'])
	echo "<div class='query'>".highlight_sql($_q)."</div>";
echo "<div class='left'>".$_var->home."<a href='tables.php?db=".urlencode($db_name)."'>".pma_img('database.png').htmlentities($db_name)."</a> &#187; <a href='?$_url'>".pma_img('b_tbl.png').htmlentities($tb_name)."</a> &#187; $lang->delete </div><hr size='1px'>";

if(!$_POST['ok']) {
printf("<div class='left'>".$lang->sure_delete."</div>",htmlentities($_GET['name']));
echo "<br/> <form action='?act=delind&name=".urlencode($_GET['name'])."&$_url' method='post'><input type='submit' name='ok' value='".$lang->Yes."'> <a href='?$_url'>".$lang->No."</a></div>";
} else {

	if($_err){ 
		echo "<div class='notice'> ".pma_img('s_error.png')." $lang->Error : $_err </div>";
	} else {
		echo "<div class='success'> ".pma_img('s_success.png')." ".$lang->sql_ok."</div>"; 
	}

}

}elseif($act=='multi')
{
if($_POST['ok'])
	echo "<div class='query'>".highlight_sql($_q)."</div>";
echo "<div class='left'>".$_var->home."<a href='tables.php?db=".urlencode($db_name)."'>".pma_img('database.png').htmlentities($db_name)."</a> &#187; <a href='?$_url'>".pma_img('b_tbl.png').htmlentities($tb_name)."</a> &#187; ".($_POST['todo'] == 'drop' ? $lang->Drop : $lang->$_POST['todo'])."</div><hr size='1px'>";

if(!$_POST['ok']) {
printf("<div class='left'>".$lang->You_selected_columns."<br/>",count($_POST['i']));
echo $_msg[0];
if($_POST['todo'] == 'drop')
echo $lang->DO_YOU_WANT_TO_DELETE_COLUMNS;
else
printf($lang->do_you_want_to_set_key,$lang->$_POST['todo']);

echo "<br/> <form action='?act=multi&$_url' method='post'><input type='hidden' name='todo' value='".$_POST['todo']."'>".$_msg[1]."<input type='submit' name='ok' value='".$lang->Yes."'> <a href='?$_url'>".$lang->No."</a></div>";
} else {

	if($_err){ 
		echo "<div class='notice'> ".pma_img('s_error.png')." $lang->Error : $_err </div>";
	} else {
		echo "<div class='success'> ".pma_img('s_success.png')." ".$lang->sql_ok."</div>"; 
	}

}

} else 
{
echo "<div class='query'>".highlight_sql($_q)."</div>";
echo "<div class='left'>".$_var->home."<a href='tables.php?db=".urlencode($db_name)."'>".pma_img('database.png').htmlentities($db_name)."</a> &#187; ".pma_img('b_tbl.png').htmlentities($tb_name)."</div>
<div class='topbar'>
	<span><a href='tbl_browse.php?$_url'>".pma_img("b_tbl.png")." $lang->Browse </a></span>
	<span class='selected'><a href='?$_url'>".pma_img("b_props.png")." $lang->Structure </a></span>
	<span><a href='tbl_search.php?$_url'>".pma_img("b_search.png")." $lang->Search </a></span>
</div>";

echo "<div class='left'>
<a href='?act=new&$_url'>".pma_img('b_insrow.png')." ".ucfirst($lang->add_column)." </a> | 
<a href='?act=settings&$_url'>".pma_img('b_tblops.png')." ".ucfirst($lang->operations)." </a> <br/><br/>
</div>";
?>
<script type="text/javascript">
function toggle(source) {
  checkboxes = document.getElementsByName('i[]');
  for(var i in checkboxes)
    checkboxes[i].checked = source.checked;
}
</script>
<form action='?'>
<input type='hidden' name='db' value='<?php echo urlencode($db_name);?>'>
<input type='hidden' name='tb' value='<?php echo urlencode($tb_name);?>'>
<input type='text' name='search' size='10' value='<?php echo htmlentities($_GET['search'],ENT_QUOTES);?>'> 
<input type='submit' value='<?php echo $lang->Search;?>'>
</form>
<hr size='1px'>
<?php
echo "
<form action='?".$_url."&act=multi' method='post'>";
foreach($col_data as $c) {
	echo "<input type='checkbox' name='i[]' value='$c->Field'> <a href='tbl_column.php?".$_url."&col=".urlencode($c->Field)."'> ".htmlentities($c->Field)." </a>".str_replace(',',', ',$c->Type)." ".$c->Null." ".$c->Default." ".$c->Extra." ".$c->Key."<br/>"; // str_replace is for avoiding going offscreen
}
echo "- - -<br/>
<input type='checkbox' onClick='toggle(this)'>
<select name='todo'>
<option>$lang->With_selected </option>
<option value='drop'>$lang->Drop</option>
<option value='PRIMARY'>$lang->PRIMARY</option>
<option value='UNIQUE'>$lang->UNIQUE</option>
<option value='FULLTEXT'>$lang->FULLTEXT</option>
<option value='INDEX'>$lang->INDEX</option>
</select>
<input type='submit' value='$lang->Go'>
</form>- - -<br/>";

echo "<div class='left'>$lang->indexes : <br/>";
if($ind_data) {
echo "<table class='tb_border' cellspacing='0'>
<tr><th>$lang->Keyname</th><th>$lang->Column</th><th>$lang->UNIQUE</th><th>$lang->delete</th></tr>";
foreach($ind_data as $ind) {
echo "<tr><td>".htmlentities($ind->Key_name)."</td><td>".htmlentities($ind->Column_name)."</td><td>".($ind->Non_unique == 0 ? $lang->Yes : $lang->No)."</td><td><a href='?act=delind&name=".urlencode($ind->Key_name)."&$_url'>".pma_img('b_drop.png')."-</a></td></tr>"; // Key_name Non_unique Column_name
}
echo "</table>";
}//end ind_data
else {
	echo "<div class='notice'> ".pma_img('s_error.png')." $lang->no_index</div>";
}
echo "<br/></div>";



echo "
<div class='footbar'>	
	<span><a href='tbl_insert.php?$_url'>".pma_img("b_insrow.png")." $lang->Insert </a></span>
	<span><a href='?".$_url."&act=empty'>".pma_img("bd_empty.png")." $lang->Empty </a></span>
	<span><a href='?".$_url."&act=drop'>".pma_img("b_drop.png")." $lang->Drop </a></span>
</div>";

} // end else