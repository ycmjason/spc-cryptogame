<?php
$passage=$_POST['text'];
$id=$_POST['id'];
$content="\$pa[]=\"".$passage."\";
";
$file = fopen("passage.php","a");


fwrite($file, $content);
fclose($file);
echo "Here\'s your Challenge for your friends. please copy the link and tell your friends.<br />
<a href=\"http://elise.ycmjason.com/?p=".$id."\">http://elise.ycmjason.com/?p=".$id."</a>";
?>
