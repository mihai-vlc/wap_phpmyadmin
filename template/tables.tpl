<?php
// wap phpmyadmin
// ionutvmi@gmail.com
// master-land.net

if($_GET['act'] == 'newtb') :
?>
<center><b><?php echo strtoupper($lang->Add_table);?></b></center>
<br/>
<?php if($_POST['ok']) { ?>
<div class='query'><?php echo highlight_sql($_q); ?></div>
<?php 
}
echo "<div class='left'>".$_var->home."<a href='?db=".urlencode($db_name)."'>".pma_img('database.png').htmlentities($db_name)."</a> &#187; ".pma_img('b_snewtbl.png').$lang->Add_table."</div><hr size='1px'>";
if($_POST['ok']) {

echo $_err ? "<div class='notice'> ".pma_img('s_error.png')." $lang->Error : $_msg </div>" : "<div class='success'> ".pma_img('s_success.png')." ".str_replace("%s",$_msg,$lang->table_created)."</div>"; 
} else {

echo "<form action='?act=newtb&db=".urlencode($db_name)."' method='post'>
$lang->table_name : <input type='text' name='tb' value='".htmlentities($_POST['tb'],ENT_QUOTES)."'>
<hr>
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
$lang->Comments : <input type='text' name='comments'><br/><br/>
<input type='submit' name='ok' value='$lang->Save'>
</form>";
}
elseif($_GET['act'] == 'multi') :

if($_POST['do'] != 'export' && $_POST['do'] != 'empty' && $_POST['do'] != 'drop')
	$do_lg=$_POST['do']."_tb";
		else
			$do_lg=ucfirst($_POST['do']);

?>
<center><b><?php echo strtoupper(htmlentities($lang->$do_lg));?></b></center>
<br/>
<?php 
echo "<div class='left'>".$_var->home."<a href='?db=".urlencode($db_name)."'>".pma_img('database.png').htmlentities($db_name)."</a> &#187; ".$lang->$do_lg." </div><hr size='1px'>";

if(!$_POST['ok']) {
printf("<div class='left'>".$lang->You_selected_tables,count($_POST['i']));
echo "<br/> ".$_msg[0]." <br/>";
printf($lang->DO_YOU_WANT_TO_ACT_TABLE,strtoupper($lang->$do_lg));
echo "<br/> <form action='?act=multi&db=".urlencode($db_name)."' method='post'><input type='hidden' name='do' value='".$_POST['do']."'>".$_msg[1]."<input type='submit' name='ok' value='".$lang->Yes."'> <a href='main.php'>".$lang->No."</a></div>";

} else {
	if($_POST['do'] =='drop' || $_POST['do'] == 'empty')
	{
		foreach($_msg as $_msg) 
		{
			if($_err){ 
			echo "<div class='notice'> ".pma_img('s_error.png')." $lang->Error : $_msg </div>";
			} else {
			echo "<div class='success'> ".pma_img('s_success.png')." ".$_msg."</div>"; 
			 }
		}
	}	
	else
	{
echo "	<table class='tb_border' cellspacing='0'> 
<tr><th>Table</th><th>Msg_type</th><th>Msg_text</th></tr>";
		foreach($_msg as $k => $_msg) 
		{
			echo "<tr><td>".$k."</td><td>".$_msg[0]."</td><td>".$_msg[1]."</td></tr>"; 
			 
		}
echo "</table>";
	}
}
else :
?>
<script type="text/javascript">
function toggle(source) {
  checkboxes = document.getElementsByName('i[]');
  for(var i in checkboxes)
    checkboxes[i].checked = source.checked;
}
</script>
<center><b><?php echo strtoupper($lang->tables);?></b></center>

<div class='query'><?php echo highlight_sql($_q); ?></div>

<?php echo "<div class='left'>".$_var->home.pma_img('database.png').htmlentities($db_name)."</div><hr size='1px'>"; ?>
<form action='?'>
<input type='hidden' name='db' value='<?php echo urlencode($db_name);?>'>
<input type='text' name='search' size='10' value='<?php echo htmlentities($_GET['search'],ENT_QUOTES);?>'> 
<input type='submit' value='<?php echo $lang->Search;?>'>
</form>
<hr size='1px'>
<form action='?act=multi&db=<?php echo urlencode($db_name);?>' method='post'>
<?php
if($_total > 0) :
foreach($db_dat as $d) : 

echo pma_img('b_tbl.png');
?>
<input type='checkbox' name='i[]' value='<?php echo htmlentities($d[0]);?>'> 
<a href='table.php?tb=<?php echo urlencode($d[0])."&db=".urlencode($db_name);?>'><?php echo htmlentities($d[0]);?></a> <?php echo convert($d[6]+$d[8])." | ".$d[4]." ".($d[4]==1 ? $lang->row : $lang->rows);?> <br/>

<?php endforeach;
if(ceil($_total / $perP) > 1)
	echo "<div class='pag'>$lang->Pages : ".$_pag->links()."</div>";
	
echo "<input type='checkbox' onClick='toggle(this)'>".$lang->With_selected.":<br/> 
".make_select(1,'do',$_var->tb_do)."
<input type='submit' value='".$lang->Go."'>";
else : 
echo "<div class='notice'>".pma_img('s_error.png')." $lang->no_data </div>";
endif;
?>


</form>

<hr size='1px'>

<form action='?perp&<?php foreach($_GET as $k =>$v) {if($k !='perp'){echo urlencode($k)."=".urlencode($v)."&";} }?>' method='post'>
<?php echo $lang->Show;?> 
<select name='perp'>
<?php 
foreach($_var->perp as $nr) {
	echo $nr == $_SESSION['perp'] ? "<option value='$nr' SELECTED>$nr</option>" : "<option value='$nr'>$nr</option>";}
?>
</select>
<input type='submit' value='<?php echo $lang->Per_Page;?>'>
</form>

<hr size='1px'>

<form action='?act=newtb&db=<?php echo urlencode($db_name);?>' method='post'>
<?php echo pma_img('b_snewtbl.png').$lang->Add_table; ?>:<br/>
<input type='text' name='tb' size='10'> 
<input type='submit' value='<?php echo $lang->Go;?>'>
</form>
<?php endif; ?>