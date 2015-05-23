<?php
session_start();
require_once("_config.php"); 
$NDK->conf = $conf;
require_once("libs/_mysql.php"); 
$DB = new DB;
$DB->connect();
if (!empty($_GET['cmd'])) $cmd=$_GET['cmd']; else $cmd="";
if (!empty($_GET['idnum'])) $idnum=$_GET['idnum']; else $idnum=0;
if (!empty($_GET['valtext'])) $valtext=$_GET['valtext']; else $valtext="";
if (!empty($_GET['p'])) $page=$_GET['p']; else $page=1;
require_once("libs/_functions.php");
$func=new func();
//Load setting from DB
$query = $DB->query("SELECT * FROM setting");
while ($set=$DB->fetch_row($query)) {
    $NDK->conf[$set['s_key']] = $set['s_value'];
}
// End
$NDK->stats = $func->Get_Stats();
$input = $func->Get_Input($cmd);
if (empty($input['p'])) $input['p']=$page;
if (empty($input['idnum'])) $input['idnum']=$idnum;
if (empty($input['valtext'])) $input['valtext']=$valtext;
if (empty($input['act'])) $input['act']="main";


require_once("skins/skin_global.php");
$NDK->skin=new sglobal();
$NDK->output = "";
include "sources/menu.php";
$NDK->menu = new sMenu();
$data['menu'] = $NDK->menu->Create_Menu();

$NDK->output .= $NDK->skin->top_header($data);
$input['act'] = str_replace("\\'","&#039;",$input['act']);
$filesource = $input['act'].".php";
$filesource = str_replace("..","",$filesource);
if (!file_exists("sources/_".$filesource)) {
   $filesource = "index.php";
}
include "sources/_".$filesource;
// Data for Footer
$data['stats'] = "<b>{$NDK->stats['totals']}</b>";
// End
$NDK->output .= $NDK->skin->footer($data);
echo $NDK->output;
?>