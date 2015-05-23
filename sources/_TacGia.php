<?php
$ndk = new sMain();
class sMain{
var $output="";
var $baseurl="";
var $html=array();
var $cat="TacGia";
var $name="T&#225;c Gi&#7843;";
// Start func
function sMain(){
global $DB,$func,$NDK,$input;
	$data['nav']=$data['out']="";
	if (empty($input['id'])) {
		$data['nav']=$this->name;
		$data['out']=$this->Get_List();
	} else {
		
		$qr=$DB->query("SELECT * FROM tacgia WHERE tg_id='{$input['id']}'");
		if ($tacgia=$DB->fetch_row($qr)) {
			$ten=$tacgia['tg_ten'];
			
		$listtg="<table width=\"80%\" bgcolor='#DDDDDD' border=\"0\" style=\"border:1px solid #999999\" cellpadding=\"1\" cellspacing=\"0\" align=center>";
		$listtg.="<tr><td width='100%' bgcolor='#D6D6D6' align=center><b>T&#225;c ph&#7849;m</b></td></tr>";
		$k=0;
		$qr=$DB->query("SELECT * FROM truyendai WHERE tacgia='{$input['id']}' ORDER BY ten ASC");
		while ($tr=$DB->fetch_row($qr)) {
			$k++;
			$link_cat=(!empty($tr['cat'])) ? $tr['cat']:"TruyenDai";
			$listtg.="<tr><td width='100%' bgcolor='#FFFFFF' align=left><a href=\"?cmd=act:{$link_cat}|id:{$tr['tid']}\">&nbsp;&rsaquo; {$tr['ten']}</a></td></tr>";
		}
		$listtg.="</table>";
		if ($k>0) $tacgia['cungtacgia']=$listtg;
		
			$out=$this->html_content($tacgia);	
		} else {
			$ten="Kh&#244;ng t&#236;m th&#7845;y";
			$out="<center>Kh&#244;ng t&#236;m th&#7845;y t&#225;c gi&#7843; n&#224;y</center>";
		}
		$data['nav']="<a href=\"#\">{$this->name}</a> &raquo; ".$ten;
		$data['out']=$out;
	}

	$this->output .= $this->skinmain($data);
	$NDK->output .= $this->output;
}
//=======================================
function Get_List(){
global $DB,$func,$NDK,$input;
	$alpha=$input['alpha'];
	$p=intval($input['p']);
$where=" WHERE tg_id!=-1 ";
if (empty($alpha)) {	
	$order=" tg_ten ASC ";
} else {
	if ($alpha=="0-9") {
		$where.=" AND ( tg_ten LIKE '0%' ";
		for ($i=1;$i<10;$i++) {
			$where.=" OR tg_ten LIKE '{$i}%' ";
		}
		$where.=") ";
	} else $where.=" AND tg_ten LIKE '{$alpha}%' ";
	$order=" tg_ten ASC ";
	$extralink="|alpha:{$alpha}";
}

$qrtotal=$DB->query("SELECT tg_id FROM tacgia {$where}");
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
    'tg_ten' => "T&#234;n t&#225;c gi&#7843;|65%",
    'tacpham' => "T&#225;c ph&#7849;m|30%|center",
);
$i=1;
$query = $DB->query("SELECT * FROM tacgia {$where} ORDER BY {$order} LIMIT $start,$n");
while ($row=$DB->fetch_row($query)) {
	$row['stt']=$start+$i;
	$row['tg_ten']="<a href=\"?cmd=act:{$this->cat}|id:{$row['tg_id']}\"><b>{$row['tg_ten']}</b></a>";
	$qr=$DB->query("SELECT tid FROM truyendai WHERE tacgia='{$row['tg_id']}'");
	$row['tacpham'] = $DB->num_rows($qr);
	if (empty($row['tacpham'])) $row['tacpham']="Ch&#432;a c&#7853;p nh&#7853;t";
    $list[]=$row;
	$i++;
}
$table['row'] = $list;
$textout.= $func->ShowAlpha($this->cat,$input['alpha']);
$textout.= $func->ShowTable($table);

return $textout.$nav;
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
return<<<EOF
<table width="90%" align=center bgcolor="#DDDDDD" border="0" cellspacing="1" cellpadding="2">
<tr>
<td width="100%" align=center class="content_title">{$data['tg_ten']}</td>
</tr>
<tr>
<td width="100%" align=left class="content_nd">
{$data['tg_gioithieu']}
</td>
</tr>
<tr>
<td width="100%" align=center  class="content_nd">{$data['cungtacgia']}</td>
</tr>
<tr>
<td width="100%" align=left  class="content_nd"><table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="20%" align=center style="padding-left:3px;" bgcolor="#CCCCCC"><a href="javascript:history.go(-1)"><b>&laquo; Quay l&#7841;i</b></a></td>
	<td width="80%" align=right style="padding-right:10px;">&nbsp;</td>
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