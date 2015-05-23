<?php
$ndk = new sMain();
class sMain{
var $output="";
var $baseurl="";
var $html=array();
// Start func
function sMain(){
global $DB,$func,$NDK,$input;
	$data['nav']="L&#7883;ch Vi&#7879;t Nam";
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
<div align=center>
<applet 
  CODEBASE =" ."
  width="467" 
  height="350" 
  code="VNCalendar.class"
  HEIGHT   = 350
  HSPACE   = 0
  VSPACE   = 0
  ALIGN    = middle
>
  Your browser does not support Java applet
</applet>
</div>
</td>
</tr>
</table>
EOF;
}

// end class

}

?>