<?php
$passage=$_POST['text'];
function randLetter()
{
	$int = rand(0,25);
	$a_z = "abcdefghijklmnopqrstuvwxyz";
	$rand_letter = $a_z[$int];
	return $rand_letter;
}
function char2int($char)
{
	$int=ord($char)-97;
	return($int);
}
function int2char($int)
{
	$char=chr($int+97);
	return $char;
}
function encryption($passage){
	global $replace;
	$passage = strtolower($passage); //make the passage to lowercase
	$passage  = stripcslashes(trim($passage)); 
	//trim is for cuting unwanted spaces before and afterthe text
	//nl2br is for turning \n to HTML TAG <br />
	//stripcslashes is for cutting "/"
	$spassage = str_split($passage); //split passage into arrays [every character]
	$totalary = count($spassage);  //count the array number
	for($i=0;$i<$totalary;$i++){
		if(ord($spassage[$i])>=97&&ord($spassage[$i])<=122){
			$show[$i]=$replace[char2int($spassage[$i])];
		}else{
			$show[$i]=$spassage[$i];
		}
	}
	return $show;
}
function genreplace(){
	$replace=array();
	for($i=0;$i<26;$i++){
		do{
			$random=randLetter();
		}while(in_array($random, $replace));
		$replace[$i]=$random;
	}
	return $replace;
}
////////////////////////////////
$replace=genreplace();
$sentence=explode("\n",$passage);
for($i=0;$i<count($sentence);$i++){
	$show[$i]=encryption($sentence[$i]);
	$totalary[$i]=count(str_split($sentence[$i]));
}
for($i=0;$i<count($sentence);$i++){
?>
<div id="enpassage<?php echo $i;?>">
<?php
for($j=0;$j<$totalary[$i];$j++){
	echo $show[$i][$j];
}
?>
</div>
<?php }?>