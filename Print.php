<?
require_once("_config.php");
require_once("libs/_mysql.php"); 
$DB = new DB;
$DB->connect();
$query = $DB->query("SELECT * FROM setting");
while ($set=$DB->fetch_row($query)) {
    $conf[$set['s_key']] = $set['s_value'];
}
require_once("libs/_functions.php");
$func=new func();

if ( (isset($_GET['cat'])) && (!empty($_GET['cat'])) ) $cat=$func->clean_value($_GET['cat']); else $cat='';

if ($cat=='TruyenDai') {
	$var=$_GET['id'];
	$arr=explode("_",$var);
	$tid=intval($arr[0]);
	$id=intval($arr[1]);
} else {
	if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) $id=$func->clean_value($_GET['id']); else $id=0;
}

if (($id!=0)&&(!empty($cat))) {
	$show=0;
 if ($cat=='TruyenDai') {
 	$img_arr = $DB->query("SELECT c.*,t.tacgia,t.ten FROM truyendai_chuong c, truyendai t WHERE c.tid='{$tid}' AND c.id='{$id}' AND t.tid=c.tid");
	if ($pro=$DB->fetch_row($img_arr)) {
		$ten=$pro['ten']." - Ch&#432;&#417;ng ".$pro['chuong'];
		$qr=$DB->query("SELECT tg_ten FROM tacgia WHERE tg_id='{$pro['tacgia']}'");
		if ($tacgia=$DB->fetch_row($qr)) {
			$pro['tacgia']="T&#225;c gi&#7843;: <b>".$tacgia['tg_ten']."</b>";
		}
		$show=1;		
		$extra_js="onLoad=\"window.print();\"";
	} else {
		$show=0;
		$mess="Kh&#244;ng t&#236;m th&#7845;y b&#224;i vi&#7871;t";
	}
 } else {	
	$img_arr = $DB->query("SELECT * FROM contents WHERE id='{$id}' AND cat='{$cat}'");
	if ($pro=$DB->fetch_row($img_arr)) {
		$ten=$pro['ten'];
		$show=1;		
		$extra_js="onLoad=\"window.print();\"";
	} else {
		$show=0;
		$mess="Kh&#244;ng t&#236;m th&#7845;y b&#224;i vi&#7871;t";
	}
 }
} else {
	$show=0;
	$mess="Kh&#244;ng t&#236;m th&#7845;y b&#224;i vi&#7871;t";
}
?>
<html>
<head>
<title>Print</title>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
<style type="text/css">
body,td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
}
.pagelink {
	background: #FFFFFF;
	border: 1px solid #999;
	padding: 1px 5px 1px 5px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
}
.pagelink a:link,
.pagelink a:visited,
.pagelink a:active{
	color: #000000 !important;
	text-decoration: none;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
}
.pagecur {
    background: #BDE0FE;
    border: 1px solid #294B79;
    padding: 1px 5px 1px 5px;
    font-weight: bold;
    color: #003300;
}
</style>
</head>
<body bgcolor="#FFFFFF" <?=$extra_js?> >
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" valign=top>
  <tr>
    <td valign=top>
<? if ($show) { ?>	
<table width="90%" align="center" bgcolor="#999999" border="0" cellspacing="1" cellpadding="3">
          <tr>
            <td bgcolor="#888888" height="25"><font color="#FFFFFF"><b>&raquo; <?=$ten?></b></font></td>
          </tr>
		  <tr>
            <td bgcolor="#FFFFFF" height="50">
				<?=$pro['noidung']?>
				<div align="right"><b><?=$pro['tacgia']?></b></div>
			</td>
          </tr>
     </table>
<? } else { ?>
<table width="90%" align="center" bgcolor="#999999" border="0" cellspacing="1" cellpadding="3">
          <tr>
            <td bgcolor="#888888" height="25"><font color="#FFFFFF"><b>&raquo; <?=$ten?></b></font></td>
          </tr>
		  <tr>
            <td bgcolor="#FFFFFF" height="50"><?=$mess?></td>
          </tr>
     </table>
<? } ?>
      <div align="center"><font color="#990000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Copyrights &copy; 2015 - Binh Nguyen - giabinh.tk</font></div>
	</td>
  </tr>
</table>
</body>
<html>