<?php
// wap phpmyadmin
// ionutvmi@gmail.com
// master-land.net
?>

<center><b><?php echo strtoupper($lang->Export);?></b></center>
<br/>
<?php
echo "<div class='left'>".$_var->home.($_GET['db'] ? "<a href='tables.php?db=".urlencode($db_name)."'>".pma_img('database.png').htmlentities($db_name)."</a> &#187;" : "").($_GET['tb'] ? "<a href='table.php?db=".urlencode($db_name)."&tb=".urlencode($tb_name)."'>".pma_img('b_tbl.png').htmlentities($tb_name)."</a> &#187;" : "")."  ".pma_img('b_tblexport.png').$lang->Export."</div><hr size='1px'>";

if(!$_POST['data']) {
if($_err){
	echo "<div class='notice'>".pma_img("s_error.png")." $lang->no_data </div>";
}else{

	 ?>

	<script type="text/javascript">
	function selectAll(selectBox,selectAll) {
		// have we been passed an ID
		if (typeof selectBox == "string") {
			selectBox = document.getElementById(selectBox);
		}

		// is the select box a multiple select box?
		if (selectBox.type == "select-multiple") {
			for (var i = 0; i < selectBox.options.length; i++) {
				selectBox.options[i].selected = selectAll;
			}
		}
	}
	</script>

	<?php
	echo "<form name='dump' action='?".$_SERVER['QUERY_STRING']."' method='post'>
	<a onclick=\"selectAll('selectbox1',true)\" />$lang->select_all</a>/<a onclick=\"selectAll('selectbox1',false)\" />$lang->unselect_all</a><br/>";
	if($_POST['_q']){
		echo "<div class='query'>".highlight_sql(base64_decode($_POST['_q']))."</div>
		
		<input type='hidden' name='data[]' value='".htmlentities($tb_name,ENT_QUOTES)."'>
		<input type='hidden' name='_q' value='".$_POST['_q']."'>
		";
	}elseif($_SESSION['records']){
		echo "<div class='query'>".highlight_sql("SELECT * FROM ".PMA_bkq($tb_name).$_SESSION['records'])."</div>
		<input type='hidden' name='data[]' value='".htmlentities($tb_name,ENT_QUOTES)."'>";
	}else{

		echo "<select name='data[]' id='selectbox1' size='5' multiple='multiple'>";
		foreach($list as $l){
			echo "<option value='".$l."'";
			if($_SESSION['selected']) {
				if(in_array($l,$_SESSION['selected']))
					echo " selected='selected'";
			}else {
				echo " selected='selected'";
			}
			echo ">".$l."</option>";
		}
		unset($_SESSION['selected']); // delete the selected list
		echo "</select>";
	}
	echo "<br/>
	$lang->output :
	<select name='output'>
		<option value='0'>$lang->save_as_sql_file </option>
		<option value='1'>$lang->save_as_zip_file </option>
		<option value='2'>$lang->view_as_text </option>
	</select><br/>
	$lang->Export : 
		<select name='export'>	
			<option value='0'>$lang->structure_data </option>
			<option value='1'>$lang->structure_only </option>
			<option value='2'>$lang->data_only </option>
		</select><br/>	
		<input type='checkbox' name='dset' value='1'> $lang->drop_set <br/>
	<input type='submit' value='$lang->Export'>
	</form>";
}
}else {
echo "<div class='success'> ".pma_img('s_success.png')." $lang->exported_successfully <br/>";
if($_POST['output']==2) {
	echo "</div> <form action='?' method='post'>
	<textarea name='tosave' rows='10' cols='40'>".htmlentities($_final_data)."</textarea><br/>
	<input type='submit' value='$lang->save_textarea'>
		</form>";
}else {
	echo "<br/> $lang->File <a href='$file_name'>$file_name </a> $lang->created<br/><br/></div>";
}

}
?>