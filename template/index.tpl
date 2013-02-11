<?php
// wap phpmyadmin
// ionutvmi@gmail.com
// master-land.net


if(!$link) :

echo $_err ? "<div class='notice'>".pma_img('s_error.png')." $lang->Error : $_msg !</div>" : ""; 
?>
<form action='?' class='center'>
<b> <?php echo $lang->Database_Server;?>: </b> <br/> <?php echo isset($_GET['c']) ? "<input type='text' name='host' value='localhost'><input type='hidden' name='c'>" : "<i>localhost</i><input type='hidden' name='host' value='localhost'> <small>(<a href='?c'>$lang->change </a>)</small>"; ?> <br/>
<b> <?php echo $lang->Database_User;?>: </b> <br/> <input type='text' name='user' value='root'><br/>
<b> <?php echo $lang->Database_Password;?>: </b> <br/> <input type='password' name='pass'><br/>
<b> <?php echo $lang->Database_Name;?>: </b> <br/> <input type='text' name='db'><br/>
<br/><input type='submit' value='<?php echo $lang->Go;?>'>
</form>
<?php 
else: 

if($pma->version != file_get_contents("http://master-land.net/phpmyadmin/update.txt"))
	printf("<div class='success'>".pma_img('s_success.png')." $lang->new_version </div>","http://master-land.net/phpmyadmin");

echo pma_img('s_success.png')."<br/>".$lang->WELCOME." <b><i>".strtoupper($_SESSION['user']); ?></i></b><br/><br/>

&#187; <a href='<?php echo $link; ?>'> <?php echo $lang->ENTER; ?> </a> &#171;
<br/><br/>
<?php
echo $lang->Bookmark;
 endif ?>