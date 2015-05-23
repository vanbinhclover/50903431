<?php

class func{

var $base_input = array();

// Strat func
function NDK_encode($t){
global $NDK;
    $t = trim($t);
    $code = base64_encode($t);
    $code = substr($code,5,strlen($code)-7).substr($code,0,5).substr($code,strlen($code)-2);
    $code = substr($code,0,3).substr($code,6,strlen($code)-8).substr($code,3,3).substr($code,strlen($code)-2);
    return $code;
}

function NDK_decode($t){
global $NDK;
    $code=trim($t);
    $code = substr($code,0,3).substr($code,strlen($code)-5,3).substr($code,3,strlen($code)-8).substr($code,strlen($code)-2);
    $code = substr($code,strlen($code)-7,5).substr($code,0,strlen($code)-7).substr($code,strlen($code)-2);
    $code = base64_decode($code);
    return $code;
}

function Get_Input($t){
global $NDK;
if ($NDK->conf['encode_link']) { // Encode the URL by NDK
    $in_arr = array();
    $code=trim($t);
    $code = substr($code,0,3).substr($code,strlen($code)-5,3).substr($code,3,strlen($code)-8).substr($code,strlen($code)-2);
    $code = substr($code,strlen($code)-7,5).substr($code,0,strlen($code)-7).substr($code,strlen($code)-2);
    $code = base64_decode($code);
} else $code=trim($t);
    
    $cmd_arr = explode("|",$code);
    foreach($cmd_arr as $value) {
        if (!empty($value)) {
            $k = trim(substr($value,0,strpos($value,":")));
            $v = trim(substr($value,strpos($value,":")+1));
            $in_arr[$k] = $v;
            $this->base_input[$k] = $v;
        }
    }
    if( is_array($_POST) )
        {
            while( list($k, $v) = each($_POST) )
            {
                if ( is_array($_POST[$k]) )
                {
                    while( list($k2, $v2) = each($_POST[$k]) )
                    {
                        $in_arr[ $this->clean_key($k) ][ $this->clean_key($k2) ] = $this->clean_value($v2);
                    }
                }
                else
                {
                    $in_arr[ $this->clean_key($k) ] = $this->clean_value($v);
                }
            }
        }
    return $in_arr;
}
// End func
function Location() {
global $input;
    $txt = "";
    while( list($k,$v) = each($input) ) {
        if ( (!empty($k)) && (!empty($v)) ) $txt .= $k.":".$v."|";
    }   
    return $txt;
}

function clean_key($key) {
    
        if ($key == "")
        {
            return "";
        }
        $key = preg_replace( "/\.\./"           , ""  , $key );
        $key = preg_replace( "/\_\_(.+?)\_\_/"  , ""  , $key );
        $key = preg_replace( "/^([\w\.\-\_]+)$/", "$1", $key );
        
        return $key;
}
//===================
function clean_value($val) {

        if ($val == "")
        {
            return "";
        }
        $val = str_replace( "&#032;", " ", $val );
        $val = str_replace( "&"            , "&amp;"         , $val );
        $val = str_replace( "<!--"         , "&#60;&#33;--"  , $val );
        $val = str_replace( "-->"          , "--&#62;"       , $val );
        $val = preg_replace( "/<script/i"  , "&#60;script"   , $val );
        $val = str_replace( ">"            , "&gt;"          , $val );
        $val = str_replace( "<"            , "&lt;"          , $val );
        $val = str_replace( "\""           , "&quot;"        , $val );
		$val = str_replace( "\&quot;"           , "&quot;"        , $val );
		$val = str_replace( "\'"           , "&#39;"        , $val );
        $val = preg_replace( "/\n/"        , "<br />"        , $val ); // Convert literal newlines
        $val = preg_replace( "/\\\$/"      , "&#036;"        , $val );
        $val = preg_replace( "/\r/"        , ""              , $val ); // Remove literal carriage returns
        $val = str_replace( "!"            , "&#33;"         , $val );
        $val = str_replace( "'"            , "&#39;"         , $val ); // IMPORTANT: It helps to increase sql query safety.
        
        // Ensure unicode chars are OK
        $val = preg_replace("/&amp;#([0-9]+);/s", "&#\\1;", $val );
        
        // Swop user inputted backslashes
        
//      $val = preg_replace( "/\\\(?!&amp;#|\?#)/", "&#092;", $val ); 
        
        return $val;
}
//========================
function md10($txt) {  // MD10 Encode by NDK
    $txt = md5($txt);
    $txt = base64_encode($txt);
    $txt = md5($txt);
    return $txt;
}
//===================   
function Link($t=""){
global $NDK;
if ($NDK->conf['encode_link']) { // Encode the URL by NDK
    $t = trim($t);
    $code = base64_encode($t);
    $code = substr($code,5,strlen($code)-7).substr($code,0,5).substr($code,strlen($code)-2);
    $code = substr($code,0,3).substr($code,6,strlen($code)-8).substr($code,3,3).substr($code,strlen($code)-2);
} else $code = trim($t);
    return $code;
}

// Strat func
function HTML($t=""){
//  $t = addslashes($t);
    $text = nl2br($t);
        
    $text = str_replace("[url]http://","[url]",$text);
    $text = str_replace("[url=http://","[url=",$text);
    //$text = preg_replace("/(http.*:\/\/.+)\s/U", "<a href=\"$1\">$1</a> ", $text);
    $text = preg_replace('/(\[b\])(.+?)(\[\/b\])/', '<b>\\2</b>',$text);
    $text = preg_replace('/(\[i\])(.+?)(\[\/i\])/', '<i>\\2</i>',$text);
    $text = preg_replace('/(\[u\])(.+?)(\[\/u\])/', "<u>\\2</u>", $text);
    $text = preg_replace('/(\[color=(.+?)\])(.+?)(\[\/color\])/', '<font color=\\2>\\3</font>',$text);
    $text = preg_replace('/(\[email\])(.+?)(\[\/email\])/', "<a href=\"mailto:\\2\">\\2 </a>", $text);
    $text = preg_replace('/(\[email=(.+?)\])(.+?)(\[\/email\])/', "<a href=\"mailto:\\2\">\\3</a>", $text);
    $text = preg_replace('/(\[url\])(.+?)(\[\/url\])/', "<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $text);
    $text = preg_replace('/(\[url=\])(.+?)(\[\/url\])/', "<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $text);
    $text = preg_replace('/(\[url=(.+?)\])(.+?)(\[\/url\])/', "<a href=\"http://\\2\" target=\"_blank\">\\3</a>", $text);
    $text = stripslashes($text);
    $text = str_replace("!!!!", "!", $text);

    $text = str_replace( "[img]", "<img align=right width=150 src=news_images/"  , $text );
    $text = str_replace( "[/img]", " />"  , $text );
    return $text;
}
// End func

// Strat func
function txt_HTML($t=""){
//  $t = addslashes($t);
    $t = preg_replace("/&(?!#[0-9]+;)/s", '&amp;', $t );
    $t = str_replace( "<", "&lt;"  , $t );
    $t = str_replace( ">", "&gt;"  , $t );
    $t = str_replace( '"', "&quot;", $t );
    $t = str_replace( "'", '&#039;', $t );
    return $t;
}
// End func

// Strat func
function txt_unHTML($t=""){
    $t = stripslashes($t);
//  $t = nl2br($t);
    $t = preg_replace("/&(?!#[0-9]+;)/s", '&amp;', $t );
    $t = str_replace( "<", "&lt;"  , $t );
    $t = str_replace( ">", "&gt;"  , $t );
    $t = str_replace( '"', "&quot;", $t );
    $t = str_replace( "'", '&#039;', $t );
    return $t;
}
// End func
function txt_unHTML1($t=""){
	$t = stripslashes($t);
	$t=str_replace( "&#039;", "\'",$t);
	$t=str_replace( "\r\n", "<br>",$t);
//	$t=str_replace( "&quot;", "",$t);
//	$t = nl2br($t);
	
	return $t;

}
function size_format($bytes="")
    {
        $retval = "";
        if ($bytes >= 1048576)
        {
            $retval = round($bytes / 1048576 * 100 ) / 100 . " MB";
        }
        else if ($bytes  >= 1024)
        {
            $retval = round($bytes / 1024 * 100 ) / 100 . " KB";
        }
        else
        {
            $retval = $bytes . " bytes";
        }
        return $retval;
    }
// Start func
/*
    Functions Upload : $result=$func->Upload($data);
    ex: $data['path']= "c://appserv/www/vnTRUST/";
        $data['dir']= "uploads";
        $data['file']= $_FILES['upload'];
        $data['type']= "hinh" or "";
        $data['resize']= 1 or 0;
        $data['w']= "";
        $data['h']= "";
    return : $result['err'] = ""; $result['link'] = "";
*/
function Upload($data){
    // Upload
            $path = $data['path'].$data['dir'];
            $max_size = 3*1024*1024; 
            $err = "";
            $image = $data['file'];
            $size=$image['size'];
            if ($data['resize']) $w=$data['w'];
            if ($image['size']>0) {
                if ($image['size']>$max_size) $err .= "File h&#236;nh qu&#225; l&#7899;n :(";
                if ($data['type']=="hinh") {
                    if (($image['type']=="image/gif") || ($image['type']=="image/pjpeg") || ($image['type']=="image/x-png") || ($image['type']=="image/bmp")) {
                        while (file_exists($path."/".$image['name'])) {
                            $image['name']="NDK_".$image['name'];
                        }
                        $chophep=1;
                    } else $err .= "- &#272;&#7883;nh d&#7841;ng kh&#244;ng &#273;&#432;&#7907;c h&#7895; tr&#7907; !";
                }
                /*
                if ($data['type']=="zip") {
                    if ($image['type']=="application/zip") {
                        if (file_exists($path . $image['name'])) $err .= "- T&#234;n file n&#224;y &#273;&#227; c&#243; !";
                    } else $err .= "- &#272;&#7883;nh d&#7841;ng c&#7911;a File kh&#244;ng h&#7907;p l&#7879; !";
                }
                */
                if (empty($err)) {
                    $filename = substr($image['name'],0,strrpos($image['name'],".")).".jpg";
                    $link_file = $path."/".$image['name'];
                    if (($data['type']=="hinh") && ($chophep) ) $this->Save($image['tmp_name'],$link_file,$w);
                    else $res = copy($image['tmp_name'],$link_file); 
                    
                    $re['link'] = $data['dir']."/".$filename;
                    /*
                    $res = @copy($image['tmp_name'],$link_file);
                    if (!$res) $err .= "- Kh&#244;ng th&#7875; Upload File . CHMOD l&#7841;i th&#432; m&#7909;c";
                    else $re['link'] = $data['dir'].$image['name'];
                    */
                }
            }
    // End upload
            $re['err'] = $err;
            $re['size'] = $size;
            return $re;
}
// End func

function Save($imgfile="",$path,$w) {
    $gd_version = 2 ;
    $img['format']=ereg_replace(".*\.(.*)$","\\1",$path);
    $img['format']=strtoupper($img['format']);
    if ($img['format']=="JPG" || $img['format']=="JPEG") {
            //JPEG
            $img['src'] = imagecreatefromjpeg($imgfile);
        }
    if ($img['format']=="PNG") {
            //PNG
            $img['src'] = imagecreatefrompng($imgfile);
        }
    if ($img['format']=="GIF") {
            //GIF
            $img['src'] = imagecreatefromgif($imgfile);
        }
    if ($img['format']=="BMP") {
            //BMP
            include("../libs/bmp.php");
            $img['src'] = imagecreatefrombmp($imgfile);
        }
    $img['old_w'] = imagesx($img['src']);
    $img['olh_h'] = imagesy($img['src']);
    if ($w!=0) {
        if ($img['old_w']>$w) $h = ($w/$img['old_w'])*$img['olh_h'];
        else {
            $w=$img['old_w'];
            $h=$img['olh_h'];
            }
        if ($h>$w*2) {
            $w = ($w*2/$h)*$w;
            $h = $w*2;
        }
    } else {
        $w = $img['old_w'];
        $h = $img['olh_h'];
    }
    if ($w<96) {
        $space=(96-$w)/2;
        $w=96; 
    } else {
            $space=0;
        }
if($gd_version==2) {
    $img['des'] = imagecreatetruecolor($w,$h);
    $white = imagecolorallocate($img['des'],255,255,255);
    imagefill($img['des'],1,1,$white);
    imagecopyresampled($img['des'],$img['src'], $space, 0, 0, 0, $w-($space*2) , $h , $img['old_w'], $img['olh_h']);
}
if($gd_version==1) {
    $img['des'] = imagecreatetruecolor($w,$h);
    $white = imagecolorallocate($img['des'],255,255,255);
    imagefill($img['des'],1,1,$white);
    imagecopyresized($img['des'],$img['src'], $space, 0, 0, 0, $w-($space*2) , $h , $img['old_w'], $img['olh_h']);
}

    $path = substr($path,0,strrpos($path,".")).".jpg";
    imagejpeg($img['des'],$path,100);

}
// End 
function makedate($text) {
    $tmp = explode ("-",$text);
    return $tmp[2]."/".$tmp[1]."/".$tmp[0];
}

// Strat func
function Insert_Session(){
global $DB,$NDK,$input;
    $s_id = md5( uniqid(microtime()) );
    $time = time() ;
    $ip = $_SERVER['REMOTE_ADDR'];
    $agent = $_SERVER['HTTP_USER_AGENT'];
    $query = "INSERT INTO sessions (s_id,ip,agent,time) VALUES ('{$s_id}','{$ip}','{$agent}','{$time}') ";
    $data_arr = $DB->query($query);
    /*
     *session_register("s_id");
     */
    isset($_SESSION['s_id']);

    $_SESSION['s_id'] = $s_id;
    $randnum=1;
    // He he....Cai na`y goi la` an gian ^.^
//      $randnum = rand(1,3);
    // End An gian
    $query = "UPDATE config SET count=count+{$randnum}";
    $data_arr = $DB->query($query);
    // Delete Old Session
    $thoihan = time() - 1800;
    $run_delete = $DB->query("DELETE FROM sessions WHERE time < {$thoihan} ");
    // End Delete
}
// End func

function Update_Session(){
global $DB,$NDK,$input;
    $s_id = $NDK->user['session_id'];
    $isonline = $DB->query("SELECT * FROM sessions WHERE s_id='{$s_id}' ");
    if ($ok=$DB->fetch_row($isonline)) {
        $time = time() ;
        $act = $input['act'];
        $run_update = $DB->query("UPDATE sessions SET time='{$time}' WHERE s_id='{$s_id}'");
    } else {
        $this->Insert_Session();
        }
    // Delete Old Session
    $thoihan = time() - 1800;
    $run_delete = $DB->query("DELETE FROM sessions WHERE time < {$thoihan} ");
    $randnum=0;
    // End Delete
    // He he....Cai na`y goi la` an gian ^.^
//      $randnum = rand(0,2);
    // End An gian
//    $query = "UPDATE config SET count=count+{$randnum} ";
    $data_arr = $DB->query($query);
}
// End func

// Strat func
function count_img($count="0"){
    $imagedir = "images/digits";
    $countimg = "";
    for ($i=0;$i<strlen($count);$i++)
    {
        $k = substr($count,$i,1);
        $countimg .= "<img src=\"{$imagedir}/{$k}.gif\" width=16 height=21 border=0>";
    }
    return $countimg;
}
// End func

// Strat func
function Get_Stats(){
global $DB,$NDK;
    // Session Start
    /*
     * if (!session_is_registered("s_id")) {
     */

    if (!isset($_SESSION['s_id'])) {
        $this->Insert_Session();
    } else {
            $this->Update_Session();
        }
    // End Session
    $thoihan = time() - 1800;
    $get_online = $DB->query("SELECT * FROM sessions WHERE time >= {$thoihan} ");
    $stats['now'] = $DB->num_rows($get_online);
    $stats['now_img'] = $this->count_img($stats['now']);
    // He he....Cai na`y goi la` an gian ^.^
//      $randnum = rand(1,3);
//      $thongke['now'] = $thongke['now'] + $randnum;
    // End An gian
    $query = "select count,email from config";
    $data_arr = $DB->query($query);
    if ($totals = $DB->fetch_row($data_arr)) {
        $stats['totals'] = $totals['count'];
        $stats['totals_img'] = $this->count_img($totals['count']);
    }
    return $stats;
}
// End func

//////////////////////////////////
// Fetch the config info
////////
function fetchDbConfig($confName) {
    global $DB;
    $result = $DB->query("SELECT array FROM shop_config WHERE name = '{$confName}'");
        if($result==TRUE){
            $base64Encoded = unserialize($result[0]['array']);
            foreach($base64Encoded as $key => $value){
                $base64Decoded[base64_decode($key)] = stripslashes(base64_decode($value));
            }
            return $base64Decoded;
        } else {
            //return FALSE;
            return 1;
        }
}
//============================
function Create_Link($newInput) {
global $input;

$cur_input = $this->base_input;
// Insert New
   $cmd_arr = explode("|",$newInput);
    foreach($cmd_arr as $value) {
        if (!empty($value)) {
            $k = trim(substr($value,0,strpos($value,":")));
            $v = trim(substr($value,strpos($value,":")+1));
            $cur_input[$k] = $v;
        }
    }
// end
$cmdstr = "";
while( list($k, $v) = each($cur_input) ){
       $cmdstr .= $k.":".$v."|";
}
$linkout = "?cmd=".$this->Link($cmdstr);

return $linkout;
}
//====================
function paginate($numRows, $maxRows, $extra="", $pageVar="p", $class="pagelink") {
global $input;
	$navigation = "";
	// get total pages
	$totalPages = ceil($numRows/$maxRows);
	$cPage = $input[$pageVar];
	$pmore = 5;
	$next_page = $pmore;
	$prev_page = $pmore;
	if ($cPage<$pmore) $next_page=$pmore+$pmore-$cPage;
	if ($totalPages-$cPage<$pmore) $prev_page=$pmore+$pmore-($totalPages-$cPage);

	$navigation .= "<span class=\"{$class}\"><b>".$numRows."</b> b&#224;i </span>&nbsp;";
	
if ($totalPages>1) {
  
	$navigation .= "<span class=\"pagecur\">".$totalPages." Trang</span>";
// Show first page
	if ($cPage>($pmore+1)) {
		$pLink = $this->Create_Link("p:1|{$extra}");
		$navigation .= "&nbsp;<a href='".$pLink."' class='".$class."'><b><font color=\"#FF0000\">&laquo;</font></b></a>";
	}
// End
// Show Prev page
	if ($cPage>1) {
		$numpage = $cPage-1;
		$pLink = $this->Create_Link("p:".$numpage."|{$extra}");
		$navigation .= "&nbsp;<a href='".$pLink."' class='".$class."'><b><font color=\"#0000FF\">&lsaquo;</font></b></a>";
	}
// End	
// Left
	for ($i=$prev_page;$i>=0;$i--) {
		$pagenum = $cPage-$i;
		if (($pagenum>0) && ($pagenum<$cPage)) {
			$pLink = $this->Create_Link("p:{$pagenum}|{$extra}");
			$navigation .= "&nbsp;<a href='".$pLink."' class='".$class."'>".$pagenum."</a>";
		}
	}
// End	
// Current
	$navigation .= "&nbsp;<span class=\"pagecur\">".$cPage."</span>";
// End
// Right
	for ($i=1;$i<=$next_page;$i++) {
		$pagenum = $cPage+$i;
		if (($pagenum>$cPage) && ($pagenum<=$totalPages)) {
			$pLink = $this->Create_Link("p:{$pagenum}|{$extra}");
			$navigation .= "&nbsp;<a href='".$pLink."' class='".$class."'>".$pagenum."</a>";
		}
	}
// End
// Show Next page
	if ($cPage<$totalPages) {
		$numpage = $cPage+1;
		$pLink = $this->Create_Link("p:".$numpage."|{$extra}");
		$navigation .= "&nbsp;<a href='".$pLink."' class='".$class."'><b><font color=\"#0000FF\">&rsaquo;</font></b></a>";
	}
// End		
// Show Last page
	if ($cPage<($totalPages-$pmore)) {
		$pLink = $this->Create_Link("p:".$totalPages."|{$extra}");
		$navigation .= "&nbsp;<a href='".$pLink."' class='".$class."'><b><font color=\"#FF0000\">&raquo;</font></b></a>";
	}
// End

} // end if total pages is greater than one

$navigation = "<div style=\"padding:7px 2px 2px 2px\">{$navigation}</div>";

	return $navigation;
}
//====================
function ShowTable($table) {
global $NDK,$func;
$out = "";
$numcol = count($table['title']);
$rowfield = array();
$rowalign = array();
$rowextra = array();
$out .= $NDK->skin->Table_Top($table['name'],$numcol);
// Title
$out .= "<tr>";
while( list($k, $v) = each($table['title']) ){
       $rowfield[]=$k;
       $tittle_arr = explode("|",$v);
       $title = $tittle_arr[0];
       $width = $tittle_arr[1];
       $align = $tittle_arr[2];
       $extra = $tittle_arr[3];

       if (!empty($align)) $align="align=\"{$align}\"";
       $rowalign[] = $align;
       $rowextra[] = $extra;
       $out .= "<td height=20 {$align} width=\"{$width}\" class=\"row_title\" {$extra}>&nbsp;{$title}</td>";
}
$out .= "</tr>\n";
// End
// Row
if (count($table['row'])>0) {
foreach($table['row'] as $row) {
    $out .= "<tr {$row['extra']}>";
    for ($i=0;$i<$numcol;$i++) {
        $value = $row[$rowfield[$i]];
        $align = $rowalign[$i];
        $extra = $rowextra[$i];

		if ($value=='') $value="&nbsp;";	
        if ($i==0) $class="row1";
        else $class="row";
        $out .= "<td class=\"{$class}\" {$align} {$extra} >{$value}</td>";
    }
    $out .= "</tr>\n";
}
} else {
	$out .= "<tr><td colspan=\"{$numcol}\" bgcolor=\"#FFFFFF\" align=center>Ch&#432;a c&#243; b&#224;i n&#224;o c&#7843;.</td></tr>";
}
//end
$out .= $NDK->skin->Table_Bot($table['extra'],$numcol,$table['extra_style']);
return $out;
}
//==============================
function Get_Title($id,$col="ten",$table="contents",$colcheck="id") {
global $DB,$NDK,$func;
	$sql="SELECT {$col} FROM {$table} WHERE {$colcheck}='{$id}'";
	$qr=$DB->query($sql);
	if ($result=$DB->fetch_row($qr)) $title=$result[$col];
	else $title="";
return $title;
}
//===============
function ShowAlpha($cat,$alpha="") {
global $DB,$NDK,$func;
$out="";
$alpha_arr = array ("A","B","C","D","&#272;","E","G","H","I","K","L","M","N","O","P","Q","R","S","T","U","V","X","Y","0-9");

$out.="<table width=\"100%\" border=0 cellpadding=3 cellspacing=1 bgcolor=\"#EEEEEE\"><tr>";
for ($i=0;$i<count($alpha_arr);$i++) {
	if ($alpha==$alpha_arr[$i]) {
	  $out.="<td align=center bgcolor=\"#EEEEEE\"><a href=\"?cmd=act:{$cat}|alpha:{$alpha_arr[$i]}\"><b><font color=\"#1111FF\">{$alpha_arr[$i]}</font></b></a></td>";
	} else {
	  $out.="<td align=center bgcolor=\"#FFFFFF\" onmouseover=\"this.bgColor='#EEEEEE'\" onmouseout=\"this.bgColor='#FFFFFF'\"><a href=\"?cmd=act:{$cat}|alpha:{$alpha_arr[$i]}\"><b>{$alpha_arr[$i]}</b></a></td>";
	}
}
$out.="</tr></table><br>";

return $out;
}
//===============
function ShowListDai($cat="") {
global $DB,$NDK,$func;
$ext_qr=(!empty($cat)) ? "WHERE cat='{$cat}'":"WHERE cat='' OR cat is NULL";
$link_act=(!empty($cat)) ? $cat:"TruyenDai";
$out="";
$out.="<table width=\"90%\" align=center border=0 cellpadding=3 cellspacing=1 bgcolor=\"#EEEEEE\"><tr>";
$xong=$chua=$tatca=0;
$qr=$DB->query("SELECT status FROM truyendai {$ext_qr}");
while ($truyen=$DB->fetch_row($qr)) {
	if ($truyen['status']==0) $chua++;
	else $xong++;
	$tatca++;
}

$out.="<td align=center bgcolor=\"#FFFFFF\" onmouseover=\"this.bgColor='#EEEEEE'\" onmouseout=\"this.bgColor='#FFFFFF'\"><a href=\"?cmd=act:{$link_act}|type:1\"><b>Truy&#7879;n &#273;&#259;ng xong</b> [{$xong}]</a></td>";

$out.="<td align=center bgcolor=\"#FFFFFF\" onmouseover=\"this.bgColor='#EEEEEE'\" onmouseout=\"this.bgColor='#FFFFFF'\"><a href=\"?cmd=act:{$link_act}|type:0\"><b>Truy&#7879;n &#273;ang &#273;&#259;ng</b> [{$chua}]</a></td>";

$out.="<td align=center bgcolor=\"#FFFFFF\" onmouseover=\"this.bgColor='#EEEEEE'\" onmouseout=\"this.bgColor='#FFFFFF'\"><a href=\"?cmd=act:{$link_act}\"><b>T&#7845;t c&#7843; truy&#7879;n</b> [{$tatca}]</a></td>";

$out.="</tr></table><br>";

return $out;
}
//============

}
?>