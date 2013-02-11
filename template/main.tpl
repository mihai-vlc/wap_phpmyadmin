<?php
// wap phpmyadmin
// ionutvmi@gmail.com
// master-land.net

if($_GET['act'] == 'newdb') :
?>
<center><b><?php echo strtoupper($lang->add_db);?></b></center>
<br/>
<?php 
echo "<div class='left'>".$_var->home.pma_img('b_newdb.png').$lang->add_db."</div><hr size='1px'>";

echo $_err ? "<div class='notice'> ".pma_img('s_error.png')." $lang->Error : $_msg </div>" : "<div class='success'> ".pma_img('s_success.png')." ".str_replace("%s",$_msg,$lang->db_created)."</div>"; 
elseif($_GET['act'] == 'dropdb') :
?>
<center><b><?php echo strtoupper($lang->Drop);?></b></center>
<br/>
<?php 
echo "<div class='left'>".$_var->home.pma_img('b_drop.png').$lang->Drop."</div><hr size='1px'>";
if(!$_POST['ok']) {
printf("<div class='left'>".$lang->You_selected_databases,count($_POST['i']));
echo "<br/> ".$_msg[0]." <br/>".$lang->DO_YOU_WANT_TO_DELETE_DATABASES."<br/> <form action='?act=dropdb' method='post'>".$_msg[1]."<input type='submit' name='ok' value='".$lang->Yes."'> <a href='main.php'>".$lang->No."</a></div>";

} else {
echo $_err ? "<div class='notice'> ".pma_img('s_error.png')." $lang->Error : $_msg </div>" : "<div class='success'> ".pma_img('s_success.png')." ".str_replace("%s",$_msg,$lang->db_deleted)."</div>"; 
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
<center><b><?php echo strtoupper($lang->databases);?></b></center>
<div class='query'><?php echo highlight_sql($_q); ?></div>
<form action='?'>
<input type='text' name='search' size='10' value='<?php echo htmlentities($_GET['search'],ENT_QUOTES);?>'> 
<input type='submit' value='<?php echo $lang->Search;?>'>
</form>
<hr size='1px'>
<form action='?act=dropdb' method='post'>
<?php
if($_total > 0) :
foreach($db_dat as $d) : 

echo pma_img('database.png');
?>
<input type='checkbox' name='i[]' value='<?php echo htmlentities($d[0]);?>'> 
<a href='tables.php?db=<?php echo urlencode($d[0]);?>'><?php echo htmlentities($d[0]);?></a> <br/>

<?php endforeach;
if(ceil($_total / $perP) > 1)
	echo "<div class='pag'>$lang->Pages : ".$_pag->links()."</div>";
	
echo "<input type='checkbox' onClick='toggle(this)'>".$lang->With_selected.":<br/> <input type='submit' value='".$lang->Drop."'>";
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

<form action='?act=newdb' method='post'>
<?php echo pma_img('b_newdb.png').$lang->add_db; ?>:<br/>
<input type='text' name='db' size='10'> 
<input type='submit' value='<?php echo $lang->Go;?>'>
</form>
<?php endif; ?>