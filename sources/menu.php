<?php
$ndk1 = new sMenu();
class sMenu{
var $output="";
var $baseurl="";
var $html=array();
var $lang=array();
//=========================
function Create_Menu() {
global $NDK,$func,$DB,$input;

// Khai bao

$Menu[] = array (
	"name" => "Truyen Online",
	"sub" => array(
		0 => array(
			"name" => "Trang ch&#237;nh",
			"link" => "index.php",
		),
		1 => array(
			"name" => "T&#225;c gi&#7843;",
			"link" => "?cmd=act:TacGia",
		),
/*
		1 => array(
			"name" => "L&#432;u b&#250;t",
			"link" => "?cmd=act:LuuBut",
		),
*/
	),
);

$Menu[] = array (
	"name" => "Truy&#7879;n",
	"sub" => array(
		0 => array(
			"name" => "Truy&#7879;n D&#224;i",
			"link" => "?cmd=act:TruyenDai",
		),
		1 => array(
			"name" => "Truy&#7879;n Ki&#7871;m Hi&#7879;p",
			"link" => "?cmd=act:TruyenKiemHiep",
		),
/*		2 => array(
			"name" => "Truy&#7879;n ng&#7855;n",
			"link" => "?cmd=act:TruyenNgan",
		),
		3 => array(
			"name" => "Truy&#7879;n c&#432;&#7901;i",
			"link" => "?cmd=act:TruyenCuoi",
		),
		4 => array(
			"name" => "Truy&#7879;n tranh",
			"link" => "?cmd=act:TruyenTranh",
		),

		5 => array(
			"name" => "B&#225;o &#225;o tr&#7855;ng",
			"link" => "?cmd=act:BaoAoTrang",
		),
		6 => array(
			"name" => "B&#225;o m&#7921;c t&#237;m",
			"link" => "?cmd=act:BaoMucTim",
		),
*/
	),
);
$i=2;
$qr = $DB->query("SELECT * FROM category ORDER BY corder ASC,cid ASC");
while ($cats=$DB->fetch_row($qr)) {
	$ndksub=array(
		"name" => $cats['cname'],
		"link" => "?cmd=act:Truyen|sub:{$cats['pact']}",
	);	
	$Menu[1]['sub'][$i]=$ndksub;
	$i++;
}

/*
$Menu[] = array (
	"name" => "Gi&#7843;i tr&#237;",
	"sub" => array(
		0 => array(
			"name" => "Nh&#7841;c h&#7885;c tr&#242;",
			"link" => "?cmd=act:Nhac",
		),
		1 => array(
			"name" => "&#272;i&#7879;n thi&#7879;p",
			"link" => "?cmd=act:DienThiep",
		),
		2 => array(
			"name" => "Di&#7877;n &#273;&#224;n",
			"link" => "../forum",
		),
		3 => array(
			"name" => "Tr&#242; ch&#417;i",
			"link" => "?cmd=act:TroChoi",
		),
	),
);
*/
$Menu[] = array (
	"name" => "Giai Tri",
	"sub" => array(
/*
		0 => array(
			"name" => "H&#7897;p th&#432; Email",
			"link" => "http://tretoday.zzn.com/",
		),
*/
		0 => array(
			"name" => "L&#7883;ch Vi&#7879;t Nam",
			"link" => "?cmd=act:Lich",
		),
		1 => array(
			"name" => "X&#7871;p B&#224;i",
			"link" => "?cmd=act:XepBai",
		),
		2 => array(
			"name" => "X&#7871;p H&#236;nh",
			"link" => "?cmd=act:XepHinh",
		),
	),
);

// End
$out="<table width=\"100%\" border=\"0\" cellpadding=\"1\" cellspacing=\"1\">";

for ($i=0;$i<count($Menu);$i++) {
	$mainmenu = $Menu[$i];
	$out.="<tr><td align=center width=\"100%\" class='mainmenu'>{$mainmenu['name']}</td></tr>";
	$out.="<tr><td style=\"padding-left:10px\">";
	// Sub
	$out.="<table width=\"100%\" border=\"0\" cellpadding=\"1\" cellspacing=\"1\" style=\"border:1px solid #072C4C\" bgcolor=\"#EEEEEE\">";
	for ($j=0;$j<count($mainmenu['sub']);$j++) {
		$submenu=$mainmenu['sub'][$j];
		$out.="<tr><td align=left width=\"100%\"><a href=\"{$submenu['link']}\" class='submenu'>&raquo; {$submenu['name']}</a></td></tr>";
	}
	$out.="</table>";
	// End
	$out.="</td></tr>";
}

$out.="</table>";
return $out;
}
//=========================
// end class
}
?>