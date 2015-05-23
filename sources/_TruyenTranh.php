<?php
$ndk = new sMain();
class sMain{
var $output="";
var $baseurl="";
var $html=array();
var $cat="TruyenTranh";
var $name="Truy&#7879;n Tranh";
// Start func
function sMain(){
global $DB,$func,$NDK,$input;
	$data['nav']=$data['out']="";
	if (empty($input['id'])) {
		$data['nav']=$this->name;
		$data['out']=$this->Get_List();
	} else {
		$data['nav']="<a href=\"?cmd=act:{$this->cat}\">{$this->name}</a> &raquo; ".$func->Get_Title($input['id']);
		$data['out']=$this->Get_Content($input['id']);
	}

	$this->output .= $this->skinmain($data);
	$NDK->output .= $this->output;
}
//=======================================
function Get_List(){
global $DB,$func,$NDK,$input;
	$alpha=$input['alpha'];
	$p=intval($input['p']);
$where=" WHERE cat='{$this->cat}' AND active ";
if (empty($alpha)) {	
	$order=" id DESC ";
} else {
	if ($alpha=="0-9") {
		$where.=" AND ( ten LIKE '0%' ";
		for ($i=1;$i<10;$i++) {
			$where.=" OR ten LIKE '{$i}%' ";
		}
		$where.=") ";
	} else $where.=" AND ten LIKE '{$alpha}%' ";
	$order=" ten ASC ";
	$extralink="|alpha:{$alpha}";
}

$qrtotal=$DB->query("SELECT id FROM contents {$where}");
$totals_pages = $DB->num_rows($qrtotal);
$n=(!empty($NDK->conf['numperpage'])) ? $NDK->conf['numperpage']:30;
$num_pages = ceil($totals_pages/$n);
if ($p > $num_pages) $p=$num_pages;
if ($p < 1 ) $p=1;
$start = ($p-1) * $n ; 

$nav = "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
<tr height=\"50\">
<td align=\"center\">
<table width=\"91%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
<tr height=\"50\">
<td align=\"left\"><br><div align=left>&nbsp;<span class=\"pagelink\">{$num_pages} Page(s) :</span>&nbsp;";
for ($i=1; $i<$num_pages+1; $i++ ) {
    if ($i==$p) $nav.="<span class=\"pagecur\">{$i}</span>&nbsp";
    else $nav.="<a href='?cmd=act:{$this->cat}{$extralink}|p:{$i}'><span class=\"pagelink\">{$i}</span></a>&nbsp";
} 
$nav .= "</div></td>
</tr>
</table>
</td>
</tr>
</table>";

$table['name']="";
$table['title'] = array (
	'stt' => "#|5%|center",
    'ten' => "T&#7921;a &#273;&#7873;|50%",
    'tacgia' => "T&#225;c gi&#7843;|30%",
    'xem' => "L&#7847;n &#273;&#7885;c|15%|center",
);
$i=1;
$query = $DB->query("SELECT * FROM contents {$where} ORDER BY {$order} LIMIT $start,$n");
while ($row=$DB->fetch_row($query)) {
	$row['stt']=$start+$i;
	$row['ten']="<a href=\"?cmd=act:{$this->cat}|id:{$row['id']}\"><b>{$row['ten']}</b></a>";
    $list[]=$row;
	$i++;
}
$table['row'] = $list;
$textout.= $func->ShowAlpha($this->cat,$input['alpha']);
$textout.= $func->ShowTable($table);

return $textout;
}
//========================================
function Get_Content($id=0){
global $DB,$func,$NDK,$input;
$textout="";
$textout.= $func->ShowAlpha($this->cat,$input['alpha']);

$where=" WHERE cat='{$this->cat}' AND active ";
// Count
	$qr = $DB->query("UPDATE contents SET xem=xem+1 {$where} AND id='{$id}'");
// End
$query = $DB->query("SELECT * FROM contents {$where} AND id='{$id}'");
if ($row=$DB->fetch_row($query)) {

	$textout.=$this->html_content($row);
} else {
	$textout.="<center>Kh&#244;ng t&#236;m th&#7845;y b&#224;i vi&#7871;t n&#224;y</center>";
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
//================================
function html_content($data){
global $NDK,$input;
$data['ngaydang']=date("d/m/Y",$data['ngaydang']);
return<<<EOF
<table width="95%" align=center bgcolor="#DDDDDD" border="0" cellspacing="1" cellpadding="2">
<tr>
<td width="100%" align=center class="content_title">{$data['ten']}</td>
</tr>
<tr>
<td width="100%" align=left class="content_nd">
{$data['noidung']}
<div align=right style="padding-right:10px"><b>{$data['tacgia']}</b></div>
<div align=left><font color="#666666">&#272;&#259;ng ng&#224;y: {$data['ngaydang']}</font></div>
</td>
</tr>
<tr>
<td width="100%" align=left  class="content_nd">L&#7847;n xem: <b>{$data['xem']}</b></td>
</tr>
<tr>
<td width="100%" align=left  class="content_nd"><table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="20%" align=center style="padding-left:3px;" bgcolor="#CCCCCC"><a href="javascript:history.go(-1)"><b>&laquo; Quay l&#7841;i</b></a></td>
	<td width="80%" align=right style="padding-right:10px;"><a href="javascript:showsendfriend({$data['id']},'{$this->cat}');"><img src="images/but_mail.gif" border=0 width=22 alt="Gi&#7899;i thi&#7879;u b&#7841;n b&#232;"></a>&nbsp;&nbsp;&nbsp;<a href="javascript:showprint({$data['id']},'{$this->cat}');"><img src="images/but_print.gif" border=0 width=22 alt="In b&#224;i vi&#7871;t n&#224;y"></a></td>
</tr>
</table>
</td>
</tr>
</table>
EOF;
}
// end class

}

?>