<?php
// wap phpmyadmin
// ionutvmi@gmail.com
// master-land.net

include "lib/settings.php";

$d = file_get_contents("lib/documentation.txt");


$pma->title=$lang->docs;
include $pma->tpl."header.tpl";
echo "<div class='left'>".$_var->home."$lang->docs ";
$d = preg_replace("/=(.+)=/iU","<h4> $1 </h4>",$d);
$d = preg_replace("/`(.+)`/iU","<b> $1 </b>",$d);
echo nl2br($d);
echo "</div>";

include $pma->tpl."footer.tpl";