<?php
class sglobal{

function top_header($data){
global $NDK,$input;
return<<<EOF
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>:: {$NDK->conf['webname']} ::{$data['tittle']}</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="SHORTCUT ICON" href="favicon.ico" type="image/x-icon" />
<link rel="icon" href="animated_favicon1.gif" type="image/gif" >
<link href="skins/style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="skins/overlib_mini.js"></script>
<script language="javascript" type="text/javascript" src="js/NDK.js"></script>
<script language=javascript src="bogoviet.js" type=text/javascript></script>
</head>
<body>
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000; filter: alpha(opacity=85);"></div>
<table width="780" border="0" align="center" cellpadding="10" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="100%"><table width="780" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td colspan="2" background="images/banner.jpg" height="110"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="300">&nbsp;</td>
        <td><a href="http://giabinh.tk" target="_blank"><img src="http://giabinh.tk/media/2014-11-19/11506d97c8d473c4de0afa11deca19df.jpg" border="0" /></a></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="2" bgcolor="#FFFFFF" height="5"><img src="images/spacer.gif" height="10" /></td>
  </tr>
  <tr>
    <td rowspan="2" background="images/bg_side.gif" style="background-repeat:repeat-y" valign="top">
	<div>{$data['menu']}</div>
	<div style="padding-left:10px; padding-top:10px"><table width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#01172C">

  <tr>

	<td align="center" bgcolor="#01172C" height="25" style="color:#FFFFFF; font-weight:bold">&nbsp;Quang Cao </td>

  </tr>

  <tr>

  	<td width="100%" align=left bgcolor="#FFFFFF">

	<div style="padding:2px"> &raquo; <a href="http://thoitrangvani.vn" target="_blank">thoi trang</a></div>

	<div style="padding:2px"> &raquo; <a href="http://cooffeetree.vn" target="_blank">caffee</a></div>

	<div style="padding:2px"> &raquo; <a href="http://giabinh.t" target="_blank">linh kien vi tinh</a></div>

		<div style="padding:2px"> &raquo; <a href="http://nguyenlieuphache.com.vn" target="_blank">nguyen lieu pha che</a></div>


	</td>

  </tr>

</table></div>
	<div><img src="images/spacer.gif" width="160" height="10"></div>
	</td>
    <td width="80%" style="padding-left:10px; padding-right:0px" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3" style="border:1px solid #89A2B8" bgcolor="#F3F9FF">
      <tr>
        <td>
EOF;
}

function footer($data){
global $NDK;
return<<<EOF
<div><img src="images/spacer.gif" width="160" height="10"></div>
</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center" valign="bottom" style="padding:5px"> Copyrights &copy; 2015 - Binh Nguyen </td>
  </tr>
</table></td>
</tr>
</table>
</body>
</html>
EOF;
}

function Main_Table($data){
global $NDK;
return<<<EOF
{$data['main']}
EOF;
}

function nav($data,$w="100%"){
global $NDK;
return<<<EOF
    <table width="{$w}" border="0" align="center" style="border:1px #2D6598 solid" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
      <tr>
        <td background="{$NDK->imageroot}navtop1_bg.gif">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="15" height="21" align="left"><img src="{$NDK->imageroot}navtop1_bullet.gif" width="15" height="21" /></td>
            <td class="maintittle">{$data['tittle']}</td>
          </tr>
        </table>
        </td>
      </tr>
      <tr>
        <td background="{$NDK->imageroot}navtop2_bg.gif">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="100" height="6" align="left"><img src="{$NDK->imageroot}navtop2_left.gif" width="100" height="6" /></td>
            <td><img src="{$NDK->imageroot}navtop2_bg.gif" width="1" height="6" /></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td align="center" style="padding:2px 2px 2px 2px">{$data['nd']}</td>
      </tr>
    </table>
EOF;
}

function nav_top($text){
global $NDK;
return<<<EOF
<div style="padding:3px 3px 3px 3px;border:1px #595A5A solid;background-color:#FFFFFF;">&nbsp;&raquo; {$text}</div>
<hr>
EOF;
}

function Table_Top($title,$col){
global $NDK;
return<<<EOF
<table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td background="images/ndk2_nav1bg.gif"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="10"><img src="images/ndk2_nav2.gif" width="10" height="27" /></td>
                    <td class="ndk2_navtitle">{$title}</td>
                    <td width="10"><img src="images/ndk2_nav2.gif" width="10" height="27" /></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td class="ndk2_navbox"><table width="100%" border="0" cellspacing="0" cellpadding="0">
EOF;
}

function Table_Bot($extra,$col,$style=""){
global $NDK;
return<<<EOF
      <tr>
        <td colspan="{$col}" style="{$style}" >{$extra}</td>
      </tr>
</table>
</td>
</tr>
</table>
EOF;
}


function direct($url,$mess){
global $NDK;
return<<<EOF
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>{$mess}</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv='refresh' content='3; url={$url}' />
<link href="{$NDK->skindir}style.css" rel="stylesheet" type="text/css">
</head>

<body>
<br><br><br><br><br><br>
    <table width="60%" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td style="padding:3px 3px 3px 3px" bgcolor="#427CB0" class="copyright" height="24" ><font size="3"><b>{$mess}</b></font></td>
          </tr>
        </table>
        </td>
      </tr>
      <tr>
        <td align="center" style="padding:3px 3px 3px 3px" bgcolor="#FFFFFF">Vui l&#242;ng ch&#7901; chuy&#7875;n trang...<br><br>
          (<a href='{$url}'>Ho&#7863;c nh&#7845;n v&#224;o &#273;&#226;y n&#7871;u b&#7841;n kh&#244;ng mu&#7889;n &#273;&#7907;i l&#226;u</a>)</td>
      </tr>
      <tr>
        <td align="center" style="padding:3px 3px 3px 3px" bgcolor="#427CB0" class="copyright"><b>B&#7843;n quy&#7873;n thu&#7897;c v&#7873; Binh Nguyen &copy 2015</b></td>
      </tr>
    </table>
</body>
</html>
EOF;
}

function hehe(){
global $NDK;
return<<<EOF

EOF;
}
//end class
}
?>
