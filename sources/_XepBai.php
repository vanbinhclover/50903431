<?php
$ndk = new sMain();
class sMain{
var $output="";
var $baseurl="";
var $html=array();
// Start func
function sMain(){
global $DB,$func,$NDK,$input;
	$data['nav']="X&#7871;p B&#224;i -> <a href=\"#\" onclick=\"resetGame();return false\"><b><font color=\"#0000FF\">B&#224;n m&#7899;i</font></b></a>";
	$this->output .= $this->skinmain($data);
	$NDK->output .= $this->output;
}
// Skin
function skinmain($data){
global $NDK,$input;
$thu_arr=array(
	'0' => "Ch&#7911; nh&#7853;t",
	'1' => "Th&#7913; hai",
	'2' => "Th&#7913; ba",
	'3' => "Th&#7913; t&#432;",
	'4' => "Th&#7913; n&#259;m",
	'5' => "Th&#7913; s&#225;u",
	'6' => "Th&#7913; b&#7843;y",
);
$thu_num=date("w",time());
$thu=$thu_arr[$thu_num];
$date=$thu.date(" d/m/Y, h:i a",time());

$nav="&nbsp;<a href=\"index.php\">{$NDK->conf['webname']}</a> &raquo; ".$data['nav'];

if (file_exists("images/title/".$input['act'].".gif")) {
	$title="<img src=\"images/title/".$input['act'].".gif\" border=0>";
}

return<<<EOF
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="100%" align=right>{$date}</td>
</tr>
<tr>
<td width="100%" align=left>{$nav}</td>
</tr>
<tr>
<td width="100%" align=center>{$title}</td>
</tr>
<tr>
<td width="100%">
<link rel="stylesheet" href="skins/solitaire.css" media="screen"></LINK>
	<script type="text/javascript">	
	var cardBackgroundArray = new Array();
	cardBackgroundArray.push('images/solitaire/card_bg1.gif');	
	cardBackgroundArray.push('images/solitaire/card_bg2.gif');	
	</script>
	<script type="text/javascript" src="js/tretoday_solitaire.js"></script>
	<SCRIPT type="text/javascript">
	window.onload = initSolitaire;	
	</script>

<div id="bg_aces">


</div>
<div id="bg_seven">

</div>

<div id="bg_deck">
	<div id="bg_deck_inner"></div>
	<div id="bg_deck_shown"></div>
</div>
</div>

<div id="movingCardContainer"></div>
</td>
</tr>
</table>
EOF;
}

// end class

}

?>