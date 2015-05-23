<?php
$ndk = new sMain();
class sMain{
var $output="";
var $baseurl="";
var $html=array();
var $name="Trang ch&#237;nh";
// Start func
function sMain(){
global $DB,$func,$NDK,$input;
	$data['nav']=$data['out']="";
	$data['nav']=$this->name;
	$data['out']=$this->Get_List_Dai(15);
	$data['out'].=$this->Get_List(5);
	$this->output .= $this->skinmain($data);
	$NDK->output .= $this->output;
}
//=======================================
function Get_List_Dai($n=10){
global $DB,$func,$NDK,$input;

	$table['name']="Truy&#7879;n D&#224;i";
	$table['title'] = array (
		'tinhtrang' => "Status|10%|center",
	    'ten' => "T&#7921;a &#273;&#7873;|55%",
	    'tacgia' => "T&#225;c gi&#7843;|25%",
	    'landoc' => "L&#7847;n &#273;&#7885;c|15%|center",
	);
	$i=1;
	$query = $DB->query("SELECT * FROM truyendai WHERE tid!=0 AND (cat='' OR cat is NULL) ORDER BY tid DESC LIMIT 0,$n");
	while ($row=$DB->fetch_row($query)) {
		$row['stt']=$start+$i;
		$row['ten']="&nbsp;<a href=\"?cmd=act:TruyenDai|id:{$row['tid']}\"><b>{$row['ten']}</b></a>";
		$row['ngaydang']=date("d/m/Y",$row['ngaydang']);
		if ($row['status']==1) $row['tinhtrang']="<b>K&#7871;t th&#250;c</b>";
		else $row['tinhtrang']="C&#242;n ti&#7871;p";
	
		$qr=$DB->query("SELECT * FROM tacgia WHERE tg_id='{$row['tacgia']}'");
		if ($tacgia=$DB->fetch_row($qr)) $row['tacgia']="<a href=\"?cmd=act:TacGia|id:{$tacgia['tg_id']}\">".$tacgia['tg_ten']."</a>";
		else $row['tacgia']="&nbsp;";
	
	    $list[]=$row;
		$i++;
	}
	$table['row'] = $list;
	$table['extra']="&nbsp;<a href=\"?cmd=act:TruyenDai\">&raquo; Xem t&#7845;t c&#7843;</a>";
	$table['extra_style']="background-color:#CCCCCC";
	$textout.= $func->ShowTable($table)."<br>";

return $textout;
}
//=======================================
function Get_List($n=5){
global $DB,$func,$NDK,$input;

$arr=array(
"TrangTho" => "Trang Th&#417;",
"TruyenNgan" => "Truy&#7879;n Ng&#7855;n",
"TruyenCuoi" => "Truy&#7879;n C&#432;&#7901;i",
"SachHocLamNguoi" => "S&#225;ch H&#7885;c L&#224;m Ng&#432;&#7901;i"
);

while( list($nowcat,$nowname) = each($arr) ) {

	$list=array();
	
	$where=" WHERE cat='{$nowcat}' AND active ";

	$table['name']=$nowname;
	$table['title'] = array (
//		'stt' => "#|5%|center",
	    'ten' => "T&#7921;a &#273;&#7873;|55%",
	    'tacgia' => "T&#225;c gi&#7843;|30%",
	    'xem' => "L&#7847;n &#273;&#7885;c|15%|center",
	);
	$i=1;
	$query = $DB->query("SELECT * FROM contents {$where} ORDER BY id DESC LIMIT 0,$n");
	while ($row=$DB->fetch_row($query)) {
		$row['stt']=$start+$i;
		$row['ten']="&nbsp;<a href=\"?cmd=act:Truyen|sub:{$nowcat}|id:{$row['id']}\"><b>{$row['ten']}</b></a>";
	    $list[]=$row;
		$i++;
	}
	$table['row'] = $list;
	$table['extra']="&nbsp;<a href=\"?cmd=act:Truyen|sub:{$nowcat}\">&raquo; Xem t&#7845;t c&#7843;</a>";
	$table['extra_style']="background-color:#CCCCCC";
	$textout.= $func->ShowTable($table)."<br>";
	
}
return $textout;
}
//========================================
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
<td width="100%">{$data['out']}</td>
</tr>
</table>
EOF;
}

// end class

}

?>