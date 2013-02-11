<?php
// wap phpmyadmin
// ionutvmi@gmail.com
// master-land.net



echo "<div class='left'>".$_var->home."<a href='tables.php?db=".urlencode($db_name)."'>".pma_img('database.png').htmlentities($db_name)."</a> &#187; <a href='table.php?$_url'>".pma_img('b_tbl.png').htmlentities($tb_name)."</a> &#187; ".pma_img('b_search.png').$lang->Search." </div><hr size='1px'>
<div class='topbar'>
	<span><a href='tbl_browse.php?$_url'>".pma_img("b_tbl.png")." $lang->Browse </a></span>
	<span><a href='table.php?$_url'>".pma_img("b_props.png")." $lang->Structure </a></span>
	<span class='selected'><a href='tbl_search.php?$_url'>".pma_img("b_search.png")." $lang->Search </a></span>
	</div>
<form action='?$_url' method='post'>";

foreach($_cols as $cx){

echo "<b>".htmlentities($cx)."</b> ";

// here i didn't filter the option for 
// the type of column because i think is
// better for the user to choose
// + is faster this way
	echo '<select name="func[]">
	<option value="1">&gt;</option>
	<option value="2">&gt;=</option>
	<option value="3">&lt;</option>
	<option value="4">&lt;=</option>
	<option value="5" SELECTED>LIKE</option>
	<option value="6">LIKE %...%</option>
	<option value="7">NOT LIKE</option>
	<option value="8">=</option>
	<option value="9">!=</option>
	<option value="10">REGEXP</option>
	<option value="11">REGEXP ^...$</option>
	<option value="12">NOT REGEXP</option>
	<option value="13">= \'\'</option>
	<option value="14">!= \'\'</option>
	<option value="15">IN (...)</option>
	<option value="16">NOT IN (...)</option>
	<option value="17">BETWEEN</option>
	<option value="18">NOT BETWEEN</option>
	<option value="19">IS NULL</option>
	<option value="20">IS NOT NULL</option>
	</select>';
	
	echo "<input type='text' name='_v[]'><br/>";
}
echo "<input type='submit' value='$lang->Search'>
</form>
<div class='footbar'>	
	<span><a href='tbl_insert.php?$_url'>".pma_img("b_insrow.png")." $lang->Insert </a></span>
	<span><a href='table.php?".$_url."&act=empty'>".pma_img("bd_empty.png")." $lang->Empty </a></span>
	<span><a href='table.php?".$_url."&act=drop'>".pma_img("b_drop.png")." $lang->Drop </a></span>
	</div>";
	