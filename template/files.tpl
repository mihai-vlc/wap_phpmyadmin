<?php
// wap phpmyadmin
// ionutvmi@gmail.com
// master-land.net
if($act=='delete'){
echo "<div class='left'>".$_var->home.($_GET['db'] ? "<a href='tables.php?db=".urlencode($db_name)."'>".pma_img('database.png').htmlentities($db_name)."</a> &#187;" : "")."<a href='?'>".pma_img("files.png")." $lang->Files </a> &#187; ".pma_img("b_drop.png")."$lang->delete</div><hr size='1px'>";

	if($_POST['ok'])
		printf("<div class='success'>".pma_img('s_success.png')." $lang->files_deleted </div>",$_msg);
	else{
		printf("<form action='?act=delete' method='post'>".$lang->You_selected_files."<br/> <i>".implode("<br/>",$_POST['i'])."</i> $_m <br/><br/>".$lang->DELETE_SELECTED."? <br/><br/><input type='submit' name='ok' value='$lang->Yes'><a href='?'>$lang->No</a></form>",count($_POST['i']));
		
	}
}else {
?>
<script type="text/javascript">
function toggle(source) {
  checkboxes = document.getElementsByName('i[]');
  for(var i in checkboxes)
    checkboxes[i].checked = source.checked;
}
</script>
<?php
echo "<div class='left'>".$_var->home.($_GET['db'] ? "<a href='tables.php?db=".urlencode($db_name)."'>".pma_img('database.png').htmlentities($db_name)."</a> &#187;" : "").pma_img("files.png")." $lang->Files </div><hr size='1px'><div class='left'>
<form action='?'><input type='text' name='search' value='".htmlentities($_GET['search'],ENT_QUOTES)."'> <input type='submit' value='$lang->Search'></form><hr size='1px'>
<form action='?act=delete&db=".urlencode($_GET['db'])."' method='post'>";
if($_total > 0){
	foreach($fl_dt as $fl){
	echo pma_img("file.png")."<input type='checkbox' name='i[]' value='$fl'> <a href='$fl'>".basename($fl)."</a> (".convert(filesize($fl)).") ".pma_img("b_tblimport.png")." <a href='import.php?".($db_name ? "db=".urlencode($db_name) : "")."&file=$fl'>$lang->Import </a><br/>";
	}
echo "<input type='checkbox' onClick='toggle(this)'>".$lang->With_selected.":<br/> <input type='submit' value='".$lang->delete."'>";

if(ceil($_total / $perP) > 1)
	echo "<div class='pag'>$lang->Pages : ".$_pag->links()."</div>";

echo "</div></form><br/><form action='?' method='post'>".$lang->Show." <select name='perp'>";
foreach($_var->perp as $nr) {
	echo $nr == $_SESSION['perp'] ? "<option value='$nr' SELECTED>$nr</option>" : "<option value='$nr'>$nr</option>";}

echo "</select>
<input type='submit' value='$lang->Per_Page'>
</form>";
}else{
	echo "</div><div class='notice'>".pma_img("s_error.png")." $lang->No_files </div>";
}
}