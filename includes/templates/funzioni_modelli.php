<?php

##################################################################################
#    HOTELDRUID
#    Copyright (C) 2001-2017 by Marco Maria Francesco De Santis (marco@digitaldruid.net)
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





if (!$tablepersonalizza) $tablepersonalizza = $PHPR_TAB_PRE."personalizza";
$percorso_cartella_modello = esegui_query("select valpersonalizza from $tablepersonalizza where idpersonalizza = 'percorso_cartella_modello' and idutente = '1'");
$percorso_cartella_modello = risul_query($percorso_cartella_modello,0,'valpersonalizza');
if (defined('C_CARTELLA_CREA_MODELLI') and C_CARTELLA_CREA_MODELLI != "") {
$c_cartella_crea_mod = C_CARTELLA_CREA_MODELLI;
if (substr($c_cartella_crea_mod,-1) == "/") $c_cartella_crea_mod = substr($c_cartella_crea_mod,0,-1);
if (substr($percorso_cartella_modello,0,strlen($c_cartella_crea_mod)) != $c_cartella_crea_mod) $percorso_cartella_modello = $c_cartella_crea_mod;
} # fine if (defined("C_CARTELLA_CREA_MODELLI") and C_CARTELLA_CREA_MODELLI != "")
else $c_cartella_crea_mod = "";
$perc_cart_mod_int = $percorso_cartella_modello;
$perc_cart_mod_vett = explode(",",$percorso_cartella_modello);
$num_perc_cart_mod_vett = count($perc_cart_mod_vett);
$vett_tmp = array();
$num_vett_tmp = 0;
for ($num_cart = 0 ; $num_cart < $num_perc_cart_mod_vett ; $num_cart++) {
if (!$c_cartella_crea_mod or substr($perc_cart_mod_vett[$num_cart]."/",0,strlen($c_cartella_crea_mod."/")) == $c_cartella_crea_mod."/") {
if (@is_dir($perc_cart_mod_vett[$num_cart])) {
$percorso_cartella_modello = $perc_cart_mod_vett[$num_cart];
$vett_tmp[$num_vett_tmp] = $percorso_cartella_modello;
$num_vett_tmp++;
if ($percorso_cartella_modello == $perc_cart_mod_sel) break;
} # fine if (@is_dir($perc_cart_mod_vett[$num_cart]))
} # fine if (!$c_cartella_crea_mod or...
} # fine for $num_cart
$perc_cart_mod_vett = $vett_tmp;
$num_perc_cart_mod_vett = $num_vett_tmp;



function mex2 ($messaggio,$pagina,$lingua) {

if ($lingua != "ita") {
include("./includes/lang/$lingua/$pagina");
} # fine if ($lingua != "ita")
elseif ($pagina == "unit.php") include("./includes/unit.php");
return $messaggio;

} # fine function mex2





function includi_file ($file_incluso,$file) {

if (defined('C_CARTELLA_FILES_REALI') and substr($file_incluso,0,(strlen(C_DATI_PATH) + 1)) != C_DATI_PATH."/") $linee_file = file(C_CARTELLA_FILES_REALI.$file_incluso);
else $linee_file = file($file_incluso);
fwrite($file,"
###########################################
###  INIZIO $file_incluso 
###########################################
");
for ($num1 = 0 ; $num1 < count($linee_file) ; $num1++) {
if (!preg_match("/^<\?/i",$linee_file[$num1]) and !preg_match("/^\?>/i",$linee_file[$num1])) fwrite($file,$linee_file[$num1]);
} # fine for $num1
fwrite($file,"
###########################################
###  FINE $file_incluso 
###########################################
");

} # fine function includi_file





function formatta_input_var_x_file ($input_utente) {

if (@get_magic_quotes_gpc()) $input_utente = stripslashes($input_utente);
$input_utente = str_replace("\\","\\\\",$input_utente);
$input_utente = str_replace("\"","\\\"",$input_utente);
$input_utente = str_replace("\\\\n","\\n",$input_utente);
$input_utente = str_replace("<!-- END1 ","<!-- ED1 ",$input_utente);
$input_utente = str_replace("<!-- START2:","<!-- ST2:",$input_utente);
$input_utente = str_replace("<!-- END2:","<!-- ED2:",$input_utente);
return $input_utente;

} # fine function formatta_input_var_x_file





function trova_url_pagina ($nome_file,$percorso_cartella_modello,$pag) {

$url_pagina = "";
if (defined('C_URL_CREA_MODELLI') and (strtolower(substr(C_URL_CREA_MODELLI,0,6)) == "http:/" or strtolower(substr(C_URL_CREA_MODELLI,0,7)) == "https:/")) {
$url_pagina = C_URL_CREA_MODELLI;
if (substr($url_pagina,0,-1) != "/") $url_pagina .= "/";
$url_pagina .= $nome_file;
} # fine if (defined('C_URL_CREA_MODELLI') and (strtolower(substr(C_URL_CREA_MODELLI,0,6)) == "http:/" or...
else {

$url_dir = "";
global $PHP_SELF,$SERVER_NAME,$HTTP_SERVER_VARS,$HTTPS,$SERVER_PORT;
if (@$SERVER_NAME or @$_SERVER['SERVER_NAME'] or @$HTTP_SERVER_VARS['SERVER_NAME']) {
if (@$PHP_SELF or @$_SERVER['PHP_SELF']) {
if ($_SERVER['SERVER_PORT']) $SERVER_PORT = $_SERVER['SERVER_PORT'];
elseif ($HTTP_SERVER_VARS['SERVER_PORT']) $SERVER_PORT = $HTTP_SERVER_VARS['SERVER_PORT'];
if ($_SERVER['SERVER_NAME']) $SERVER_NAME = $_SERVER['SERVER_NAME'];
elseif ($HTTP_SERVER_VARS['SERVER_NAME']) $SERVER_NAME = $HTTP_SERVER_VARS['SERVER_NAME'];
if ($_SERVER['PHP_SELF']) $PHP_SELF = $_SERVER['PHP_SELF'];
if ($HTTPS == "on" or $_SERVER['HTTPS'] == "on" or $SERVER_PORT == "443") $url_server = "https://".$SERVER_NAME;
else $url_server = "http://".$SERVER_NAME;
if ($SERVER_PORT and $SERVER_PORT != "80" and $SERVER_PORT != "443") $url_server .= ":$SERVER_PORT";
if (substr($PHP_SELF,0,1) != "/") $PHP_SELF = "/".$PHP_SELF;
$url_dir = $url_server.$PHP_SELF;
if (substr($url_dir,(strlen($pag) * -1)) == $pag) $url_dir = substr($url_dir,0,(strlen($pag) * -1));
if (substr($url_dir,-4) == ".php") {
$url_vett1 = explode("/",$url_dir);
$url_dir = substr($url_dir,0,(strlen($url_vett1[(count($url_vett1) - 1)]) * -1));
} # fine if (substr($url_dir,-4) == ".php")
} # fine if (@$PHP_SELF or @$_SERVER['PHP_SELF'])
} # fine if (@$SERVER_NAME or @$_SERVER['SERVER_NAME'] or...

if ($url_dir) {
if (defined('C_DOMINIO_CREA_MODELLI') and C_DOMINIO_CREA_MODELLI and defined('C_CARTELLA_CREA_MODELLI') and C_CARTELLA_CREA_MODELLI != "" and stristr($url_server,C_DOMINIO_CREA_MODELLI)) {
$c_cartella_crea_mod = C_CARTELLA_CREA_MODELLI;
if (substr($c_cartella_crea_mod,-1) == "/") $c_cartella_crea_mod = substr($c_cartella_crea_mod,0,-1);
$perc_cart_mod = substr($percorso_cartella_modello,strlen($c_cartella_crea_mod));
$url_pagina = $url_server.$perc_cart_mod."/".$nome_file;
} # fine if (defined('C_DOMINIO_CREA_MODELLI') and C_DOMINIO_CREA_MODELLI and..
else {
if (defined('C_URL_CREA_MODELLI') and C_URL_CREA_MODELLI) {
if (substr(C_URL_CREA_MODELLI,0,2) == "./" or substr(C_URL_CREA_MODELLI,0,3) == "../") $url_pagina = $url_dir.C_URL_CREA_MODELLI;
else $url_pagina = $url_server.C_URL_CREA_MODELLI;
if (defined('C_CARTELLA_CREA_MODELLI') and C_CARTELLA_CREA_MODELLI != "") {
$sub_cartella = C_CARTELLA_CREA_MODELLI;
if (substr($sub_cartella,-1) == "/") $sub_cartella = substr($sub_cartella,0,-1);
$sub_cartella = substr($percorso_cartella_modello,strlen($sub_cartella));
if (substr($sub_cartella,0,1) == "/") $sub_cartella = substr($sub_cartella,1);
if (substr($url_pagina,-1) != "/") $url_pagina .= "/";
$url_pagina .= $sub_cartella;
} # fine if (defined('C_CARTELLA_CREA_MODELLI') and C_CARTELLA_CREA_MODELLI != "")
if (substr($url_pagina,-1) != "/") $url_pagina .= "/";
$url_pagina .= $nome_file;
} # fine if (defined('C_URL_CREA_MODELLI') and C_URL_CREA_MODELLI)
else $url_pagina = "$url_dir$percorso_cartella_modello/$nome_file";
} # fine else if (defined('C_DOMINIO_CREA_MODELLI') and C_DOMINIO_CREA_MODELLI and..

$url_pagina = str_replace("/./","/",$url_pagina);
while (str_replace("/../","",$url_pagina) != $url_pagina) {
$url_vett1 = explode("/../",$url_pagina);
$url_vett2 = explode("/",$url_vett1[0]);
$prima_parte_url = substr($url_vett1[0],0,(strlen($url_vett2[(count($url_vett2) - 1)]) * -1));
$url_pagina = $prima_parte_url.substr($url_pagina,(strlen($url_vett1[0]) + 4));
} # fine while (str_replace("/../","",$url_pagina) != $url_pagina)
} # fine if ($url_dir)
} # fine else if (defined('C_URL_CREA_MODELLI') and strtolower(substr(C_URL_CREA_MODELLI,0,6)) == "http:/" or...

return $url_pagina;

} # fine function trova_url_pagina





function mostra_indirizzi_alernativi ($percorso_cartella_modello,$nome_file,&$url_pagina,$url_pagina_link,$pag,$lingua_orig) {

if (defined("C_FILE_DOMINIO") and C_FILE_DOMINIO != "" and (!defined('C_NASCONDI_MARCA') or C_NASCONDI_MARCA != "SI") and is_file(C_FILE_DOMINIO)) {
$altri_domini = file(C_FILE_DOMINIO);
if ($altri_domini) {
if (defined("C_CARTELLA_CREA_MODELLI") and C_CARTELLA_CREA_MODELLI != "") $percorso_cartella_dominio = substr($percorso_cartella_modello,strlen(C_CARTELLA_CREA_MODELLI));
else $percorso_cartella_dominio = $percorso_cartella_modello;
if (substr($percorso_cartella_dominio,0,1) == "/") $percorso_cartella_dominio = substr($percorso_cartella_dominio,1);
if (strcmp($percorso_cartella_dominio,"") and substr($percorso_cartella_dominio,-1) != "/") $percorso_cartella_dominio .= "/";
if (substr($percorso_cartella_dominio,0,2) == "./") $percorso_cartella_dominio = substr($percorso_cartella_dominio,2);
$lista_altri_domini = "";
$num_altri_domini = count($altri_domini);
if (is_file(C_FILE_DOMINIO."_https")) {
$dom_https = 1;
$altri_dom_https = file(C_FILE_DOMINIO."_https");
$num_dom_https = count($altri_dom_https);
for ($num1 = 0 ; $num1 < $num_dom_https ; $num1++) {
$dom_trovato = 0;
for ($num2 = 0 ; $num2 < $num_altri_domini ; $num2++) if (trim($altri_dom_https[$num1]) == trim($altri_domini[$num2])) $dom_trovato = 1;
if (!$dom_trovato) {
$altri_domini[$num_altri_domini] = $altri_dom_https[$num1];
$num_altri_domini++;
} # fine if (!$dom_trovato)
} # fine for $num1
} # fine (@is_file(C_FILE_DOMINIO."_https"))
else $dom_https = 0;
for ($num1 = 0 ; $num1 < $num_altri_domini ; $num1++) {
if ($num1 == 0 or $dom_https) $altro_dominio = "https://";
else $altro_dominio = "http://";
$altro_dominio .= trim($altri_domini[$num1])."/$percorso_cartella_dominio$nome_file";
if ($num1 == 0 or $dom_https) $url_pagina = $altro_dominio;
if ($altro_dominio != $url_pagina_link) {
$lista_altri_domini .= "<b><a href=\"$altro_dominio\" target=\"_blank\">$altro_dominio</a></b>";
if ($num1 == 0 or $dom_https) $lista_altri_domini .= " (".mex2("sicuro",$pag,$lingua_orig).")";
$lista_altri_domini .= "<br>";
} # fine if ($altro_dominio != $url_pagina_link)
} # fine for $num1
if ($lista_altri_domini) echo "<br><br>".mex2("Indirizzi alternativi da cui la pagina è raggiungibile",$pag,$lingua_orig).":<br><div class=\"linhbox\">$lista_altri_domini</div>";
} # fine if ($altri_domini)
} # fine if (defined("C_FILE_DOMINIO") and C_FILE_DOMINIO != "" and (!defined('C_NASCONDI_MARCA') or C_NASCONDI_MARCA != "SI") and...

} # fine function mostra_indirizzi_alernativi





?>