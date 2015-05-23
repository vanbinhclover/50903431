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

if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) $id=$func->clean_value($_GET['id']); else $id=0;
if ( (isset($_GET['cat'])) && (!empty($_GET['cat'])) ) $cat=$func->clean_value($_GET['cat']); else $cat='';

if (($id!=0)&&(!empty($cat))) {
	$show=0;
	$img_arr = $DB->query("SELECT * FROM contents WHERE id='{$id}' AND cat='{$cat}'");
	if ($pro=$DB->fetch_row($img_arr)) {
		$ten=$pro['ten'];
		$linkpro=$conf['rooturl']."?cmd=act:Truyen|sub:{$cat}|id:{$id}";
		$message="Toi tim thay bai viet '{$ten}' va nghi ban co the thich no.";
		
		if ($_POST['dosend']) {
			$send['s_name']=$_POST['s_name'];
			$send['s_email']=$_POST['s_email'];
			$send['r_name']=$_POST['r_name'];
			$send['r_email']=$_POST['r_email'];
			$send['message']=$_POST['message'];
			$send['linkpro']=$linkpro;
			
			require_once('libs/_email.php');
			require_once('mail_template/tellfriend.tpl');		
	
			$emailmessage = email_temp($send);
		
			$semail = new emailer();
			$semail->html_email = 1;
			$semail->from      = $send['s_email'];
			$semail->subject = 'Message from: '.$send['s_name'];
			$semail->to      = $send['r_email'];
			$semail->message = $emailmessage;
			$send = $semail->send_mail();
			$show=0;
			$mess=$send;
		} else $show=1;
	} else {
		$show=0;
		$mess="Kh&#244;ng t&#236;m th&#7845;y b&#224;i vi&#7871;t";
	}
} else {
	$show=0;
	$mess="Kh&#244;ng t&#236;m th&#7845;y b&#224;i vi&#7871;t";
}
?>
<html>
<head>
<title>Tell A Friend</title>
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
<body bgcolor="#FFFFFF">
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" valign=top>
  <tr>
    <td valign=top>
<? if ($show) { ?>	
<script language="javascript">
function checkform(f) {
	if (f.s_name.value=='') {
		alert("Vui long nhap ten cua ban");
		f.s_name.focus();
		return false;
	}

	var email = f.s_email.value;
        if (email == ""){
            alert("Vui long nhap email cua ban");
            f.s_email.focus();
            return false;
        }
        err = '';
        if (email.indexOf('@') <= 0 || email.indexOf('@') == email.length -1){
            err = "Email khong hop le";       
        } else {
            var domain=email.substring(email.indexOf('@'),email.length);
            if (domain.indexOf('.') <= 0) {
                err = "Email khong hop le";
            }
        }
        if (err != '') { 
            alert(err); 
            f.s_email.focus();
            return false;
        }
		
	if (f.r_name.value=='') {
		alert("Vui long nhap ten nguoi nhan");
		f.r_name.focus();
		return false;
	}
		
	var email = f.r_email.value;
        if (email == ""){
            alert("Vui long nhap email nguoi nhan");
            f.r_email.focus();
            return false;
        }
        err = '';
        if (email.indexOf('@') <= 0 || email.indexOf('@') == email.length -1){
            err = "Email khong hop le";       
        } else {
            var domain=email.substring(email.indexOf('@'),email.length);
            if (domain.indexOf('.') <= 0) {
                err = "Email khong hop le";
            }
        }
        if (err != '') { 
            alert(err); 
            f.r_email.focus();
            return false;
        }
		
	if (f.message.value=='') {
		alert("Vui long nhap loi nhan");
		f.message.focus();
		return false;
	}
		
return true;
}
</script>
<form name="tell" id="tell" action="" method="post" onSubmit="return checkform(this);">
	<table width="90%" align="center" bgcolor="#CCCCCC" border="0" cellspacing="1" cellpadding="1">
          <tr>
            <td bgcolor="#888888" height="25" colspan="2"><font color="#FFFFFF"><b>&raquo; <?=$ten?></b></font></td>
          </tr>
		  <tr>
            <td bgcolor="#FFFFFF" align="right" width="35%">T&#234;n c&#7911;a b&#7841;n:</td>
			<td bgcolor="#FFFFFF" ><input name="s_name" id="s_name" value="" size="40" maxlength="250"></td>
          </tr>
		  <tr>
            <td bgcolor="#FFFFFF" align="right">Email c&#7911;a b&#7841;n:</td>
			<td bgcolor="#FFFFFF" ><input name="s_email" id="s_email" value="" size="40" maxlength="250"></td>
          </tr>
		  <tr>
            <td bgcolor="#FFFFFF" align="right">T&#234;n ng&#432;&#7901;i nh&#7853;n:</td>
			<td bgcolor="#FFFFFF" ><input name="r_name" id="r_name" value="" size="40" maxlength="250"></td>
          </tr>
		  <tr>
            <td bgcolor="#FFFFFF" align="right">Email ng&#432;&#7901;i nh&#7853;n:</td>
			<td bgcolor="#FFFFFF" ><input name="r_email" id="r_email" value="" size="40" maxlength="250"></td>
          </tr>
		  <tr>
            <td bgcolor="#FFFFFF" align="right">L&#7901;i nh&#7855;n:</td>
			<td bgcolor="#FFFFFF" ><textarea name="message" id="messsage" cols="32" rows="10"><?=$message?></textarea></td>
          </tr>
     </table>
<div align="center" style="padding:3px"><input type="hidden" name="dosend" value="1"><input type="submit" name="submit" value="&nbsp;G&#7903;i&nbsp;">&nbsp;&nbsp;&nbsp;<input type="reset" name="reset" value="L&#224;m l&#7841;i"></div> 
</form>
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
      <div align="center"><a href = "javascript:window.close()"><font color="#990000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><br>
        Close Window</font></a> </div>
	</td>
  </tr>
</table>
</body>
<html>