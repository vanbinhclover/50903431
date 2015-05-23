<?php
$ndk = new sMain();
class sMain{
var $output="";
var $baseurl="";
var $html=array();
// Start func
function sMain(){
global $DB,$func,$NDK,$input;
	$data['nav']="X&#7871;p H&#236;nh";
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
<style type="text/css">
	#puzzle_container{
		line-height:500px;
		text-align:center;
		vertical-align:center;
		border:10px outset #123456;
		position:relative;
		color: #FFFFFF;
		background-color: #777777;
		
		width: 520px;	/* IE 5.x */
		width/* */:/**/500px;	/* Other browsers */
		width: /**/500px;	
		
		height: 520px;	/* IE 5.x */
		height/* */:/**/500px;	/* Other browsers */
		height: /**/500px;			

	}
	
	#puzzle_container .square{
		overflow:hidden;
		border-left:1px solid #FFF;
		border-top:1px solid #FFF;
		position:absolute;
	}

	.activeImageIndicator{
		border:1px solid #FF0000;
		position:absolute;
		z-index:10000;
	}
	.revealedImage{
		position:absolute;
		left:0px;
		opacity:0;
		filter:alpha(opacity=50);
		top:0px;
		z-index:50000;
	}
</style>
<script type="text/javascript" src="js/puzzle.js"></script>
<script language="javascript">
window.onload = initPuzzle;
</script>
<div style="padding:5px" style="background-color:#FFFFFF; border:1px solid #CCCCCC">
<a href="#" onclick="scramble();return false"><b><font color="#0000FF">X&#225;o tr&#7897;n h&#236;nh</font></b></a> | <a href="#" onclick="initPuzzle();return false"><b>H&#236;nh kh&#225;c</b></a> | 
<form style="display:inline">
 C&#7897;t/D&#242;ng: <input type="text" value="4" onblur="var no = this.value.replace(/[^\d]/g,'');if(no/1<3){ this.value = '3';no=3; };cols=no" maxlength="1" size="2"> x 
<input type="text" value="4" onblur="var no = this.value.replace(/[^\d]/g,'');if(no/1<2){ this.value = '2';no=2; };rows=no" maxlength="1" size="2"></form>
</div>
<div align="center" style="padding-top:10px">
<div id="puzzle_container">
<b>&#272;ang kh&#7903;i t&#7841;o tr&#242; ch&#417;i - Xui vui l&#242;ng &#273;&#7907;i x&#237;u ...</b>
</div>
<div id="messageDiv"></div>
<p><i>X&#225;o tr&#7897;n h&#236;nh sau &#273;&#243; Click v&#224;o h&#236;nh &#273;&#7875; di chuy&#7875;n.</i></p>
</div>

</td>
</tr>
</table>
EOF;
}

// end class

}

?>