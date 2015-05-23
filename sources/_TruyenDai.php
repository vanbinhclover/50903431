<?php
$ndk = new sMain();
class sMain{
var $output="";
var $baseurl="";
var $html=array();
var $cat="TruyenDai";
var $name="Truy&#7879;n D&#224;i";
// Start func
function sMain(){
global $DB,$func,$NDK,$input;
	$data['nav']=$data['out']="";
if ((!empty($input['chuong']))&&(!empty($input['id']))) {
	$this->output .= $this->Load_Chuong($input['id'],$input['chuong']);
} else {
	if (empty($input['id'])) {
		$data['nav']=$this->name;
		$data['out']=$this->Get_List();
	} else {
		$data['nav']="<a href=\"?cmd=act:{$this->cat}\">{$this->name}</a> &raquo; ".$func->Get_Title($input['id'],"ten","truyendai","tid");
		$data['out']=$this->Get_Content($input['id']);
	}

	$this->output .= $this->skinmain($data);
}
	$NDK->output .= $this->output;
}
//=======================================
function Get_List(){
global $DB,$func,$NDK,$input;
	$type=$input['type'];
	$alpha=$input['alpha'];
	$p=intval($input['p']);
$where=" WHERE tid!=0  AND (cat='' OR cat is NULL)";
$order=" ten ASC ";
$tenlist="T&#7845;t c&#7843; truy&#7879;n";
if ($type!="") {	
	$where.=" AND status='{$type}' ";
	$order=" ten ASC ";
	$extralink="|type:{$type}";
	if ($type==1) $tenlist="Truy&#7879;n &#273;&#259;ng xong";
	else $tenlist="Truy&#7879;n &#273;ang &#273;&#259;ng";
}

if (!empty($alpha)) {	
	if ($alpha=="0-9") {
		$where.=" AND ( ten LIKE '0%' ";
		for ($i=1;$i<10;$i++) {
			$where.=" OR ten LIKE '{$i}%' ";
		}
		$where.=") ";
	} else $where.=" AND ten LIKE '{$alpha}%' ";
	$order=" ten ASC ";
	$extralink.="|alpha:{$alpha}";
	$tenlist.=" - v&#7847;n {$alpha}";
}

$qrtotal=$DB->query("SELECT tid FROM truyendai {$where}");
$totals_pages = $DB->num_rows($qrtotal);
$n=(!empty($NDK->conf['numperpage'])) ? $NDK->conf['numperpage']:30;
$num_pages = ceil($totals_pages/$n);
if ($p > $num_pages) $p=$num_pages;
if ($p < 1 ) $p=1;
$start = ($p-1) * $n ; 

$nav=$func->paginate($totals_pages, $n);

$table['name']=$tenlist ."&nbsp; [ <font color='#1111FF'>".$totals_pages."</font> truy&#7879;n ]";
$table['title'] = array (
	'stt' => "#|5%|center",
    'ten' => "T&#7921;a &#273;&#7873;|50%",
    'tacgia' => "T&#225;c gi&#7843;|30%",
    'landoc' => "L&#7847;n &#273;&#7885;c|15%|center",
);
$i=1;
$query = $DB->query("SELECT * FROM truyendai {$where} ORDER BY {$order} LIMIT $start,$n");
while ($row=$DB->fetch_row($query)) {
	$row['stt']=$start+$i;
	$row['ten']="<a href=\"?cmd=act:{$this->cat}|id:{$row['tid']}\"><b>{$row['ten']}</b></a>";
	
	$qr=$DB->query("SELECT * FROM tacgia WHERE tg_id='{$row['tacgia']}'");
	if ($tacgia=$DB->fetch_row($qr)) $row['tacgia']="<a href=\"?cmd=act:TacGia|id:{$tacgia['tg_id']}\">".$tacgia['tg_ten']."</a>";
	else $row['tacgia']="&nbsp;";
	
    $list[]=$row;
	$i++;
}
$table['row'] = $list;
$textout.= $func->ShowAlpha($this->cat,$input['alpha']);
$textout.= $func->ShowListDai();
$textout.= $func->ShowTable($table);

return $textout.$nav;
}
//========================================
function Get_Content($id=0){
global $DB,$func,$NDK,$input;
$textout="";
$textout.= $func->ShowAlpha($this->cat,$input['alpha']);

$query = $DB->query("SELECT * FROM truyendai WHERE tid='{$id}'");
if ($truyen=$DB->fetch_row($query)) {

	$qr=$DB->query("SELECT * FROM tacgia WHERE tg_id='{$truyen['tacgia']}'");
	if ($tacgia=$DB->fetch_row($qr)) {
		$listtg="<table width=\"80%\" bgcolor='#DDDDDD' border=\"0\" style=\"border:1px solid #999999\" cellpadding=\"1\" cellspacing=\"0\" align=center>";
		$listtg.="<tr><td width='100%' bgcolor='#D6D6D6' align=center><b>C&#249;ng t&#225;c gi&#7843;</b></td></tr>";
		$k=0;
		$qr=$DB->query("SELECT * FROM truyendai WHERE tacgia='{$truyen['tacgia']}' AND (cat='' OR cat is NULL) ORDER BY ten ASC");
		while ($tr=$DB->fetch_row($qr)) {
			$k++;
			$listtg.="<tr><td width='100%' bgcolor='#FFFFFF' align=left><a href=\"?cmd=act:{$this->cat}|id:{$tr['tid']}\">&nbsp;&rsaquo; {$tr['ten']}</a></td></tr>";
		}
		if ($k>0) $listtg.="<tr><td width='100%' bgcolor='#FFFFFF' align=right><a href=\"?cmd=act:TacGia|id:{$tacgia['tg_id']}\">&nbsp;&raquo; Xem t&#7845;t c&#7843;</a>&nbsp;</td></tr>";
		$listtg.="</table>";
		if ($k>0) $truyen['cungtacgia']=$listtg;
		$truyen['tacgia']="T&#225;c gi&#7843;: <b><a href=\"?cmd=act:TacGia|id:{$tacgia['tg_id']}\">".$tacgia['tg_ten']."</a></b>";
	} else $truyen['tacgia']="&nbsp;";

	if ($truyen['status']==1) $truyen['status']="&#272;&#259;ng xong";
	else $truyen['status']="C&#242;n ti&#7871;p";

	$truyen['list']="<table width=\"100%\" border=\"0\" cellpadding=\"3\" cellspacing=\"1\">";
	$qr=$DB->query("SELECT * FROM truyendai_chuong WHERE tid='{$id}' ORDER BY chuong ASC");
	$i=0; $num=2; $width=100/$num."%";
	while ($chuong=$DB->fetch_row($qr)) {
		if ($i%$num==0) $truyen['list'].="<tr>";
		$truyen['list'].="<td width='{$width}'><a href=\"#{$id}_Chuong_{$chuong['chuong']}\" onClick=\"Xem_Chuong('{$id}','{$chuong['chuong']}');\">&raquo; Ch&#432;&#417;ng {$chuong['chuong']}</a></td>";
		$i++;
		if ($i%$num==0) $truyen['list'].="</tr>";
	}
if ($i>0) {
	if ($i%$num<($num-1)) {
        for ($j=0;$j<$num-($i%$num);$j++) $truyen['list'].="<td>&nbsp;</td>";
        $truyen['list'].="</tr>";     
    }
} else $truyen['list'].="<tr><td align=center>Ch&#432;a c&#243; ch&#432;&#417;ng</td></tr>";
	$truyen['list'].="</table>";
	$textout.=$this->html_truyen($truyen);
} else {
	$textout.="<center>Kh&#244;ng t&#236;m th&#7845;y b&#224;i vi&#7871;t n&#224;y</center>";
}

return $textout;
}
//========================================
function Load_Chuong($id=0,$chuong=""){
global $DB,$func,$NDK,$input;
$textout="";
$query = $DB->query("SELECT c.*,t.tacgia,t.ten FROM truyendai_chuong c, truyendai t WHERE c.tid='{$id}' AND c.chuong='{$chuong}' AND t.tid=c.tid");
if ($row=$DB->fetch_row($query)) {

// Count
	$qr = $DB->query("UPDATE truyendai SET landoc=landoc+1 WHERE tid='{$id}'");
// End
	$qr=$DB->query("SELECT tg_ten FROM tacgia WHERE tg_id='{$row['tacgia']}'");
	if ($tacgia=$DB->fetch_row($qr)) {
		$row['tacgia']="T&#225;c gi&#7843;: <b>".$tacgia['tg_ten']."</b>";
	}
	$row['ten']=$row['ten']." - Ch&#432;&#417;ng {$chuong}";
// back next	
	$qr=$DB->query("SELECT chuong FROM truyendai_chuong WHERE tid='{$id}' ORDER BY chuong ASC");
	$chuongarr=array();
	while ($chuonglist=$DB->fetch_row($qr)) {
		$chuongarr[]=$chuonglist['chuong'];
	}
	$i=0; $notfound=1;
	while (($i<count($chuongarr))&&($notfound)) {
		if ($chuongarr[$i]==$chuong) {
			$notfound=0;
			if (!empty($chuongarr[$i-1])) {
				$ch=$chuongarr[$i-1];
				$row['butleft']="<a href=\"#{$id}_Chuong_{$ch}\" onClick=\"Xem_Chuong('{$id}','{$ch}');\">Ch&#432;&#417;ng {$ch} &laquo;</a>";
			}
			if (!empty($chuongarr[$i+1])) {
				$ch=$chuongarr[$i+1];
				$row['butright']="<a href=\"#{$id}_Chuong_{$ch}\" onClick=\"Xem_Chuong('{$id}','{$ch}');\">&raquo; Ch&#432;&#417;ng {$ch}</a>";
			}
		}
		$i++;
	}
// end
	$textout.=$this->html_content($row);
} else {
	$textout.="<center>Kh&#244;ng t&#236;m th&#7845;y ch&#432;&#417;ng n&#224;y</center>";
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
<script>getVarChuong();</script>
EOF;
}
//================================
function html_truyen($data){
global $NDK,$input;
return<<<EOF
<div id="noidung_chuong"></div>
<table width="95%" align=center bgcolor="#DDDDDD" border="0" cellspacing="1" cellpadding="2">
<tr>
<td width="100%" colspan=2 align=center class="content_title">{$data['ten']}</td>
</tr>
<tr>
<td width="30%" align=left class="content_nd" valign=top>
{$data['list']}
<div align=center>[ <b>{$data['status']}</b> ]</div>
</td>
<td width="70%" align=left class="content_nd" valign=top>
{$data['gioithieu']}
<div>{$data['tacgia']}</div>
<div align=center>{$data['cungtacgia']}</div>
</td>
</tr>
<tr>
<td width="100%" colspan=2 align=left  class="content_nd"><table width="100%" border="0" cellspacing="0" cellpadding="0">
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
<div align=center>{$data['tacgia']}</div>
{$data['noidung']}
<div align=left><font color="#666666">&#272;&#259;ng ng&#224;y: {$data['ngaydang']}</font></div>
</td>
</tr>
<tr>
<td width="100%" align=left  class="content_nd"><table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="33%" align=left style="padding-left:5px;"><b>{$data['butleft']}</b>&nbsp;</td>
	<td width="33%" align=center ><a href="javascript:showprint('{$data['tid']}_{$data['id']}','{$this->cat}');"><img src="images/but_print.gif" border=0 width=22 alt="In b&#224;i vi&#7871;t n&#224;y"></a></td>
	<td width="33%" align=right style="padding-right:5px;">&nbsp;<b>{$data['butright']}</b></td>
</tr>
</table>
</td>
</tr>
</table>
<br>
EOF;
}
// end class

}

?>