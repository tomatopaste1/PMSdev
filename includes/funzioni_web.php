<?php

##################################################################################
#    HOTELDRUID
#    Copyright (C) 2019 by Marco Maria Francesco De Santis (marco@digitaldruid.net)
#
#    This program is free software: you can redistribute it and/or modify
#    it under the terms of the GNU Affero General Public License as published by
#    the Free Software Foundation, either version 3 of the License, or
#    any later version accepted by Marco Maria Francesco De Santis, which
#    shall act as a proxy as defined in Section 14 of version 3 of the
#    license.
#
#    This program is distributed in the hope that it will be useful,
#    but WITHOUT ANY WARRANTY; without even the implied warranty of
#    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#    GNU Affero General Public License for more details.
#
#    You should have received a copy of the GNU Affero General Public License
#    along with this program.  If not, see <http://www.gnu.org/licenses/>.
##################################################################################



function upload_hd_img_form ($id_txt_url,$tablepersonalizza,$ord="",$js="") {

$out = "";
if (defined('C_CARTELLA_CREA_MODELLI') and C_CARTELLA_CREA_MODELLI != "") {
if (!$js or $js == 2) {
$url_base_upload = "";
if (defined('C_CARTELLA_UPLOAD_IMG') and C_CARTELLA_UPLOAD_IMG != "" and @is_dir(C_CARTELLA_CREA_MODELLI."/".C_CARTELLA_UPLOAD_IMG)) $percorso_cartella_upload = C_CARTELLA_UPLOAD_IMG;
else $percorso_cartella_upload = "";
if (substr($percorso_cartella_upload,0,1) == "/") $percorso_cartella_upload = substr($percorso_cartella_upload,1);
if (strcmp($percorso_cartella_upload,"") and substr($percorso_cartella_upload,-1) != "/") $percorso_cartella_upload .= "/";
if (substr($percorso_cartella_upload,0,2) == "./") $percorso_cartella_upload = substr($percorso_cartella_upload,2);
if (defined("C_FILE_DOMINIO") and C_FILE_DOMINIO != "" and (!defined('C_NASCONDI_MARCA') or C_NASCONDI_MARCA != "SI")) {
$altri_domini = @file(C_FILE_DOMINIO);
if ($altri_domini) {
$url_base_upload = "https://".trim($altri_domini[0])."/$percorso_cartella_upload";
} # fine if ($altri_domini)
unset($altri_domini);
} # fine if (defined("C_FILE_DOMINIO") and C_FILE_DOMINIO != "" and (!defined('C_NASCONDI_MARCA') or C_NASCONDI_MARCA != "SI"))
if (!$url_base_upload) {
if (!function_exists('trova_url_pagina')) include("./includes/templates/funzioni_modelli.php");
if (substr($percorso_cartella_upload,-1) == "/") $percorso_cartella_upload = substr($percorso_cartella_upload,0,-1);
$url_base_upload = trova_url_pagina("",C_CARTELLA_CREA_MODELLI."/$percorso_cartella_upload","");
} # fine if (!$url_base_upload)
unset($percorso_cartella_upload);
$out .= "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"20000000\">
<script type=\"text/javascript\">
<!--
function update_hd_img_txt_url (id_txt_url) {
var txt_url = document.getElementById(id_txt_url);
var fhdiu =  document.getElementById('fhdiu').value;
if (fhdiu.indexOf('/') > -1) fhdiu = fhdiu.substring(fhdiu.lastIndexOf('/')+1);
if (fhdiu.indexOf('\\\\') > -1) fhdiu = fhdiu.substring(fhdiu.lastIndexOf('\\\\')+1);
txt_url.value = encodeURI('$url_base_upload'+fhdiu);
}
-->
</script>
";
} # fine if (!$js or $js == 2)
if ($js != 2) {
$out1 = "(".mex("Fai l'upload di",'tab_tariffe.php')." <input id=\"fhdiu\" name=\"file_hd_img_upload$ord\" type=\"file\" onchange=\"update_hd_img_txt_url('$id_txt_url');\">)";
if ($js) $out1 = str_replace("'","\\'",$out1);
$out .= $out1;
} # fine if ($js != 2)
} # fine if (defined('C_CARTELLA_CREA_MODELLI') and C_CARTELLA_CREA_MODELLI != "")

return $out;

} # fine function upload_hd_img_form




function upload_hd_img ($filename,$ord="") {

if (defined('C_CARTELLA_CREA_MODELLI') and C_CARTELLA_CREA_MODELLI != "") {
global ${"file_hd_img_upload$ord"};
if (!${"file_hd_img_upload$ord"}) {
if ($HTTP_POST_FILES["file_hd_img_upload$ord"]['tmp_name']) ${"file_hd_img_upload$ord"} = $HTTP_POST_FILES["file_hd_img_upload$ord"]['tmp_name'];
else if ($_FILES["file_hd_img_upload$ord"]['tmp_name']) ${"file_hd_img_upload$ord"} = $_FILES["file_hd_img_upload$ord"]['tmp_name'];
} # fine if (!${"file_hd_img_upload$ord"})
if (${"file_hd_img_upload$ord"}) {

if (defined('C_CARTELLA_UPLOAD_IMG') and C_CARTELLA_UPLOAD_IMG != "" and @is_dir(C_CARTELLA_CREA_MODELLI."/".C_CARTELLA_UPLOAD_IMG)) $percorso_cartella_upload = C_CARTELLA_UPLOAD_IMG;
else $percorso_cartella_upload = "";
if (substr($percorso_cartella_upload,0,1) == "/") $percorso_cartella_upload = substr($percorso_cartella_upload,1);
if (substr($percorso_cartella_upload,0,2) == "./") $percorso_cartella_upload = substr($percorso_cartella_upload,2);
$percorso_cartella_upload = C_CARTELLA_CREA_MODELLI."/$percorso_cartella_upload";
if (strcmp($percorso_cartella_upload,"") and substr($percorso_cartella_upload,-1) != "/") $percorso_cartella_upload .= "/";

$errore = 0;
if (strstr($filename,"/")) {
$filename = explode("/",$filename);
$filename = $filename[(count($filename) - 1)];
} # fine if (strstr($filename,"/"))
if (strstr($filename,"\\")) {
$filename = explode("\\",$filename);
$filename = $filename[(count($filename) - 1)];
} # fine if (strstr($filename,"\\"))
$filename = urldecode($filename);
$lowfn = strtolower($filename);
if (substr($lowfn,-4) != ".jpg" and substr($lowfn,-5) != ".jpeg" and substr($lowfn,-4) != ".gif" and substr($lowfn,-4) != ".png" and substr($lowfn,-5) != ".webp" and substr($lowfn,-4) != ".svg" and substr($lowfn,-4) != ".ico") $errore = 1;
if (!$errore and (@is_file("$percorso_cartella_upload/$filename") or @is_dir("$percorso_cartella_upload/$filename") or @is_link("$percorso_cartella_upload/$filename"))) {
$errore = 1;
echo mex("Eesiste già un file chiamato",'tab_tariffe.php')." ".htmlspecialchars($filename).".<br>";
} # fine if (!$errore and (@is_file("$percorso_cartella_upload/$filename") or...

if (!$errore) {
$file_tmp = C_DATI_PATH."/hoteld_img_upl.tmp";
if (!move_uploaded_file(${"file_hd_img_upload$ord"},$file_tmp)) $errore = 1;
if (!$errore) {
if (defined("C_MASSIMO_NUM_BYTE_UPLOAD") and C_MASSIMO_NUM_BYTE_UPLOAD != 0 and filesize($file_tmp) > C_MASSIMO_NUM_BYTE_UPLOAD) {
$errore = 1;
echo mex("La dimensione del file eccede il limite",'tab_tariffe.php').".<br>";
} # fine if (defined("C_MASSIMO_NUM_BYTE_UPLOAD") and C_MASSIMO_NUM_BYTE_UPLOAD != 0 and...
elseif (!rename($file_tmp,"$percorso_cartella_upload/$filename")) $errore = 1;
if ($errore) unlink($file_tmp);
} # fine if (!$errore)
} # fine if (!$errore)
if (!$errore) echo mex("Ho fatto l'upload del file",'crea_backup.php')." ".htmlspecialchars($filename).".<br>";
else echo mex("Non ho potuto fare l'upload del file",'crea_backup.php').".<br>";

} # fine if (${"file_hd_img_upload$ord"})
} # fine if (defined('C_CARTELLA_CREA_MODELLI') and C_CARTELLA_CREA_MODELLI != "")

} # fine function upload_hd_img




?>