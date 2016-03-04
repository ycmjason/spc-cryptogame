<?php
include("passage.php");

//////////////////////////////////////////
$rand=rand(0,(count($p)-1));
$passage=$p[$rand];
if(isset($_GET['p'])&&isset($pa[$_GET['p']])){
	$passage=$pa[$_GET['p']];
}
$passage=wordwrap($passage, 88, "\n");

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

function fqcpct($passage){
	$totalary = count($passage);  //count the array number
	$charsum=0;
	for($i=0;$i<26;$i++){
		$fre[$i]=0;
	}
	for($i=0;$i<26;$i++){
		for($j=0;$j<$totalary;$j++){
			if(ord($passage[$j])>=97&&ord($passage[$j])<=122&&char2int($passage[$j])==$i){
				$charsum++;
				$fre[$i]++;
			}
		}
	}
	for($i=0;$i<26;$i++){
		$fqcpct[$i]['fqc']=($fre[$i]/$charsum)*100;
		$fqcpct[$i]['cha']=int2char($i);
	}
	return $fqcpct;
}
function bsort($value){
	$x = count ( $value ) ;
	$check=1;
	while($check>0){
		$check=0;
		for ( $i=0 ; $i<$x; $i++ ) {
			if ( $value[$i]['fqc'] < $value[$i+1]['fqc'] ) {
				$temp=$value[$i];
				$value[$i]['fqc']=$value[$i+1]['fqc'];
				$value[$i]['cha']=$value[$i+1]['cha'];
				$value[$i+1]['fqc']=$temp['fqc'];
				$value[$i+1]['cha']=$temp['cha'];
				$check++;
			}
		}
	}
	return $value;
}
////////////////////////////////
$replace=genreplace();
$sentence=explode("\n",$passage);
for($i=0;$i<count($sentence);$i++){
	$show[$i]=encryption($sentence[$i]);
	$totalary[$i]=count(str_split($sentence[$i]));
}
$fqcpct=fqcpct(encryption($passage));
$displayfqc=bsort($fqcpct);
//echo print_r($fqcpct);
?>
<html>
	<head>
		<script type="text/javascript" src="jquery.js"></script>
		<script type="text/javascript">     
			function char2int(word)
			{
				integer=word.charCodeAt(0)-97;
				return integer;
			}
			function changeguess(){
				var guess=[
				$("#a").val(),
				$("#b").val(),
				$("#c").val(),
				$("#d").val(),
				$("#e").val(),
				$("#f").val(),
				$("#g").val(),
				$("#h").val(),
				$("#i").val(),
				$("#j").val(),
				$("#k").val(),
				$("#l").val(),
				$("#m").val(),
				$("#n").val(),
				$("#o").val(),
				$("#p").val(),
				$("#q").val(),
				$("#r").val(),
				$("#s").val(),
				$("#t").val(),
				$("#u").val(),
				$("#v").val(),
				$("#w").val(),
				$("#x").val(),
				$("#y").val(),
				$("#z").val()
				];
				<?php
				for($i=0;$i<count($sentence);$i++){?>
				$('#guess<?php echo $i;?>').html("").show();
				var spassage=[<?php
				for($j=0;$j<$totalary[$i];$j++){
					echo "\"".addslashes($show[$i][$j])."\"";
					if($i!=$totalary[$i]-1){
						echo ",";
					}
				}
				?>];
				var totalary=spassage.length;
				var ascii;
				var ok;
				var show="";
				for(var i=0;i<totalary;i++){
					ok=0;
					ascii=spassage[i].charCodeAt(0);
					if(ascii>=97&&ascii<=122){
						ascii=char2int(spassage[i]);
					}else{
						ascii=spassage[i].charCodeAt(0);
					}
					for(var j=0;j<26;j++){
						if(ascii==j){
							if(guess[j]!=""){
								show+=guess[j];
							}
							else{
								show+="&nbsp;";
							}
							ok=1;
						}
					}
					if(ok!=1){
						show+="&nbsp;";
					}
				}
				$('#guess<?php echo $i;?>').html(show).show();
				<?php }?>
			}
			function generateurl(){
				var text;
				text=$('#textc').val();
				$.post('generatec.php',{text:text,id:<?php echo count($pa)?>},
					function(output){
						$('#generatec').html(output).show();
					});
			}
			function generateown()
			{
				var text;
				text=$('#text').val();
				$.post('generate.php',{text:text},
					function(output){
						$('#generateown').html(output).show();
					});
			}
		</script>    
	</head>
	<body style="font-size:18px;">
	<a href="index.php"><img src="http://www.spc.edu.hk/160/160logo_225_260.gif" style="width:100"/></a>

	<div style="float:right;width:250px;border:2px black solid;">
			<div style="margin-bottom:10px;"><u>Frequency Analysis</u></div>
		<div style="float:left;width:120px;">
			This passage<br />
			<tt>
			<?php
			for($i=0;$i<26;$i++){
				echo $displayfqc[$i]['cha']." : ".substr($displayfqc[$i]['fqc'],0,4)."<br />";
			}
			?>
		</div>
		<div style="float:right;width:129px;">
		<?php
		echo nl2br("</tt>In English<tt>
e: 12.7
t: 9.1
a: 8.2
i: 7.0
n: 6.7
o: 6.3
h: 6.1
r: 6.0
d: 4.3
q: 4.3
l: 4.0
c: 2.8
u: 2.8
m: 2.4
w: 2.3
f: 2.2
s: 2.2
g: 2.0
y: 2.0
p: 1.9
b: 1.5
v: 1.0
k: 0.8
j: 0.2
x: 0.1
z: 0.1</tt>");
		?>
		</div>
		</div>
		<form>
	<tt>
		Please guess:<p>
		
		<?php
		for($i=0;$i<26;$i++){
		$char=int2char($i);
		?>
		<?php echo $char?>
		<input id="<?php echo $char?>" type="text" size="1" onKeyUp="changeguess();"/>
		<?php
		if($i==12){
			echo "<br />";
		}
		}
		?>
		</p>
		
		<?php
		for($i=0;$i<count($sentence);$i++){
		?>
		<div id="guess<?php echo $i;?>" style="color:red;">&nbsp;</div>
		<div id="enpassage<?php echo $i;?>">
		<?php
		for($j=0;$j<$totalary[$i];$j++){
			echo $show[$i][$j];
		}
		?>
		</div>
		<?php }?>
		</tt>
		<input type="reset" onmouseup="changeguess();$('.guess').html('&nbsp;');" />

</form>
<form>
Make a challenge to your friends!<br />
<textarea id="textc" rows="6" cols="50"></textarea><br />
<input type="button" value="Send" onClick="generateurl();" />
<div id="generatec">&nbsp;</div>
</form>
		<div style="margin-top:50px">
		<form name="form">
		Make Your Own Encrypted Text!<br />
		<textarea id="text" rows="4" cols="40"></textarea><br />
		<input type="button" value="Generate!" onClick="generateown();" />
		<div id="generateown">&nbsp;</div>
		</form>
		</div>
	</body>
</html>