<?php

##################################################################################
#    HOTELDRUID
#    Copyright (C) 2001-2019 by Marco Maria Francesco De Santis (marco@digitaldruid.net)
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

$pag = "crearegole.php";
$titolo = "HotelDruid: Crea Regole";

include("./costanti.php");
include(C_DATI_PATH."/dati_connessione.php");
include("./includes/funzioni_$PHPR_DB_TYPE.php");
$numconnessione = connetti_db($PHPR_DB_NAME,$PHPR_DB_HOST,$PHPR_DB_PORT,$PHPR_DB_USER,$PHPR_DB_PASS,$PHPR_LOAD_EXT);
include("./includes/funzioni.php");
$tableregole = $PHPR_TAB_PRE."regole".$anno;
$tableperiodi = $PHPR_TAB_PRE."periodi".$anno;
$tablenometariffe = $PHPR_TAB_PRE."ntariffe".$anno;
$tableappartamenti = $PHPR_TAB_PRE."appartamenti";
$tableutenti = $PHPR_TAB_PRE."utenti";
$tableprenota = $PHPR_TAB_PRE."prenota".$anno;
$tabletransazioni = $PHPR_TAB_PRE."transazioni";
$tableversioni = $PHPR_TAB_PRE."versioni";


$id_utente = controlla_login($numconnessione,$PHPR_TAB_PRE,$id_sessione,$nome_utente_phpr,$password_phpr,$anno);
if ($id_utente) {

if ($id_utente != 1) {
$tableprivilegi = $PHPR_TAB_PRE."privilegi";
$tablerelgruppi = $PHPR_TAB_PRE."relgruppi";
$privilegi_annuali_utente = esegui_query("select * from $tableprivilegi where idutente = '$id_utente' and anno = '$anno'");
if (numlin_query($privilegi_annuali_utente) == 0) $anno_utente_attivato = "NO";
else {
$anno_utente_attivato = "SI";
$privilegi_globali_utente = esegui_query("select * from $tableprivilegi where idutente = '$id_utente' and anno = '1'");
$priv_ins_clienti = risul_query($privilegi_globali_utente,0,'priv_ins_clienti');
$vedi_clienti = "NO";
if (substr($priv_ins_clienti,2,1) == "s") $vedi_clienti = "SI";
if (substr($priv_ins_clienti,2,1) == "p") $vedi_clienti = "PROPRI";
if (substr($priv_ins_clienti,2,1) == "g") { $vedi_clienti = "GRUPPI"; $prendi_gruppi = "SI"; }
$priv_ins_prenota = risul_query($privilegi_annuali_utente,0,'priv_ins_prenota');
$priv_ins_periodi_passati = substr($priv_ins_prenota,8,1);
$priv_mod_prenota = risul_query($privilegi_annuali_utente,0,'priv_mod_prenota');
$priv_mod_prenotazioni = substr($priv_mod_prenota,0,1);
if ($priv_mod_prenotazioni == "g") $prendi_gruppi = "SI";
$priv_mod_date = substr($priv_mod_prenota,1,1);
$priv_mod_assegnazione_app = substr($priv_mod_prenota,2,1);
$priv_mod_commento = substr($priv_mod_prenota,5,1);
$priv_mod_tariffa = substr($priv_mod_prenota,3,1);
$priv_mod_sconto = substr($priv_mod_prenota,6,1);
$priv_mod_caparra = substr($priv_mod_prenota,7,1);
$priv_mod_pagato = substr($priv_mod_prenota,10,1);
$priv_mod_prenota_iniziate = substr($priv_mod_prenota,11,1);
$priv_mod_prenota_ore = substr($priv_mod_prenota,12,3);
$priv_mod_checkin = substr($priv_mod_prenota,20,1);
$priv_mod_prenota_comp = substr($priv_mod_prenota,23,1);
$priv_mod_orig_prenota = substr($priv_mod_prenota,24,1);
$priv_vedi_commento = substr($priv_mod_prenota,25,1);
$priv_vedi_commenti_pers = substr($priv_mod_prenota,26,1);
$priv_ins_tariffe = risul_query($privilegi_annuali_utente,0,'priv_ins_tariffe');
$priv_ins_costi_agg = substr($priv_ins_tariffe,1,1);
$priv_mod_reg1 = substr($priv_ins_tariffe,4,1);
$priv_mod_reg2 = substr($priv_ins_tariffe,5,1);
$priv_prenota_gruppi = "NO";
$priv_app_gruppi = "NO";
$regole1_consentite = risul_query($privilegi_annuali_utente,0,'regole1_consentite');
$attiva_regole1_consentite = substr($regole1_consentite,0,1);
if ($attiva_regole1_consentite != "n") $regole1_consentite = explode("#@^",substr($regole1_consentite,3));
$tariffe_consentite = risul_query($privilegi_annuali_utente,0,'tariffe_consentite');
$attiva_tariffe_consentite = substr($tariffe_consentite,0,1);
if ($attiva_tariffe_consentite == "s") {
$tariffe_consentite = explode(",",substr($tariffe_consentite,2));
unset($tariffe_consentite_vett);
for ($num1 = 0 ; $num1 < count($tariffe_consentite) ; $num1++) if ($tariffe_consentite[$num1]) $tariffe_consentite_vett[$tariffe_consentite[$num1]] = "SI";
} # fine if ($attiva_tariffe_consentite == "s")
$priv_ins_nuove_prenota = substr($priv_ins_prenota,0,1);
$priv_ins_assegnazione_app = substr($priv_ins_prenota,1,1);
$priv_mod_prenotazioni_v = $priv_mod_prenotazioni;
} # fine else if (numlin_query($privilegi_annuali_utente) == 0)

/*if ($priv_app_gruppi == "SI") {
$attiva_regole1_consentite_gr[$id_utente] = $attiva_regole1_consentite;
$regole1_consentite_gr[$id_utente] = $regole1_consentite;
$attiva_tariffe_consentite_gr[$id_utente] = $attiva_tariffe_consentite;
$tariffe_consentite_vett_gr[$id_utente] = $tariffe_consentite_vett;
$priv_ins_nuove_prenota_gr[$id_utente] = $priv_ins_nuove_prenota;
$priv_ins_assegnazione_app_gr[$id_utente] = $priv_ins_assegnazione_app;
$priv_mod_prenotazioni_gr[$id_utente] = $priv_mod_prenotazioni;
$priv_mod_assegnazione_app_gr[$id_utente] = $priv_mod_assegnazione_app;
} # fine if ($priv_app_gruppi == "SI")
unset($utenti_gruppi);
$utenti_gruppi[$id_utente] = 1;
if ($prendi_gruppi == "SI") {
$gruppi_utente = esegui_query("select idgruppo from $tablerelgruppi where idutente = '$id_utente' and idgruppo is not NULL ");
$num_gruppi_utente = numlin_query($gruppi_utente);
for ($num1 = 0 ; $num1 < $num_gruppi_utente ; $num1++) {
$idgruppo = risul_query($gruppi_utente,$num1,'idgruppo');
$utenti_gruppo = esegui_query("select idutente from $tablerelgruppi where idgruppo = '$idgruppo' ");
$num_utenti_gruppo = numlin_query($utenti_gruppo);
for ($num2 = 0 ; $num2 < $num_utenti_gruppo ; $num2++) {
$idutente_gruppo = risul_query($utenti_gruppo,$num2,'idutente');
if ($idutente_gruppo != $id_utente and !$utenti_gruppi[$idutente_gruppo]) {
$utenti_gruppi[$idutente_gruppo] = 1;

if ($priv_app_gruppi == "SI") {
$priv_anno_ut_gr = esegui_query("select * from $tableprivilegi where idutente = '$idutente_gruppo' and anno = '$anno'");
if (numlin_query($priv_anno_ut_gr) == 1) {
$regole1_consentite_gr[$idutente_gruppo] = risul_query($priv_anno_ut_gr,0,'regole1_consentite');
$attiva_regole1_consentite_gr[$idutente_gruppo] = substr($regole1_consentite_gr[$idutente_gruppo],0,1);
if ($attiva_regole1_consentite_gr[$idutente_gruppo] != "n") $regole1_consentite_gr[$idutente_gruppo] = explode("#@^",substr($regole1_consentite_gr[$idutente_gruppo],3));
$tariffe_consentite_tmp = risul_query($priv_anno_ut_gr,0,'tariffe_consentite');
$attiva_tariffe_consentite_gr[$idutente_gruppo] = substr($tariffe_consentite_tmp,0,1);
if ($attiva_tariffe_consentite_gr[$idutente_gruppo] == "s") {
$tariffe_consentite_tmp = explode(",",substr($tariffe_consentite_tmp,2));
$tariffe_consentite_vett_gr[$idutente_gruppo] = "";
for ($num3 = 0 ; $num3 < count($tariffe_consentite_tmp) ; $num3++) if ($tariffe_consentite_tmp[$num3]) $tariffe_consentite_vett_gr[$idutente_gruppo][$tariffe_consentite_tmp[$num3]] = "SI";
} # fine if ($attiva_tariffe_consentite_gr[$idutente_gruppo] == "s")
$costi_agg_consentiti_tmp = risul_query($priv_anno_ut_gr,0,"costi_agg_consentiti");
$attiva_costi_agg_consentiti_tmp = substr($costi_agg_consentiti_tmp,0,1);
if ($attiva_costi_agg_consentiti_tmp == "n") $attiva_costi_agg_consentiti_gr = "n";
if ($attiva_costi_agg_consentiti_gr == "s") {
$costi_agg_consentiti_tmp = explode(",",substr($costi_agg_consentiti_tmp,2));
for ($num3 = 0 ; $num3 < count($costi_agg_consentiti_tmp) ; $num3++) if ($costi_agg_consentiti_tmp[$num3]) $costi_agg_consentiti_vett_gr[$costi_agg_consentiti_tmp[$num3]] = "SI";
} # fine if ($attiva_costi_agg_consentiti_gr == "s")
$priv_ins_prenota_tmp = risul_query($priv_anno_ut_gr,0,'priv_ins_prenota');
$priv_ins_nuove_prenota_gr[$idutente_gruppo] = substr($priv_ins_prenota_tmp,0,1);
$priv_ins_assegnazione_app_gr[$idutente_gruppo] = substr($priv_ins_prenota_tmp,1,1);
$priv_mod_prenota_tmp = risul_query($priv_anno_ut_gr,0,'priv_mod_prenota');
$priv_mod_prenotazioni_gr[$idutente_gruppo] = substr($priv_mod_prenota_tmp,0,1);
$priv_mod_assegnazione_app_gr[$idutente_gruppo] = substr($priv_mod_prenota_tmp,2,1);
} # fine if (numlin_query($priv_anno_ut_gr) == 1)
else {
$priv_ins_nuove_prenota_gr[$idutente_gruppo] = "n";
$priv_mod_prenotazioni_gr[$idutente_gruppo] = "n";
} # fine else if (numlin_query($priv_anno_ut_gr) == 1)
} # fine if ($priv_app_gruppi == "SI")

} # fine if ($idutente_gruppo != $id_utente)
} # fine for $num2
} # fine for $num1
} # fine if ($prendi_gruppi == "SI")*/

} # fine if ($id_utente != 1)
else {
$anno_utente_attivato = "SI";
$vedi_clienti = "SI";
$priv_ins_periodi_passati = "s";
$priv_mod_prenotazioni = "s";
$priv_mod_date = "s";
$priv_mod_assegnazione_app = "s";
$priv_mod_commento = "s";
$priv_mod_sconto = "s";
$priv_mod_caparra = "s";
$priv_mod_pagato = "s";
$priv_mod_prenota_iniziate = "s";
$priv_mod_prenota_ore = "000";
$priv_mod_checkin = "s";
$priv_mod_prenota_comp = "s";
$priv_mod_orig_prenota = "s";
$priv_vedi_commento = "s";
$priv_vedi_commenti_pers = "s";
$attiva_tariffe_consentite = "n";
$attiva_regole1_consentite = "n";
$priv_ins_costi_agg = "s";
$priv_mod_reg1 = "s";
$priv_mod_reg2 = "s";
} # fine else if ($id_utente != 1)
if ($anno_utente_attivato == "SI" and ($priv_mod_reg1 != "n" or $priv_mod_reg2 != "n")) {

if ($priv_vedi_commenti_pers = "s") $priv_mod_commenti_pers = "s";
else $priv_mod_commenti_pers = "n";

if (@is_file(C_DATI_PATH."/dati_subordinazione.php")) {
$installazione_subordinata = "SI";
$inserimento_nuovi_clienti = "NO";
$modifica_clienti = "NO";
$priv_ins_nuove_prenota = "n";
$priv_mod_date = "n";
$priv_mod_assegnazione_app = "n";
$priv_mod_commento = "n";
$priv_mod_commenti_pers = "n";
$priv_mod_sconto = "n";
$priv_mod_caparra = "n";
$priv_mod_pagato = "n";
$priv_mod_checkin = "n";
$priv_mod_prenota_comp = "n";
$priv_mod_orig_prenota = "n";
$priv_ins_spese = "n";
$priv_ins_entrate = "n";
$priv_ins_costi_agg = "n";
$priv_mod_reg1 = "n";
$priv_mod_reg2 = "n";
} # fine if (@is_file(C_DATI_PATH."/dati_subordinazione.php"))



$titolo = "HotelDruid: ".mex("Crea Regole",$pag);
if ($tema[$id_utente] and $tema[$id_utente] != "base" and @is_dir("./themes/".$tema[$id_utente]."/php")) include("./themes/".$tema[$id_utente]."/php/head.php");
else include("./includes/head.php");


$stile_data = stile_data();

$appartamenti = esegui_query("select * from $tableappartamenti order by idappartamenti");
$num_appartamenti = numlin_query($appartamenti);
include("./includes/funzioni_appartamenti.php");
#if ($priv_app_gruppi != "SI") $appartamenti_consentiti = trova_app_consentiti($appartamenti,$num_appartamenti,$attiva_regole1_consentite,$regole1_consentite,$priv_mod_assegnazione_app,$priv_mod_prenotazioni,$priv_ins_assegnazione_app,$priv_ins_nuove_prenota,$attiva_tariffe_consentite,$tariffe_consentite_vett,$id_utente,$tableregole,$tablenometariffe);
#else $appartamenti_consentiti = trova_app_consentiti($appartamenti,$num_appartamenti,$attiva_regole1_consentite_gr,$regole1_consentite_gr,$priv_mod_assegnazione_app_gr,$priv_mod_prenotazioni_gr,$priv_ins_assegnazione_app_gr,$priv_ins_nuove_prenota_gr,$attiva_tariffe_consentite_gr,$tariffe_consentite_vett_gr,$id_utente,$tableregole,$tablenometariffe);
$appartamenti_consentiti = trova_app_consentiti($appartamenti,$num_appartamenti,$attiva_regole1_consentite,$regole1_consentite,$priv_mod_assegnazione_app,$priv_mod_prenotazioni,$priv_ins_assegnazione_app,$priv_ins_nuove_prenota,$attiva_tariffe_consentite,$tariffe_consentite_vett,$id_utente,$tableregole,$tablenometariffe);
$tutti_app_consentiti = 1;
if ($id_utente != 1) {
for ($num1 = 0 ; $num1 < $num_appartamenti ; $num1++) {
if ($appartamenti_consentiti[risul_query($appartamenti,$num1,'idappartamenti')] != "SI") {
$tutti_app_consentiti = 0;
break;
} # fine if ($appartamenti_consentiti[risul_query($appartamenti,$num1,'idappartamenti')] != "SI") 
} # fine for $num1
} # fine if ($id_utente != 1)



if ($inserisci) {
$aggiorna_ic_disp = 0;
$aggiorna_ic_tar = 0;
$tabelle_lock = array($tableregole);
$altre_tab_lock = array($tablenometariffe,$tableperiodi,$tableappartamenti,$tableutenti);
$tabelle_lock = lock_tabelle($tabelle_lock,$altre_tab_lock);


if ($regola_1 and ($priv_mod_reg1 == "s" or $priv_mod_reg1 == "a")) {
$regola_1_tar = "";
if (!$inizioperiodo or !$fineperiodo or !$appartamento) {
echo mex("Non sono stati inseriti tutti i dati necessari",$pag)."!<br>";
} # fine if (!$inizioperiodo or !$fineperiodo or !$appartamento)
else {
$idinizioperiodo = esegui_query("select idperiodi from $tableperiodi where datainizio = '".aggslashdb($inizioperiodo)."' ");
if (numlin_query($idinizioperiodo) == 0) $idinizioperiodo = 9999999;
else $idinizioperiodo = risul_query($idinizioperiodo,0,'idperiodi');
$idfineperiodo = esegui_query("select idperiodi from $tableperiodi where datafine = '".aggslashdb($fineperiodo)."' ");
if (numlin_query($idfineperiodo) == 0) $idfineperiodo = -1;
else $idfineperiodo = risul_query($idfineperiodo,0,'idperiodi');
if ($idfineperiodo < $idinizioperiodo) {
echo mex("Le date sono sbagliate",$pag).".<br>";
} # fine if ($idfineperiodo < $idinizioperiodo)
else {
if ($motivazione == " ") echo mex("Motivazione non valida",$pag).".<br>";
else {
$appartamento = htmlspecialchars($appartamento);
$app_esistente = esegui_query("select idappartamenti from $tableappartamenti where idappartamenti = '".aggslashdb($appartamento)."' ");
if (!numlin_query($app_esistente) or $appartamenti_consentiti[$appartamento] != "SI") echo mex("Non sono stati inseriti tutti i dati necessari",$pag).".<br>";
else {
if ($mod_idregola1 and controlla_num_pos($mod_idregola1) == "SI") {
$reg_esist = esegui_query("select * from $tableregole where idregole = '$mod_idregola1' ");
if (numlin_query($reg_esist)) {
$app_agenzia = risul_query($reg_esist,0,'app_agenzia');
if (!$app_agenzia) $mod_idregola1 = "";
} # fine if (numlin_query($reg_esist))
else $mod_idregola1 = "";
} # fine if ($mod_idregola1 and controlla_num_pos($mod_idregola1) == "SI")
else $mod_idregola1 = "";
if ($mod_idregola1) $cond_reg_mod = " and idregole != '$mod_idregola1'";
else $cond_reg_mod = "";
$vecchia_regola = esegui_query("select * from $tableregole where app_agenzia = '".aggslashdb($appartamento)."' and iddatainizio <= '$idfineperiodo' and iddatafine >= '$idinizioperiodo'$cond_reg_mod order by iddatainizio");
$num_vecchia_regola = numlin_query($vecchia_regola);
if ($attiva_regole1_consentite != "n") $cancella_vecchie_regole = "";
if ($num_vecchia_regola != 0 and !$cancella_vecchie_regole) {
echo mex("Esiste già una regola di questo tipo nell'appartamento e nel periodo selezionato",'unit.php')." (".formatta_data($inizioperiodo,$stile_data)." ".mex("al",$pag)." ".formatta_data($fineperiodo,$stile_data).").<br><br>
<form accept-charset=\"utf-8\" method=\"post\" action=\"crearegole.php\"><div class=\"linhbox\">
<input type=\"hidden\" name=\"anno\" value=\"$anno\">
<input type=\"hidden\" name=\"id_sessione\" value=\"$id_sessione\">
<input type=\"hidden\" name=\"inserisci\" value=\"SI\">
<input type=\"hidden\" name=\"regola_1\" value=\"1\">
<input type=\"hidden\" name=\"mod_idregola1\" value=\"$mod_idregola1\">
<input type=\"hidden\" name=\"origine\" value=\"".htmlspecialchars($origine)."\">
<input type=\"hidden\" name=\"cancella_vecchie_regole\" value=\"1\">
<input type=\"hidden\" name=\"chiudi_app\" value=\"".htmlspecialchars($chiudi_app)."\">
<input type=\"hidden\" name=\"appartamento\" value=\"$appartamento\">
<input type=\"hidden\" name=\"inizioperiodo\" value=\"".htmlspecialchars($inizioperiodo)."\">
<input type=\"hidden\" name=\"fineperiodo\" value=\"".htmlspecialchars($fineperiodo)."\">
<input type=\"hidden\" name=\"motivazione\" value=\"".htmlspecialchars($motivazione)."\">
<em>".mex("Regole esistenti",$pag)."</em>:<br>";
for ($num1 = 0 ; $num1 < $num_vecchia_regola ; $num1++) {
$idregola_v = risul_query($vecchia_regola,$num1,'idregole');
$iddatainizio_v = risul_query($vecchia_regola,$num1,'iddatainizio');
$iddatafine_v = risul_query($vecchia_regola,$num1,'iddatafine');
$motivazione_v = risul_query($vecchia_regola,$num1,'motivazione');
$motivazione2_v = risul_query($vecchia_regola,$num1,'motivazione2');
$datainizio_v = esegui_query("select datainizio from $tableperiodi where idperiodi = '$iddatainizio_v' ");
$datainizio_v = formatta_data(risul_query($datainizio_v,0,'datainizio'),$stile_data);
$datafine_v = esegui_query("select datafine from $tableperiodi where idperiodi = '$iddatafine_v' ");
$datafine_v = formatta_data(risul_query($datafine_v,0,'datafine'),$stile_data);
echo ($num1 + 1)."- ";
if ($motivazione2_v == "x") echo mex("Chiudi",$pag);
else echo mex("Chiedi prima di assegnare",$pag);
echo " ".mex("l'appartamento",'unit.php')." $appartamento ".mex("nel periodo dal",$pag)." $datainizio_v ".mex("al",$pag)." $datafine_v";
if (strcmp(trim($motivazione_v),"")) echo " (<em>$motivazione_v</em>)";
if ($attiva_regole1_consentite == "n") {
echo ".<select name=\"creg$idregola_v\">";
if ($iddatainizio_v < $idinizioperiodo or $iddatafine_v > $idfineperiodo) echo "<option value=\"\" selected>".mex("Ridimensiona",$pag)."</option>";
echo "<option value=\"1\">".mex("Cancella",$pag)."</option></select>";
} # fine if ($attiva_regole1_consentite == "n")
echo "<br>";
} # fine for $num1
if ($attiva_regole1_consentite == "n") echo "<button class=\"cont\" type=\"submit\"><div>".mex("Cancella o ridimensiona queste regole",$pag)."</div></button>";
echo "<br><br><br></div></form>";
} # fine if ($num_vecchia_regola != 0 and !$cancella_vecchie_regole)
else {
$idregole = esegui_query("select max(idregole) from $tableregole");
$idregole = risul_query($idregole,0,0);
$idregole = $idregole + 1;
for ($num1 = 0 ; $num1 < $num_vecchia_regola ; $num1++) {
$idregola_v = risul_query($vecchia_regola,$num1,'idregole');
$iddatainizio_v = risul_query($vecchia_regola,$num1,'iddatainizio');
$iddatafine_v = risul_query($vecchia_regola,$num1,'iddatafine');
if (($iddatainizio_v >= $idinizioperiodo and $iddatafine_v <= $idfineperiodo) or ${"creg$idregola_v"}) esegui_query("delete from $tableregole where idregole = '$idregola_v' ");
else {
if ($iddatainizio_v >= $idinizioperiodo) esegui_query("update $tableregole set iddatainizio = '".($idfineperiodo + 1)."' where idregole = '$idregola_v' ");
else {
esegui_query("update $tableregole set iddatafine = '".($idinizioperiodo - 1)."' where idregole = '$idregola_v' ");
if ($iddatafine_v > $idfineperiodo) {
$motivazione_v = risul_query($vecchia_regola,$num1,'motivazione');
$motivazione2_v = risul_query($vecchia_regola,$num1,'motivazione2');
esegui_query("insert into $tableregole (idregole,app_agenzia,iddatainizio,iddatafine) values ($idregole,'".aggslashdb($appartamento)."','".($idfineperiodo + 1)."','$iddatafine_v')");
if ($motivazione2_v == "x") esegui_query("update $tableregole set motivazione2 = 'x' where idregole = '$idregole' ");
if ($motivazione_v) esegui_query("update $tableregole set motivazione = '".aggslashdb($motivazione_v)."' where idregole = '$idregole' ");
$idregole++;
} # fine if ($iddatafine_v > $idfineperiodo)
} # fine else if ($iddatainizio_v >= $idinizioperiodo)
} # fine else if (($iddatainizio_v >= $idinizioperiodo and $iddatafine_v <= $idfineperiodo) or ${"creg$idregola_v"})
} # fine for $num1
if ($mod_idregola1) {
esegui_query("delete from $tableregole where idregole = '$mod_idregola1' ");
$idregole = $mod_idregola1;
} # fine if ($mod_idregola1)
esegui_query("insert into $tableregole (idregole,app_agenzia,iddatainizio,iddatafine) values ($idregole,'".aggslashdb($appartamento)."','$idinizioperiodo','$idfineperiodo')");
if (@get_magic_quotes_gpc()) $motivazione = stripslashes($motivazione);
$motivazione = htmlspecialchars($motivazione);
if ($motivazione) esegui_query("update $tableregole set motivazione = '".aggslashdb($motivazione)."' where idregole = '$idregole' ");
if ($attiva_regole1_consentite != "n") $chiudi_app = 1;
if ($chiudi_app) {
esegui_query("update $tableregole set motivazione2 = 'x' where idregole = '$idregole' ");
unlock_tabelle($tabelle_lock);
include("./includes/liberasettimane.php");
$tabelle_lock = array($tableprenota);
$altre_tab_lock = array($tableperiodi,$tableappartamenti,$tableregole,$tablepersonalizza);
$tabelle_lock = lock_tabelle($tabelle_lock,$altre_tab_lock);

unset($limiti_var);
unset($profondita);
unset($dati_app);
$limiti_var['idperiodocorrente'] = calcola_id_periodo_corrente($anno);
if ($idinizioperiodo > $limiti_var['idperiodocorrente']) $n_ini = $idinizioperiodo;
else {
if (($limiti_var['idperiodocorrente'] + 1) <= $idfineperiodo) $n_ini = ($limiti_var['idperiodocorrente'] + 1);
else $n_ini = $limiti_var['idperiodocorrente'];
} # fine else if ($idinizioperiodo > $limiti_var['idperiodocorrente'])
$limiti_var['n_ini'] = $n_ini;
$limiti_var['n_fine'] = $idfineperiodo;
$profondita['iniziale'] = "";
$profondita['attuale'] = 1;
$max_prenota = esegui_query("select max(idprenota) from $tableprenota");
$tot_prenota = risul_query($max_prenota,0,0);
$profondita['tot_prenota_ini'] = $tot_prenota;
$profondita['tot_prenota_attuale'] = $tot_prenota;
tab_a_var($limiti_var,$app_prenota_id,$app_orig_prenota_id,$inizio_prenota_id,$fine_prenota_id,$app_assegnabili_id,$prenota_in_app_sett,$anno,$dati_app,$profondita,$PHPR_TAB_PRE."prenota");
$fatto_libera = "";
$app_agenzia = esegui_query("select * from $tableregole where app_agenzia != '' and idregole != '$idregole' ");
$num_app_agenzia = numlin_query($app_agenzia);
if ($num_app_agenzia != 0) {
$info_periodi_ag['numero'] = $num_app_agenzia;
for ($num1 = 0 ; $num1 < $num_app_agenzia ; $num1++) {
$info_periodi_ag['app'][$num1] = risul_query($app_agenzia,$num1,'app_agenzia');
$info_periodi_ag['ini'][$num1] = risul_query($app_agenzia,$num1,'iddatainizio');
$info_periodi_ag['fine'][$num1] = risul_query($app_agenzia,$num1,'iddatafine');
} # fine for $num1
inserisci_prenota_fittizie($info_periodi_ag,$profondita,$app_prenota_id,$inizio_prenota_id,$fine_prenota_id,$prenota_in_app_sett,$app_assegnabili_id);
} # fine if ($num_app_agenzia != 0)
unset($app_richiesti);
$app_richiesti[',numero,'] = 1;
$app_richiesti[1] = $appartamento;
$idinizioperiodo_vett[1] = $n_ini;
$idfineperiodo_vett[1] = $idfineperiodo;
liberasettimane($idinizioperiodo_vett,$idfineperiodo_vett,$limiti_var,$anno,$fatto_libera,$app_liberato_vett,$profondita,$app_richiesti,$app_prenota_id,$app_orig_prenota_id,$inizio_prenota_id,$fine_prenota_id,$app_assegnabili_id,$prenota_in_app_sett,$dati_app,$PHPR_TAB_PRE."prenota");
if ($fatto_libera != "SI") $risul_agg = 0;
else $risul_agg = aggiorna_tableprenota($app_prenota_id,$app_orig_prenota_id,$tableprenota);
if ($risul_agg) {
echo mex("Il periodo chiuso è stato liberato dalle prenotazioni",$pag).".<br>";
} # fine if ($risul_agg)
else echo mex("<span class=\"colred\">Non è stato possibile</span> liberare dalle prenotazioni il periodo chiuso",$pag).".<br>";
unlock_tabelle($tabelle_lock);
unset($tabelle_lock);
$aggiorna_ic_disp = 1;
} # fine if ($chiudi_app)
echo mex("La regola è stata inserita",$pag).".";
$tabelle_lock = array($tableversioni,$tabletransazioni);
$tabelle_lock = lock_tabelle($tabelle_lock);
$ultimo_accesso = date("Y-m-d H:i:s",(time() + (C_DIFF_ORE * 3600)));
$transaz_esistente = esegui_query("select idtransazioni from $tabletransazioni where idsessione = '$id_sessione' and tipo_transazione = 'cr_1a' ");
if (numlin_query($transaz_esistente)) {
$transaz_esistente = risul_query($transaz_esistente,0,'idtransazioni');
esegui_query("update $tabletransazioni set dati_transazione1 = '".aggslashdb($inizioperiodo)."', dati_transazione2 = '".aggslashdb($fineperiodo)."', dati_transazione3 = '".aggslashdb($motivazione)."', dati_transazione4 = '".aggslashdb($chiudi_app)."', ultimo_accesso = '$ultimo_accesso' where idtransazioni = '$transaz_esistente' ");
} # fine if (numlin_query($transaz_esistente))
else {
$adesso = date("YmdHis",(time() + (C_DIFF_ORE * 3600)));
list($usec, $sec) = explode(' ', microtime());
mt_srand((float) $sec + ((float) $usec * 100000));
$val_casuale = mt_rand(100000,999999);
$versione_transazione = prendi_numero_versione($tableversioni);
$id_transazione = $adesso.$val_casuale.$versione_transazione;
esegui_query("insert into $tabletransazioni (idtransazioni,idsessione,tipo_transazione,anno,dati_transazione1,dati_transazione2,dati_transazione3,dati_transazione4,ultimo_accesso) 
values ('$id_transazione','$id_sessione','cr_1a','$anno','".aggslashdb($inizioperiodo)."','".aggslashdb($fineperiodo)."','".aggslashdb($motivazione)."','".aggslashdb($chiudi_app)."','$ultimo_accesso')");
} # fine else if (numlin_query($transaz_esistente))
unlock_tabelle($tabelle_lock);
} # fine else if ($num_vecchia_regola != 0 and !$cancella_vecchie_regole)
} # fine else if (!numlin_query($app_esistente))
} # fine else if ($motivazione == " ")
} # fine else if ($idfineperiodo < $idinizioperiodo)
} # fine else if (!$inizioperiodo or !$fineperiodo or !$appartamento)
} # fine if ($regola_1 and ($priv_mod_reg1 == "s" or $priv_mod_reg1 == "a"))


if ($regola_1_tar and ($priv_mod_reg1 == "s" or $priv_mod_reg1 == "t")) {
if (!$inizioperiodo or !$fineperiodo or !$tipotariffa or substr($tipotariffa,0,7) != "tariffa" or controlla_num_pos(substr($tipotariffa,7)) == "NO" or ($attiva_tariffe_consentite != "n" and $tariffe_consentite_vett[substr($tipotariffa,7)] != "SI")) {
echo mex("Non sono stati inseriti tutti i dati necessari",$pag)."!<br>";
} # fine if (!$inizioperiodo or !$fineperiodo or !$tipotariffa or...
else {
$idinizioperiodo = esegui_query("select idperiodi from $tableperiodi where datainizio = '".aggslashdb($inizioperiodo)."' ");
if (numlin_query($idinizioperiodo) == 0) $idinizioperiodo = 9999999;
else $idinizioperiodo = risul_query($idinizioperiodo,0,'idperiodi');
$idfineperiodo = esegui_query("select idperiodi from $tableperiodi where datafine = '".aggslashdb($fineperiodo)."' ");
if (numlin_query($idfineperiodo) == 0) $idfineperiodo = -1;
else $idfineperiodo = risul_query($idfineperiodo,0,'idperiodi');
if ($idfineperiodo < $idinizioperiodo) {
echo mex("Le date sono sbagliate",$pag).".<br>";
} # fine if ($idfineperiodo < $idinizioperiodo)
else {
if ($motivazione == " ") echo mex("Motivazione non valida",$pag).".<br>";
else {
if ($mod_idregola1 and controlla_num_pos($mod_idregola1) == "SI") {
$reg_esist = esegui_query("select * from $tableregole where idregole = '$mod_idregola1' ");
if (numlin_query($reg_esist)) {
$tariffa_chiusa = risul_query($reg_esist,0,'tariffa_chiusa');
if (!$tariffa_chiusa) $mod_idregola1 = "";
} # fine if (numlin_query($reg_esist))
else $mod_idregola1 = "";
} # fine if ($mod_idregola1 and controlla_num_pos($mod_idregola1) == "SI")
else $mod_idregola1 = "";
if ($mod_idregola1) $cond_reg_mod = " and idregole != '$mod_idregola1'";
else $cond_reg_mod = "";
$vecchia_regola = esegui_query("select * from $tableregole where tariffa_chiusa = '$tipotariffa' and iddatainizio <= '$idfineperiodo' and iddatafine >= '$idinizioperiodo'$cond_reg_mod order by iddatainizio ");
$num_vecchia_regola = numlin_query($vecchia_regola);
if ($num_vecchia_regola != 0 and !$cancella_vecchie_regole) {
$tariffa_vedi = mex("tariffa","prenota.php").substr($tipotariffa,7);
echo mex("Esiste già una regola di questo tipo nel periodo selezionato",$pag)." (".formatta_data($inizioperiodo,$stile_data)." ".mex("al",$pag)." ".formatta_data($fineperiodo,$stile_data).").<br><br>
<form accept-charset=\"utf-8\" method=\"post\" action=\"crearegole.php\"><div class=\"linhbox\">
<input type=\"hidden\" name=\"anno\" value=\"$anno\">
<input type=\"hidden\" name=\"id_sessione\" value=\"$id_sessione\">
<input type=\"hidden\" name=\"inserisci\" value=\"SI\">
<input type=\"hidden\" name=\"regola_1_tar\" value=\"1\">
<input type=\"hidden\" name=\"mod_idregola1\" value=\"$mod_idregola1\">
<input type=\"hidden\" name=\"origine\" value=\"".htmlspecialchars($origine)."\">
<input type=\"hidden\" name=\"cancella_vecchie_regole\" value=\"1\">
<input type=\"hidden\" name=\"tipotariffa\" value=\"$tipotariffa\">
<input type=\"hidden\" name=\"inizioperiodo\" value=\"".htmlspecialchars($inizioperiodo)."\">
<input type=\"hidden\" name=\"fineperiodo\" value=\"".htmlspecialchars($fineperiodo)."\">
<input type=\"hidden\" name=\"motivazione\" value=\"".htmlspecialchars($motivazione)."\">
<em>".mex("Regole esistenti",$pag)."</em>:<br>";
for ($num1 = 0 ; $num1 < $num_vecchia_regola ; $num1++) {
$idregola_v = risul_query($vecchia_regola,$num1,'idregole');
$iddatainizio_v = risul_query($vecchia_regola,$num1,'iddatainizio');
$iddatafine_v = risul_query($vecchia_regola,$num1,'iddatafine');
$motivazione_v = risul_query($vecchia_regola,$num1,'motivazione');
$datainizio_v = esegui_query("select datainizio from $tableperiodi where idperiodi = '$iddatainizio_v' ");
$datainizio_v = formatta_data(risul_query($datainizio_v,0,'datainizio'),$stile_data);
$datafine_v = esegui_query("select datafine from $tableperiodi where idperiodi = '$iddatafine_v' ");
$datafine_v = formatta_data(risul_query($datafine_v,0,'datafine'),$stile_data);
echo ($num1 + 1)."- ".mex("Chiudi",$pag)." $tariffa_vedi ".mex("nel periodo dal",$pag)." $datainizio_v ".mex("al",$pag)." $datafine_v";
if (strcmp(trim($motivazione_v),"")) echo " (<em>$motivazione_v</em>)";
echo ".<select name=\"creg$idregola_v\">";
if ($iddatainizio_v < $idinizioperiodo or $iddatafine_v > $idfineperiodo) echo "<option value=\"\" selected>".mex("Ridimensiona",$pag)."</option>";
echo "<option value=\"1\">".mex("Cancella",$pag)."</option>
</select><br>";
} # fine for $num1
echo "<button class=\"cont\" type=\"submit\"><div>".mex("Cancella o ridimensiona queste regole",$pag)."</div></button>
<br><br><br></div></form>";
} # fine if ($num_vecchia_regola != 0 and !$cancella_vecchie_regole)
else {
$idregole = esegui_query("select max(idregole) from $tableregole");
$idregole = risul_query($idregole,0,0);
$idregole = $idregole + 1;
for ($num1 = 0 ; $num1 < $num_vecchia_regola ; $num1++) {
$idregola_v = risul_query($vecchia_regola,$num1,'idregole');
$iddatainizio_v = risul_query($vecchia_regola,$num1,'iddatainizio');
$iddatafine_v = risul_query($vecchia_regola,$num1,'iddatafine');
if (($iddatainizio_v >= $idinizioperiodo and $iddatafine_v <= $idfineperiodo) or ${"creg$idregola_v"}) esegui_query("delete from $tableregole where idregole = '$idregola_v' ");
else {
if ($iddatainizio_v >= $idinizioperiodo) esegui_query("update $tableregole set iddatainizio = '".($idfineperiodo + 1)."' where idregole = '$idregola_v' ");
else {
esegui_query("update $tableregole set iddatafine = '".($idinizioperiodo - 1)."' where idregole = '$idregola_v' ");
if ($iddatafine_v > $idfineperiodo) {
$motivazione_v = risul_query($vecchia_regola,$num1,'motivazione');
esegui_query("insert into $tableregole (idregole,tariffa_chiusa,iddatainizio,iddatafine) values ($idregole,'$tipotariffa','".($idfineperiodo + 1)."','$iddatafine_v')");
if ($motivazione_v) esegui_query("update $tableregole set motivazione = '".aggslashdb($motivazione_v)."' where idregole = '$idregole' ");
$idregole++;
} # fine if ($iddatafine_v > $idfineperiodo)
} # fine else if ($iddatainizio_v >= $idinizioperiodo)
} # fine else if (($iddatainizio_v >= $idinizioperiodo and $iddatafine_v <= $idfineperiodo) or ${"creg$idregola_v"})
} # fine for $num1
if ($mod_idregola1) {
esegui_query("delete from $tableregole where idregole = '$mod_idregola1' ");
$idregole = $mod_idregola1;
} # fine if ($mod_idregola1)
esegui_query("insert into $tableregole (idregole,tariffa_chiusa,iddatainizio,iddatafine) values ('$idregole','$tipotariffa','$idinizioperiodo','$idfineperiodo')");
if (@get_magic_quotes_gpc()) $motivazione = stripslashes($motivazione);
$motivazione = htmlspecialchars($motivazione);
if ($motivazione) esegui_query("update $tableregole set motivazione = '".aggslashdb($motivazione)."' where idregole = '$idregole' ");
echo mex("La regola è stata inserita",$pag).".";
$aggiorna_ic_tar = 1;
$tabelle_lock = array($tableversioni,$tabletransazioni);
$tabelle_lock = lock_tabelle($tabelle_lock);
$ultimo_accesso = date("Y-m-d H:i:s",(time() + (C_DIFF_ORE * 3600)));
$transaz_esistente = esegui_query("select idtransazioni from $tabletransazioni where idsessione = '$id_sessione' and tipo_transazione = 'cr_1t' ");
if (numlin_query($transaz_esistente)) {
$transaz_esistente = risul_query($transaz_esistente,0,'idtransazioni');
esegui_query("update $tabletransazioni set dati_transazione1 = '".aggslashdb($inizioperiodo)."', dati_transazione2 = '".aggslashdb($fineperiodo)."', dati_transazione3 = '".aggslashdb($motivazione)."', ultimo_accesso = '$ultimo_accesso' where idtransazioni = '$transaz_esistente' ");
} # fine if (numlin_query($transaz_esistente))
else {
$adesso = date("YmdHis",(time() + (C_DIFF_ORE * 3600)));
list($usec, $sec) = explode(' ', microtime());
mt_srand((float) $sec + ((float) $usec * 100000));
$val_casuale = mt_rand(100000,999999);
$versione_transazione = prendi_numero_versione($tableversioni);
$id_transazione = $adesso.$val_casuale.$versione_transazione;
esegui_query("insert into $tabletransazioni (idtransazioni,idsessione,tipo_transazione,anno,dati_transazione1,dati_transazione2,dati_transazione3,ultimo_accesso) 
values ('$id_transazione','$id_sessione','cr_1t','$anno','".aggslashdb($inizioperiodo)."','".aggslashdb($fineperiodo)."','".aggslashdb($motivazione)."','$ultimo_accesso')");
} # fine else if (numlin_query($transaz_esistente))
unlock_tabelle($tabelle_lock);
} # fine else if ($num_vecchia_regola != 0 and !$cancella_vecchie_regole)
} # fine else if ($motivazione == " ")
} # fine else if ($idfineperiodo < $idinizioperiodo)
} # fine else if (!$inizioperiodo or !$fineperiodo or !$appartamento)
} # fine if ($regola_1_tar and ($priv_mod_reg1 == "s" or $priv_mod_reg1 == "t"))


if ($canc_regola_1 and $priv_mod_reg1 == "s" and $tutti_app_consentiti and $attiva_regole1_consentite == "n") {
$tutte_tar_consentite = 1;
if ($attiva_tariffe_consentite != "n") {
$rigatariffe = esegui_query("select * from $tablenometariffe where idntariffe = 1 ");
$numero_tariffe = risul_query($rigatariffe,0,'nomecostoagg');
for ($numtariffa = 1 ; $numtariffa <= $numero_tariffe ; $numtariffa++) {
if ($tariffe_consentite_vett[$numtariffa] != "SI") {
$tutte_tar_consentite = 0;
break;
} # fine if ($tariffe_consentite_vett[$numtariffa] != "SI")
} # fine for $numtariffa
} # fine if ($attiva_tariffe_consentite != "n")
if ($tutte_tar_consentite) {
if ($gia_stato) {
esegui_query("delete from $tableregole where (app_agenzia != '' and app_agenzia is not NULL) or (tariffa_chiusa != '' and tariffa_chiusa is not NULL) ");
echo mex("Le regole sono state cancellate",$pag);
$aggiorna_ic_disp = 1;
$aggiorna_ic_tar = 1;
} # fine if ($gia_stato)
else {
echo mex("Sei sicuro di voler cancellare tutte le regole del tipo 1",$pag)."?
<form accept-charset=\"utf-8\" method=\"post\" action=\"crearegole.php\"><div>
<input type=\"hidden\" name=\"anno\" value=\"$anno\">
<input type=\"hidden\" name=\"id_sessione\" value=\"$id_sessione\">
<input type=\"hidden\" name=\"inserisci\" value=\"SI\">
<input type=\"hidden\" name=\"canc_regola_1\" value=\"1\">
<button class=\"crul\" type=\"submit\" name=\"gia_stato\" value=\"1\"><div>  ".mex("SI",$pag)."  </div></button></div></form><br>";
} # fine else if ($gia_stato)
} # fine if ($tutte_tar_consentite)
} # fine if ($canc_regola_1 and $priv_mod_reg1 == "s" and $tutti_app_consentiti and $attiva_regole1_consentite == "n")


if (($regola_2 or $regola_2b) and $priv_mod_reg2 == "s") {
if (substr($tipotariffa,0,7) != "tariffa" or controlla_num_pos(substr($tipotariffa,7)) == "NO" or ($attiva_tariffe_consentite != "n" and $tariffe_consentite_vett[substr($tipotariffa,7)] != "SI")) {
$inserire = "NO";
echo "<br><span class=\"colwarn\">".mex("Si deve scegliere la tariffa",$pag)."</span>.<br><br>";
} # fine if (substr($tipotariffa,0,7) != "tariffa" or...
else {
$regola_esistente = esegui_query("select * from $tableregole where tariffa_per_app = '".aggslashdb($tipotariffa)."'");
$num_regole_esistente = numlin_query($regola_esistente);
/*
if ($num_regole_esistente != 0) {
$inserire = "NO";
echo mex("La tariffa scelta ha già degli appartamenti associati, cancella la regola prima di inserirne una nuova",'unit.php').".<br>";
} # fine if ($num_regole_esistente != 0)
*/
} # fine else if (substr($tipotariffa,0,7) != "tariffa" or...
if (!$lista_app) {
$inserire = "NO";
echo "<br><span class=\"colwarn\">".mex("Si deve inserire almeno un appartamento da associare",'unit.php')."</span>.<br><br>";
} # fine if (!$lista_app)
else {
if ($num_regole_esistente) {
if ($regola_2b) $reg_corr = risul_query($regola_esistente,0,'motivazione2');
else $reg_corr = risul_query($regola_esistente,0,'motivazione');
} # fine if ($num_regole_esistente)
else $reg_corr = "";
$appartamenti = esegui_query("select idappartamenti from $tableappartamenti");
$num_appartamenti = numlin_query($appartamenti);
$app_esistente = array();
for ($num1 = 0 ; $num1 < $num_appartamenti ; $num1 = $num1 + 1) {
$appartamento = risul_query($appartamenti,$num1,'idappartamenti');
if ($appartamenti_consentiti[$appartamento] == "SI" or strstr(",$reg_corr,",",$appartamento,")) $app_esistente[$appartamento] = "SI";
} # fine for $num1
$vett_app = explode(",",$lista_app);
$num_app = count($vett_app);
$num_app_reali = 0;
$app_trovato = array();
for ($num1 = 0 ; $num1 < $num_app ; $num1 = $num1 + 1) {
if ($app_esistente[$vett_app[$num1]] != "SI") {
$inserire = "NO";
echo "<br>".mex("L'appartamento",'unit.php')." <b class=\"colwarn\">".htmlspecialchars($vett_app[$num1])."</b>".mex(" non esiste",$pag).".<br><br>";
} # fine if ($app_esistente[$appartamento] != "SI")
else {
if ($app_trovato[$vett_app[$num1]]) $lista_app = substr(str_replace(",".$vett_app[$num1].",",",",",$lista_app,"),1,-1).",".$vett_app[$num1];
else $num_app_reali++;
$app_trovato[$vett_app[$num1]] = 1;
} # fine else if ($app_esistente[$appartamento] != "SI")
} # fine for $num1
} # fine else if (!$lista_app)
$num_da_assegnare = 1;
if ($regola_2) {
$num_da_assegnare = $num_apti;
if ($num_apti < 1 or $num_apti > $num_appartamenti) $inserire = "NO";
if ($num_regole_esistente) {
$regola_2b_esist = risul_query($regola_esistente,0,'motivazione2');
if (strcmp($regola_2b_esist,"")) {
$num_regola_2b_esist = explode(",",$regola_2b_esist);
$num_regola_2b_esist = count($num_regola_2b_esist);
if ($num_regola_2b_esist < $num_app_reali) $num_app_reali = $num_regola_2b_esist;
} # fine if if (strcmp($regola_2b_esist,""))
} # fine if ($num_regole_esistente)
} # fine if ($regola_2)
elseif ($num_regole_esistente) {
$num_da_assegnare = risul_query($regola_esistente,0,'motivazione3');
if (substr($num_da_assegnare,0,1) == "v") $num_da_assegnare = substr($num_da_assegnare,1);
} # fine elseif ($num_regole_esistente)
if ($num_da_assegnare and $num_da_assegnare > $num_app_reali and $inserire != "NO") {
$inserire = "NO";
echo "<br><span class=\"colwarn\">".mex("Numero di appartamenti",'unit.php')."</span> ".mex("da assegnare troppo alto, supera quello presente nella lista",$pag).".<br><br>";
} # fine if ($num_da_assegnare and $num_da_assegnare > $num_app_reali and...
if ($regola_2b) {
if (!$num_giorni or controlla_num_pos($num_giorni) == "NO") {
$inserire = "NO";
echo "<br><span class=\"colwarn\">".mex("Si deve inserire il numero di giorni",$pag)."</span>.<br><br>";
} # fine if (!$num_giorni or controlla_num_pos($num_giorni) == "NO")
if ($ini_fine != "ini" and $ini_fine != "fine") $inserire = "NO";
if ($ini_fine == "ini") {
$num_giorni_ini = "'$num_giorni'";
$num_giorni_fine = "NULL";
} # fine if ($ini_fine == "ini")
if ($ini_fine == "fine") {
$num_giorni_ini = "NULL";
$num_giorni_fine = "'$num_giorni'";
} # fine if ($ini_fine == "fine")
} # fine if ($regola_2b)
if ($v_apti != "v") $v_apti = "";

if ($inserire != "NO") {
if ($num_regole_esistente != 0) {
if ($regola_2b) {
esegui_query("update $tableregole set motivazione2 = '$lista_app', iddatainizio = $num_giorni_ini, iddatafine = $num_giorni_fine where tariffa_per_app = '".aggslashdb($tipotariffa)."'");
$lista_app_norm = risul_query($regola_esistente,0,'motivazione');
$lista_app_ecc = $lista_app;
} # fine if ($regola_2b)
else {
esegui_query("update $tableregole set motivazione = '$lista_app' where tariffa_per_app = '".aggslashdb($tipotariffa)."'");
if ($num_apti > 1) esegui_query("update $tableregole set motivazione3 = '$v_apti$num_apti' where tariffa_per_app = '".aggslashdb($tipotariffa)."'");
else esegui_query("update $tableregole set motivazione3 = NULL where tariffa_per_app = '".aggslashdb($tipotariffa)."'");
$lista_app_norm = $lista_app;
$lista_app_ecc = risul_query($regola_esistente,0,'motivazione2');
} # fine else if ($regola_2b)
if ($lista_app_norm and $lista_app_ecc) {
$vett_app = explode(",",$lista_app_norm);
$num_app = count($vett_app);
$app_mancanti_da_ecc = "";
for ($num1 = 0 ; $num1 < $num_app ; $num1 = $num1 + 1) {
if (str_replace(",".$vett_app[$num1].",","",",$lista_app_ecc,") == ",$lista_app_ecc,") $app_mancanti_da_ecc .= ", ".$vett_app[$num1];
} # fine for $num1
if ($app_mancanti_da_ecc) echo "<br><b class=\"colwarn\">".mex("Attenzione",$pag)."</b>: ".mex("ci sono appartamenti",'unit.php')." (<b>".substr($app_mancanti_da_ecc,2)."</b>) ".mex("della regola 2 mancanti nella eccezione alla regola",$pag).".<br>";
} # fine if ($lista_app_norm and $lista_app_ecc)
echo "<br>".mex("La regola di assegnazione",$pag)." 2 ".mex("è stata modificata",$pag).".<br><br>";
} # fine if ($num_regole_esistente != 0)
else {
$idregole = esegui_query("select max(idregole) from $tableregole");
$idregole = risul_query($idregole,0,0);
$idregole = $idregole + 1;
if ($regola_2b) esegui_query("insert into $tableregole (idregole,tariffa_per_app,iddatainizio,motivazione2) values ('$idregole','".aggslashdb($tipotariffa)."','$num_giorni','$lista_app')");
else {
esegui_query("insert into $tableregole (idregole,tariffa_per_app,motivazione) values ('$idregole','".aggslashdb($tipotariffa)."','$lista_app')");
if ($num_apti > 1) esegui_query("update $tableregole set motivazione3 = '$v_apti$num_apti' where tariffa_per_app = '".aggslashdb($tipotariffa)."'");
} # fine else if ($regola_2b)
echo "<br>".mex("La regola di assegnazione",$pag)." 2 ".mex("è stata inserita",$pag).".<br><br>";
} # fine else if ($num_regole_esistente != 0)
$aggiorna_ic_disp = 1;
} # fine if ($inserire != "NO")
} # fine if (($regola_2 or $regola_2b) and $priv_mod_reg2 == "s")


if ($regola_3 and $priv_mod_reg2 == "s") {
if (substr($tipotariffa,0,7) != "tariffa" or controlla_num_pos(substr($tipotariffa,7)) == "NO" or ($attiva_tariffe_consentite != "n" and $tariffe_consentite_vett[substr($tipotariffa,7)] != "SI")) {
$inserire = "NO";
echo mex("Si deve scegliere la tariffa",$pag).".<br>";
} # fine if (substr($tipotariffa,0,7) != "tariffa" or...
else {
$regola_esistente = esegui_query("select * from $tableregole where tariffa_per_persone = '".aggslashdb($tipotariffa)."'");
$num_regole_esistente = numlin_query($regola_esistente);
/*
if ($num_regole_esistente != 0) {
$inserire = "NO";
echo mex("La tariffa scelta ha già un numero di persone associato, cancella la regola prima di inserirne una nuova",$pag).".<br>";
} # fine if ($num_regole_esistente != 0)
*/
} # fine else if (substr($tipotariffa,0,7) != "tariffa" or...
if (!$num_persone or controlla_num_pos($num_persone) != "SI") {
$inserire = "NO";
echo mex("Si deve inserire il numero di persone da associare",$pag).".<br>";
} # fine if (!$num_persone or controlla_num_pos($num_persone) != "SI")
if ($inserire != "NO") {
$regola2_esistente = esegui_query("select * from $tableregole where tariffa_per_app = '".aggslashdb($tipotariffa)."'");
if (numlin_query($regola2_esistente)) {
$lista_app2 = risul_query($regola2_esistente,0,'motivazione');
$lista_app2_ecc = risul_query($regola2_esistente,0,'motivazione2');
if ($lista_app2_ecc) {
if ($lista_app2) $lista_app2 .= ",";
$lista_app2 .= $lista_app2_ecc;
} # fine if ($lista_app2_ecc)
$vett_app2 = explode(",",$lista_app2);
$num_app2 = count($vett_app2);
$app_trovato = array();
$app_piccoli = "";
$app_compatibile = 0;
for ($num1 = 0 ; $num1 < $num_app2 ; $num1++) {
if (!$app_trovato[$vett_app2[$num1]]) {
$app_trovato[$vett_app2[$num1]] = 1;
$app = esegui_query("select * from $tableappartamenti where idappartamenti = '".aggslashdb($vett_app2[$num1])."' ");
if (numlin_query($app)) {
$pers_max = risul_query($app,0,'maxoccupanti');
if ($pers_max < $num_persone) $app_piccoli .= ", ".$vett_app2[$num1];
else $app_compatibile = 1;
} # fine if (numlin_query($app))
} # fine if (!$app_trovato[$vett_app2[$num1]])
} # fine for $num1
if (!$app_compatibile) echo "<br><b class=\"colwarn\">".mex("Attenzione",$pag)."</b>: ".lcfirst(mex("Non ci sono appartamenti con le caratteristiche richieste",'unit.php'))." ".mex("nella regola di assegnazione 2 di questa tariffa",$pag).".<br>";
elseif ($app_piccoli) echo "<br><b class=\"colwarn\">".mex("Attenzione",$pag)."</b>: ".mex("ci sono appartamenti",'unit.php')." (<b>".substr($app_piccoli,2)."</b>) ".mex("nella regola 2 di questa tariffa che non possono ospitare",$pag)." $num_persone ".mex("persone",$pag).".<br>";
} # fine if (numlin_query($regola2_esistente))
if ($num_regole_esistente != 0) {
esegui_query("update $tableregole set iddatainizio = '".aggslashdb($num_persone)."' where tariffa_per_persone = '".aggslashdb($tipotariffa)."'");
echo "<br>".mex("La regola di assegnazione",$pag)." 3 ".mex("è stata modificata",$pag).".<br><br>";
} # fine if ($num_regole_esistente != 0)
else {
$idregole = esegui_query("select max(idregole) from $tableregole");
$idregole = risul_query($idregole,0,0);
$idregole = $idregole + 1;
esegui_query("insert into $tableregole (idregole,tariffa_per_persone,iddatainizio) values ('$idregole','".aggslashdb($tipotariffa)."', '".aggslashdb($num_persone)."')");
echo "<br>".mex("La regola di assegnazione",$pag)." 3 ".mex("è stata inserita",$pag).".<br><br>";
} # fine else if ($num_regole_esistente != 0)
$aggiorna_ic_tar = 1;
} # fine if ($inserire != "NO")
} # fine if ($regola_3 and $priv_mod_reg2 == "s")


if ($regola_4 and $id_utente == 1) {
if (substr($tipotariffa,0,7) != "tariffa" or controlla_num_pos(substr($tipotariffa,7)) == "NO") {
$inserire = "NO";
echo mex("Si deve scegliere la tariffa",$pag).".<br>";
} # fine if (substr($tipotariffa,0,7) != "tariffa" or...
else {
$regola_esistente = esegui_query("select * from $tableregole where tariffa_per_utente = '".aggslashdb($tipotariffa)."'");
$num_regole_esistente = numlin_query($regola_esistente);
/*
if ($num_regole_esistente != 0) {
$inserire = "NO";
echo mex("La tariffa scelta ha già un utente associato, cancella la regola prima di inserirne una nuova",$pag).".<br>";
} # fine if ($num_regole_esistente != 0)
*/
} # fine else if (substr($tipotariffa,0,7) != "tariffa" or...
if (!$id_utente_inserimento) {
$inserire = "NO";
echo mex("Si deve inserire l'utente da associare",$pag).".<br>";
} # fine if (!$id_utente_inserimento)
else {
$id_utente_inserimento = aggslashdb($id_utente_inserimento);
$utente_tariffa = esegui_query("select nome_utente from $tableutenti where idutenti = '$id_utente_inserimento'");
if (numlin_query($utente_tariffa) != 1) {
$inserire = "NO";
echo mex("L'utente ",$pag).$id_utente_inserimento.mex(" non esiste",$pag).".<br>";
} # fine if ($numlin_query($utente_tariffa) != 1)
} # fine else if (!$id_utente_inserimento)
if ($inserire != "NO") {
if ($num_regole_esistente != 0) {
esegui_query("update $tableregole set iddatainizio = '$id_utente_inserimento' where tariffa_per_utente = '".aggslashdb($tipotariffa)."'");
echo mex("La regola di assegnazione",$pag)." 4 ".mex("è stata modificata",$pag).".<br>";
} # fine if ($num_regole_esistente != 0)
else {
$idregole = esegui_query("select max(idregole) from $tableregole");
$idregole = risul_query($idregole,0,0);
$idregole = $idregole + 1;
esegui_query("insert into $tableregole (idregole,tariffa_per_utente,iddatainizio) values ('$idregole','".aggslashdb($tipotariffa)."', '$id_utente_inserimento')");
echo mex("La regola di assegnazione",$pag)." 4 ".mex("è stata inserita",$pag).".<br>";
} # fine else if ($num_regole_esistente != 0)
} # fine if ($inserire != "NO")
} # fine if ($regola_4 and $id_utente == 1)


if ($tabelle_lock) unlock_tabelle($tabelle_lock);
if ($origine) $azione = controlla_pag_origine($origine);
else $azione = "crearegole.php";
echo "<form accept-charset=\"utf-8\" method=\"post\" action=\"$azione\"><div>
<input type=\"hidden\" name=\"anno\" value=\"$anno\">
<input type=\"hidden\" name=\"id_sessione\" value=\"$id_sessione\">
<button class=\"gobk\" type=\"submit\"><div>".mex("Torna indietro",$pag)."</div></button>";

if ($aggiorna_ic_disp or $aggiorna_ic_tar) {
$lock = 1;
$aggiorna_disp = $aggiorna_ic_disp;
$aggiorna_tar = $aggiorna_ic_tar;
if (@function_exists('pcntl_fork')) include("./includes/interconnect/aggiorna_ic_fork.php");
else include("./includes/interconnect/aggiorna_ic.php");
} # fine if ($aggiorna_ic_disp or $aggiorna_ic_tar)

} # fine if ($inserisci)

else {




# Form iniziale di inserimento
echo "<br><h4 id=\"h_irul\"><span>".mex("Inserisci le regole di assegnazione per le prenotazioni dell'anno",$pag)." $anno
</span></h4><br><hr style=\"width: 95%\">";

$mostra_solo_regola = 0;
$origine = htmlspecialchars($origine);
if ($origine == "tab_reg1") {
$mostra_solo_regola = 1;
$origine = "visualizza_tabelle.php?tipo_tabella=regole#hreg1";
} # fine if ($origine == "tab_reg1")
if ($origine == "tab_reg2") {
$mostra_solo_regola = 2;
$origine = "visualizza_tabelle.php?tipo_tabella=regole#hreg2";
} # fine if ($origine == "tab_reg2")
if ($origine == "tab_reg3") {
$mostra_solo_regola = 3;
$origine = "visualizza_tabelle.php?tipo_tabella=regole#hreg3";
} # fine if ($origine == "tab_reg3")
if ($origine == "tab_tariffa") {
if ($tipotariffa_regola2) $mostra_solo_regola = 2;
if ($tipotariffa_regola3) $mostra_solo_regola = 3;
$origine = "tab_tariffe.php?numtariffa1=".htmlspecialchars(substr($tipotariffa_regola2.$tipotariffa_regola3,7));
} # fine if ($origine == "tab_tariffa")
if (substr($origine,0,3) == "ic_") {
$orig = explode("_",$origine);
if ($orig[1] == "r2") $mostra_solo_regola = 2;
if ($orig[1] == "r3") $mostra_solo_regola = 3;
$origine = "interconnessioni.php#".$orig[2];
} # fine if (substr($origine,0,3) == "ic_")
$rigatariffe = esegui_query("select * from $tablenometariffe where idntariffe = 1 ");
$numero_tariffe = risul_query($rigatariffe,0,'nomecostoagg');


if ((!$mostra_solo_regola or $mostra_solo_regola == 1) and $priv_mod_reg1 != "n") {
$tipo_mod_reg1 = "";
if ($mod_idregola1 and controlla_num_pos($mod_idregola1) == "SI") {
$reg_esist = esegui_query("select * from $tableregole where idregole = '$mod_idregola1' ");
if (numlin_query($reg_esist)) {
$app_agenzia = risul_query($reg_esist,0,'app_agenzia');
$tariffa_chiusa = risul_query($reg_esist,0,'tariffa_chiusa');
if ($app_agenzia) $tipo_mod_reg1 = "a";
elseif ($tariffa_chiusa) $tipo_mod_reg1 = "t";
if ($tipo_mod_reg1) {
$iddatainizio = risul_query($reg_esist,0,'iddatainizio');
$iddatafine = risul_query($reg_esist,0,'iddatafine');
$motivazione = risul_query($reg_esist,0,'motivazione');
$motivazione2 = risul_query($reg_esist,0,'motivazione2');
$inizio_select = esegui_query("select datainizio from $PHPR_TAB_PRE"."periodi$anno where idperiodi = '$iddatainizio' ");
$inizio_select = risul_query($inizio_select,0,'datainizio');
$fine_select = esegui_query("select datafine from $PHPR_TAB_PRE"."periodi$anno where idperiodi = '$iddatafine' ");
$fine_select = risul_query($fine_select,0,'datafine');
} # fine if ($tipo_mod_reg1)
} # fine if (numlin_query($reg_esist))
} # fine if ($mod_idregola1 and controlla_num_pos($mod_idregola1) == "SI")
if (!$tipo_mod_reg1) {
$transaz_esist_1a = esegui_query("select * from $tabletransazioni where idsessione = '$id_sessione' and tipo_transazione = 'cr_1a' ");
$transaz_esist_1t = esegui_query("select * from $tabletransazioni where idsessione = '$id_sessione' and tipo_transazione = 'cr_1t' ");
if (!numlin_query($transaz_esist_1a) or !numlin_query($transaz_esist_1t)) {
$inizio_select = "";
$fine_select = "";
$motivazione_orig = htmlspecialchars($motivazione);
$oggi = date("Y-m-d",(time() + (C_DIFF_ORE * 3600)));
$date_select = esegui_query("select datainizio,datafine from $PHPR_TAB_PRE"."periodi$anno where datainizio <= '$oggi' and datafine > '$oggi' ");
if (numlin_query($date_select) != 0) {
$inizio_select_orig = risul_query($date_select,0,'datainizio');
$fine_select_orig = risul_query($date_select,0,'datafine');
} # fine if (numlin_query($date_select) != 0)
} # fine if (!numlin_query($transaz_esist_1a) or !numlin_query($transaz_esist_1t))
if (numlin_query($transaz_esist_1a)) {
$inizio_select = risul_query($transaz_esist_1a,0,'dati_transazione1');
$fine_select = risul_query($transaz_esist_1a,0,'dati_transazione2');
$motivazione = risul_query($transaz_esist_1a,0,'dati_transazione3');
$motivazione2 = risul_query($transaz_esist_1a,0,'dati_transazione4');
if ($motivazione2) $motivazione2 = "x";
} # fine if (numlin_query($transaz_esist_1a))
else {
$inizio_select = $inizio_select_orig;
$fine_select = $fine_select_orig;
$motivazione = $motivazione_orig;
} # fine else if (numlin_query($transaz_esist_1a))
} # fine if (!$tipo_mod_reg1)

if (($priv_mod_reg1 == "s" or $priv_mod_reg1 == "a") and $tipo_mod_reg1 != "t") {
echo "<div style=\"text-align: center;\">".mex("Regola di assegnazione",$pag)." <b>1</b>.</div><br>
<form accept-charset=\"utf-8\" method=\"post\" action=\"crearegole.php\"><div>
<input type=\"hidden\" name=\"anno\" value=\"$anno\">
<input type=\"hidden\" name=\"id_sessione\" value=\"$id_sessione\">
<input type=\"hidden\" name=\"inserisci\" value=\"SI\">";
if ($tipo_mod_reg1) echo "<input type=\"hidden\" name=\"mod_idregola1\" value=\"$mod_idregola1\">";
if ($origine) echo "<input type=\"hidden\" name=\"origine\" value=\"".htmlspecialchars($origine)."\">";
echo "<select name=\"chiudi_app\">";
if ($motivazione2 == "x") { $sel_x = " selected"; $sel = ""; }
else { $sel_x = ""; $sel = " selected"; }
echo "<option value=\"1\"$sel_x>".mex("Chiudi",$pag)."</option>";
if ($attiva_regole1_consentite == "n") echo "<option value=\"\"$sel>".mex("Chiedi prima di assegnare",$pag)."</option>";
echo "</select> ".mex("l'appartamento",'unit.php')." <select name=\"appartamento\">";
if (!strcmp($app_agenzia,"")) $sel = " selected";
else $sel = "";
echo "<option value=\"\"$sel>--</option>";
if ($tutti_app_consentiti and !$tipo_mod_reg1) include(C_DATI_PATH."/selectappartamenti.php");
else {
for ($num1 = 0 ; $num1 < $num_appartamenti ; $num1++) {
$idapp = risul_query($appartamenti,$num1,'idappartamenti');
if ($idapp == $app_agenzia) $sel = " selected";
else $sel = "";
if ($tutti_app_consentiti or $appartamenti_consentiti[$idapp] == "SI") echo "<option value=\"$idapp\"$sel>$idapp</option>";
} # fine for $num1
} # fine else if ($tutti_app_consentiti and !$tipo_mod_reg1)
echo "</select> ".mex("nel periodo dal",$pag)." ";
mostra_menu_date(C_DATI_PATH."/selectperiodi$anno.$id_utente.php","inizioperiodo",$inizio_select,"","",$id_utente,$tema);
echo " ".mex("al",$pag)." ";
mostra_menu_date(C_DATI_PATH."/selectperiodi$anno.$id_utente.php","fineperiodo",$fine_select,"","",$id_utente,$tema);
echo "<br>".mex("motivazione",$pag).":
 <input type=\"text\" name=\"motivazione\" value=\"$motivazione\" size=\"30\" maxlength=\"30\">
 (".mex("opzionale",$pag).")<br><div style=\"text-align: center;\">
<button class=\"rlpe\" type=\"submit\" name=\"regola_1\" value=\"1\"><div>";
if ($tipo_mod_reg1) echo mex("Modifica la regola",$pag);
else echo mex("Inserisci la regola",$pag);
echo " 1</div></button></div></div></form>";
} # fine if (($priv_mod_reg1 == "s" or $priv_mod_reg1 == "a") and $tipo_mod_reg1 != "t")
if ($priv_mod_reg1 == "s" and !$tipo_mod_reg1) echo "<hr style=\"width: 50%; margin-left: 2.5%;\">";

if (($priv_mod_reg1 == "s" or $priv_mod_reg1 == "t") and $tipo_mod_reg1 != "a") {
echo "<form accept-charset=\"utf-8\" method=\"post\" action=\"crearegole.php\"><div class=\"linhbox\">
<input type=\"hidden\" name=\"anno\" value=\"$anno\">
<input type=\"hidden\" name=\"id_sessione\" value=\"$id_sessione\">
<input type=\"hidden\" name=\"inserisci\" value=\"SI\">
<input type=\"hidden\" name=\"regola_1_tar\" value=\"1\">";
if ($origine) echo "<input type=\"hidden\" name=\"origine\" value=\"".htmlspecialchars($origine)."\">";
if ($tipo_mod_reg1) echo "<input type=\"hidden\" name=\"mod_idregola1\" value=\"$mod_idregola1\">";
else {
if (numlin_query($transaz_esist_1t)) {
$inizio_select = risul_query($transaz_esist_1t,0,'dati_transazione1');
$fine_select = risul_query($transaz_esist_1t,0,'dati_transazione2');
$motivazione = risul_query($transaz_esist_1t,0,'dati_transazione3');
} # fine if (numlin_query($transaz_esist_1t))
else {
$inizio_select = $inizio_select_orig;
$fine_select = $fine_select_orig;
$motivazione = $motivazione_orig;
} # fine else if (numlin_query($transaz_esist_1a))
} # fine else if (!$tipo_mod_reg1)
echo mex("Chiudi",$pag)." <select name=\"tipotariffa\" id=\"t1\">";
if (!$tariffa_chiusa) $sel = " selected";
else $sel = "";
echo "<option value=\"\"$sel>----</option>";
$tutte_tar_consentite = 1;
for ($numtariffa = 1 ; $numtariffa <= $numero_tariffe ; $numtariffa = $numtariffa + 1) {
if ($attiva_tariffe_consentite == "n" or $tariffe_consentite_vett[$numtariffa] == "SI") {
$tariffa = "tariffa".$numtariffa;
$tariffa_vedi = mex("tariffa","prenota.php").$numtariffa;
$nometariffa = risul_query($rigatariffe,0,$tariffa);
if ($nometariffa != "") $nometariffa_vedi = " ($nometariffa)";
else $nometariffa_vedi = "";
if ($tariffa == $tariffa_chiusa) $sel = " selected";
else $sel = "";
echo "<option value=\"$tariffa\"$sel>$tariffa_vedi$nometariffa_vedi</option>";
} # fine if ($attiva_tariffe_consentite == "n" or $tariffe_consentite_vett[$numtariffa] == "SI")
else $tutte_tar_consentite = 0;
} # fine for $numtariffa
echo "</select> ".mex("nel periodo dal",$pag)." ";
mostra_menu_date(C_DATI_PATH."/selectperiodi$anno.$id_utente.php","inizioperiodo",$inizio_select,"","",$id_utente,$tema);
echo " ".mex("al",$pag)." ";
mostra_menu_date(C_DATI_PATH."/selectperiodi$anno.$id_utente.php","fineperiodo",$fine_select,"","",$id_utente,$tema);
echo "<br>".mex("motivazione",$pag).":
 <input type=\"text\" name=\"motivazione\" value=\"$motivazione\" size=\"30\" maxlength=\"30\">
 (".mex("opzionale",$pag).")
 <button class=\"rlpe\" type=\"submit\"><div>";
if ($tipo_mod_reg1) echo mex("Modifica la regola",$pag);
else echo mex("Inserisci la regola",$pag);
echo " 1 ".mex("per le tariffe",$pag)."</div></button>
</div></form>";
} # fine if (($priv_mod_reg1 == "s" or $priv_mod_reg1 == "t") and $tipo_mod_reg1 != "a")

if ($tutti_app_consentiti and $attiva_regole1_consentite == "n" and $tutte_tar_consentite and $priv_mod_reg1 == "s" and !$mostra_solo_regola) {
echo "<hr style=\"width: 50%; margin-left: 2.5%;\">
<form accept-charset=\"utf-8\" method=\"post\" action=\"crearegole.php\"><div>
<input type=\"hidden\" name=\"anno\" value=\"$anno\">
<input type=\"hidden\" name=\"id_sessione\" value=\"$id_sessione\">
<input type=\"hidden\" name=\"inserisci\" value=\"SI\">
<button class=\"crul\" type=\"submit\" name=\"canc_regola_1\" value=\"1\"><div>".mex("Cancella tutte le regole di questo tipo",$pag)."</div></button>
</div></form>";
} # fine if ($tutti_app_consentiti and $attiva_regole1_consentite == "n" and...

echo "<hr style=\"width: 95%\">";
} # fine if ((!$mostra_solo_regola or $mostra_solo_regola == 1) and $priv_mod_reg1 != "n")


if ((!$mostra_solo_regola or $mostra_solo_regola == 2) and $priv_mod_reg2 == "s") {
echo "<a name=\"regola2\"></a>
<script type=\"text/javascript\">
<!--
var nreg = '';
function nasc_app (nreg) {
var tab_app = document.getElementById('tab_app'+nreg);
tab_app.style.visibility = 'hidden';
tab_app.innerHTML = '';
} // fine function nasc_app

function aggiorna_vic_reg2 () {
var sel_t2na = document.getElementById(\"t2na\");
var num_sel = sel_t2na.selectedIndex;
var num_app = sel_t2na.options[num_sel].value;
var txt_vic = document.getElementById(\"vcn_r2\");
if (num_app > 1) txt_vic.style.display = 'inline';
else txt_vic.style.display = 'none';
}

function aggiorna_val_reg2 () {
var sel_tar = document.getElementById(\"t2\");
var num_sel = sel_tar.selectedIndex;
var tariffa = sel_tar.options[num_sel].value;
var txt_box = document.getElementById(\"v2\");
var sel_t2na = document.getElementById(\"t2na\");
var num_opt = sel_t2na.options.length;
if (tariffa == '') {
txt_box.disabled = true;
sel_t2na.selectedIndex = 0;
}
else txt_box.disabled = false;
txt_box.value = \"\";
";
$regole2 = esegui_query("select * from $tableregole where tariffa_per_app != ''");
$num_regole2 = numlin_query($regole2);
for ($num1 = 0 ; $num1 < $num_regole2 ; $num1++) {
$tariffa = risul_query($regole2,$num1,'tariffa_per_app');
if ($attiva_tariffe_consentite == "n" or $tariffe_consentite_vett[substr($tariffa,7)] == "SI") {
$lista_app = risul_query($regole2,$num1,'motivazione');
$num_apti = risul_query($regole2,$num1,'motivazione3');
$v_apti = "true";
if (substr($num_apti,0,1) == "v") $num_apti = substr($num_apti,1);
elseif ($num_apti) $v_apti = "false";
if (!$num_apti) $num_apti = 1;
echo "if (tariffa == \"$tariffa\") {
txt_box.value = \"$lista_app\";
var n_a = '$num_apti';
document.getElementById(\"v2na\").checked = $v_apti;
}
for (n1 = 0 ; n1 < num_opt ; n1++) {
if (sel_t2na.options[n1].value == n_a) sel_t2na.selectedIndex = n1;
}
";
} # fine if ($attiva_tariffe_consentite == "n" or $tariffe_consentite_vett[substr($tariffa,7)] == "SI")
} # fine for $num1
echo "
aggiorna_vic_reg2();
nasc_app ('')
} // fine function aggiorna_val_reg2

function aggiorna_val_reg2b () {
var sel_tar = document.getElementById(\"t2b\");
var num_sel = sel_tar.selectedIndex;
var tariffa = sel_tar.options[num_sel].value;
var txt_box = document.getElementById(\"v2b\");
if (tariffa == '') txt_box.disabled = true;
else txt_box.disabled = false;
txt_box.value = \"\";
";
$regole2 = esegui_query("select * from $tableregole where tariffa_per_app != ''");
$num_regole2 = numlin_query($regole2);
for ($num1 = 0 ; $num1 < $num_regole2 ; $num1++) {
$tariffa = risul_query($regole2,$num1,'tariffa_per_app');
if ($attiva_tariffe_consentite == "n" or $tariffe_consentite_vett[substr($tariffa,7)] == "SI") {
$num_giorni_ini = risul_query($regole2,$num1,'iddatainizio');
$num_giorni_fine = risul_query($regole2,$num1,'iddatafine');
if ($num_giorni_ini) {
$num_giorni = $num_giorni_ini;
$selind = 0;
} # fine if ($num_giorni_ini)
else {
$num_giorni = $num_giorni_fine;
$selind = 1;
} # fine else if ($num_giorni_ini)
if (!$num_giorni) $num_giorni = 0;
$lista_app = risul_query($regole2,$num1,'motivazione2');
echo "if (tariffa == \"$tariffa\") {
txt_box.value = '$lista_app';
document.getElementById(\"ng2b\").value = '$num_giorni';
document.getElementById(\"if2b\").selectedIndex = $selind;
}
";
} # fine if ($attiva_tariffe_consentite == "n" or $tariffe_consentite_vett[substr($tariffa,7)] == "SI")
} # fine for $num1
echo "
nasc_app ('b')
} // fine function aggiorna_val_reg2b

function agg_da_txt_a_tab (bold,nreg) {
var app_check = '';
var testo = document.getElementById(\"v2\"+nreg).value;
var app_testo = testo.split(',');
var num_app_testo = app_testo.length;
var id_app_sel = new Array();
for (n1 = 0 ; n1 < num_app_testo ; n1++) id_app_sel[app_testo[n1]] = 's';
for (n1 = 0 ; n1 < num_appart ; n1++) {
app_check = document.getElementById('app'+nreg+n1);
if (id_app_sel[appart[n1]] == 's') {
app_check.checked = true;
if (bold == 'SI') document.getElementById('tda'+n1).style.fontWeight = 'bold';
}
else app_check.checked = false;
}
} // fine function agg_da_txt_a_tab

function agg_da_tab_a_txt (nreg) {
var app_check = '';
var testo = '';
for (n1 = 0 ; n1 < num_appart ; n1++) {
app_check = document.getElementById('app'+nreg+n1);
if (app_check.checked == true) testo += ','+appart[n1];
}
var txt_box = document.getElementById(\"v2\"+nreg);
txt_box.value = testo.substring(1);
document.getElementById(\"maxocc\"+nreg).selectedIndex = 0;
} // fine function agg_da_tab_a_txt

function sel_app_maxocc (nreg) {
var sel_maxocc = document.getElementById(\"maxocc\"+nreg);
var maxocc_val = sel_maxocc.options[sel_maxocc.selectedIndex].value;
if (maxocc_val) {
var txt_box = document.getElementById(\"v2\"+nreg);
txt_box.value = lapp_maxocc[maxocc_val];
agg_da_txt_a_tab('',nreg);
} // fine if (maxocc_val)
} // fine function sel_app_maxocc

function mos_app (nreg) {
var tab_app = document.getElementById('tab_app'+nreg);
if (tab_app.style.visibility == 'visible') {
tab_app.style.visibility = 'hidden';
tab_app.innerHTML = '';
}
else {
var txt_box = document.getElementById(\"v2\"+nreg);
if (txt_box.disabled == false) {
var iLeft = 0;
var elemento = document.getElementById('v2'+nreg);
while(elemento.tagName != 'BODY') {
iLeft += elemento.offsetLeft;
elemento = elemento.offsetParent;
}
tab_app.style.visibility = 'visible';
tab_app.innerHTML = '<table border=1 cellspacing=0 cellpadding=2 style=\"margin-left: '+(iLeft - 10)+'px; background-color: $t1color; text-align: center;\">\
";
$appartamenti = esegui_query("select * from $tableappartamenti order by idappartamenti");
$num_appartamenti = numlin_query($appartamenti);
$num_col = 4;
$n_col = 1;
$array_app = "";
$lista_maxocc = array();
for ($num1 = 0 ; $num1 < $num_appartamenti ; $num1++) {
$idapp = risul_query($appartamenti,$num1,'idappartamenti');
if ($appartamenti_consentiti[$idapp] == "SI") {
$lung = strlen($idapp);
if ($lung > 6) $num_col = 3;
if ($lung > 14) {
$num_col = 2;
break;
} # fine if ($lung > 14)
} # fine if ($appartamenti_consentiti[$idapp] == "SI")
} # fine for $num1
$num_app_consentiti = 0;
for ($num1 = 0 ; $num1 < $num_appartamenti ; $num1++) {
$idapp = risul_query($appartamenti,$num1,'idappartamenti');
if ($appartamenti_consentiti[$idapp] == "SI") {
$maxocc = risul_query($appartamenti,$num1,'maxoccupanti');
if ($maxocc) $lista_maxocc[$maxocc] .= $idapp.",";
$array_app .= "\"$idapp\",";
if ($n_col == 1) echo "<tr>";
echo "<td id=\"tda$num_app_consentiti\"><label><input type=\"checkbox\" id=\"app'+nreg+'$num_app_consentiti\" onclick=\"agg_da_tab_a_txt(\\''+nreg+'\\')\">$idapp<\/label><\/td>";
if ($n_col == $num_col) {
echo "<\/tr>";
$n_col = 1;
} # fine if ($n_col == $num_col)
else $n_col++;
echo "\
";
$num_app_consentiti++;
} # fine if ($appartamenti_consentiti[$idapp] == "SI")
} # fine for $num1
if ($n_col != 1) {
for ($num1 = $n_col ; $num1 <= $num_col ; $num1++) echo "<td>&nbsp;<\/td>";
echo "<\/tr>";
} # fine if ($n_col != 1)
$array_app = substr($array_app,0,-1);
if (@is_array($lista_maxocc)) {
echo "<tr><td colspan=\"$num_col\">\
<small>".mex("Seleziona tutti gli appartamenti<br> da ",'unit.php')."\
<select id=\"maxocc'+nreg+'\" onchange=\"sel_app_maxocc(\\''+nreg+'\\')\">\
<option value=\"\" selected>-<\/option>\
";
ksort($lista_maxocc);
reset($lista_maxocc);
foreach ($lista_maxocc as $key => $val) {
$persone_casa = $key;
if ($persone_casa != $ultime_persone_casa) {
$array_maxocc .= "lapp_maxocc[$persone_casa] = \"".substr($val,0,-1)."\";
";
$ultime_persone_casa = $persone_casa;
echo "<option value=\"$persone_casa\">$persone_casa<\/option>\
";
} # fine if ($persone_casa != $ultimepersone_casa)
} # fine foreach ($lista_maxocc as $key => $val)

echo "<\/select>\
".mex(" persone",'unit.php')."<\/small>\
<\/td><\/tr>";
} # fine if (@is_array($lista_maxocc))
echo "<\/table><br>'
agg_da_txt_a_tab('SI',nreg);
}
}
} // fine function mos_app

var appart = new Array($array_app);
var num_appart = $num_app_consentiti;
var lapp_maxocc = new Array();
$array_maxocc
-->
</script>

<div id=\"hreg2\" style=\"text-align: center;\">".mex("Regola di assegnazione",$pag)." <b>2</b>.</div><br>
<form accept-charset=\"utf-8\" method=\"post\" action=\"crearegole.php\"><div>
<input type=\"hidden\" name=\"anno\" value=\"$anno\">
<input type=\"hidden\" name=\"id_sessione\" value=\"$id_sessione\">
<input type=\"hidden\" name=\"inserisci\" value=\"SI\">
<input type=\"hidden\" name=\"regola_2\" value=\"SI\">";
if ($origine) echo "<input type=\"hidden\" name=\"origine\" value=\"".htmlspecialchars($origine)."\">";
echo "".mex("Quando si sceglie la tariffa",$pag)."
<select name=\"tipotariffa\" id=\"t2\" onchange=\"aggiorna_val_reg2()\">
<option value=\"\" selected>----</option>";
for ($numtariffa = 1 ; $numtariffa <= $numero_tariffe ; $numtariffa = $numtariffa + 1) {
if ($attiva_tariffe_consentite == "n" or $tariffe_consentite_vett[$numtariffa] == "SI") {
$tariffa = "tariffa".$numtariffa;
$tariffa_vedi = mex("tariffa","prenota.php").$numtariffa;
$nometariffa = risul_query($rigatariffe,0,$tariffa);
if ($nometariffa != "") $nometariffa_vedi = " ($nometariffa)";
else $nometariffa_vedi = "";
echo "
<option value=\"$tariffa\">$tariffa_vedi$nometariffa_vedi</option>";
} # fine if ($attiva_tariffe_consentite == "n" or $tariffe_consentite_vett[$numtariffa] == "SI")
} # fine for $numtariffa
echo "</select>
 ".mex("assegna automaticamente",$pag)." <select name=\"num_apti\" id=\"t2na\" onchange=\"aggiorna_vic_reg2()\">";
for ($num1 = 1 ; $num1 <= $num_app_consentiti ; $num1++) {
if ($num1 == 1) echo "<option value=\"1\" selected>".mex("un appartamento",'unit.php')."</option>";
else echo "<option value=\"$num1\">$num1 ".mex("appartamenti",'unit.php')."</option>";
} # fine for $num1
echo "</select><span id=\"vcn_r2\"> (<label><input type=\"checkbox\" name=\"v_apti\" id=\"v2na\" value=\"v\" checked>".mex("vicini",'unit.php')."</label>)</span> ".mex("tra",$pag)."
 <input type=\"text\" id=\"v2\" name=\"lista_app\" size=\"15\" onclick=\"nasc_app('')\"><input class=\"dbutton\" id=\"mapp\" onclick=\"mos_app('')\" value=\"..\" type=\"button\">
 (".mex("lista di appartamenti separati da virgole",'unit.php').")
 ".mex("se non si inserisce nessun altro metodo di assegnazione",$pag).".<br>";
echo "<script type=\"text/javascript\">
<!--
var txt_box = document.getElementById(\"v2\");
txt_box.disabled = true;
document.write('<div id=\"tab_app\" style=\"visibility: hidden;\"><\/div>');
document.getElementById(\"vcn_r2\").style.display = 'none';
-->
</script>
<div style=\"text-align: center;\">
<button class=\"irul\" type=\"submit\"><div>".mex("Inserisci o modifica la regola",$pag)." 2</div></button>
</div></div></form>";

if ($tipotariffa_regola2 and preg_replace("/tariffa[0-9]+/","",$tipotariffa_regola2) == "") {
echo "<script type=\"text/javascript\">
<!--
var sel_tr2 = document.getElementById(\"t2\");
sel_tr2.value = '$tipotariffa_regola2';
aggiorna_val_reg2();
mos_app('');
-->
</script>
";
} # fine if ($tipotariffa_regola2)

echo "<br><br>
<form accept-charset=\"utf-8\" method=\"post\" action=\"crearegole.php\"><div class=\"linhbox\">
<input type=\"hidden\" name=\"anno\" value=\"$anno\">
<input type=\"hidden\" name=\"id_sessione\" value=\"$id_sessione\">
<input type=\"hidden\" name=\"inserisci\" value=\"SI\">
<input type=\"hidden\" name=\"regola_2b\" value=\"SI\">";
if ($origine) echo "<input type=\"hidden\" name=\"origine\" value=\"".htmlspecialchars($origine)."\">";
echo "".mex("Eccezioni alla regola",$pag)." 2:<br>
".mex("Quando si sceglie la tariffa",$pag)."
<select name=\"tipotariffa\" id=\"t2b\" onchange=\"aggiorna_val_reg2b()\">
<option value=\"\" selected>----</option>
";
for ($numtariffa = 1 ; $numtariffa <= $numero_tariffe ; $numtariffa++) {
if ($attiva_tariffe_consentite == "n" or $tariffe_consentite_vett[$numtariffa] == "SI") {
$tariffa = "tariffa".$numtariffa;
$tariffa_vedi = mex("tariffa","prenota.php").$numtariffa;
$nometariffa = risul_query($rigatariffe,0,$tariffa);
if ($nometariffa != "") $nometariffa_vedi = " ($nometariffa)";
else $nometariffa_vedi = "";
echo "
<option value=\"$tariffa\">$tariffa_vedi$nometariffa_vedi</option>";
} # fine if ($attiva_tariffe_consentite == "n" or $tariffe_consentite_vett[$numtariffa] == "SI")
} # fine for $numtariffa
echo "</select>
 ".mex("e mancano meno di",$pag)."
 <input type=\"text\" id=\"ng2b\" name=\"num_giorni\" size=\"2\" value=\"0\">
 ".mex("giorni",$pag)."
 <select id=\"if2b\" name=\"ini_fine\">
<option value=\"ini\">".mex("dall'inizio",$pag)."</option>
<option value=\"fine\">".mex("dalla fine",$pag)."</option>
</select> ".mex("della prenotazione quando essa viene inserita, allora",$pag)."
 ".mex("assegna automaticamente gli appartamenti",'unit.php')."
 <input type=\"text\" id=\"v2b\" name=\"lista_app\" size=\"15\" onclick=\"nasc_app('b')\"><input class=\"dbutton\" id=\"mappb\" onclick=\"mos_app('b')\" value=\"..\" type=\"button\">
 (".mex("se c'è almeno un appartamento della regola originale compatibile con il numero di persone",'unit.php')."). ";
echo "<button class=\"rlpe\" type=\"submit\"><div>".mex("Inserisci o modifica questa eccezione alla regola",$pag)." 2</div></button>
<script type=\"text/javascript\">
<!--
var txt_box = document.getElementById(\"v2b\");
txt_box.disabled = true;
document.write('<div id=\"tab_appb\" style=\"visibility: hidden;\"><\/div>');
-->
</script>
</div></form>
<hr style=\"width: 95%\">";
} # fine if ((!$mostra_solo_regola or $mostra_solo_regola == 2) and $priv_mod_reg2 == "s")


if ((!$mostra_solo_regola or $mostra_solo_regola == 3) and $priv_mod_reg2 == "s") {
echo "
<script type=\"text/javascript\">
<!--
function aggiorna_val_reg3 () {
var sel_tar = document.getElementById(\"t3\");
var num_sel = sel_tar.selectedIndex;
var tariffa = sel_tar.options[num_sel].value;
var txt_box = document.getElementById(\"v3\");
if (tariffa == '') txt_box.disabled = true;
else txt_box.disabled = false;
txt_box.value = \"\";
";
$regole3 = esegui_query("select * from $tableregole where tariffa_per_persone != ''");
$num_regole3 = numlin_query($regole3);
for ($num1 = 0 ; $num1 < $num_regole3 ; $num1++) {
$tariffa = risul_query($regole3,$num1,'tariffa_per_persone');
if ($attiva_tariffe_consentite == "n" or $tariffe_consentite_vett[substr($tariffa,7)] == "SI") {
$persone = risul_query($regole3,$num1,'iddatainizio');
echo "if (tariffa == \"$tariffa\") txt_box.value = \"$persone\";
";
} # fine if ($attiva_tariffe_consentite == "n" or $tariffe_consentite_vett[substr($tariffa,7)] == "SI")
} # fine for $num1
echo "}
-->
</script>

<div style=\"text-align: center;\" id=\"hreg3\">".mex("Regola di assegnazione",$pag)." <b>3</b>.</div><br>
<form accept-charset=\"utf-8\" method=\"post\" action=\"crearegole.php\"><div>
<input type=\"hidden\" name=\"anno\" value=\"$anno\">
<input type=\"hidden\" name=\"id_sessione\" value=\"$id_sessione\">
<input type=\"hidden\" name=\"inserisci\" value=\"SI\">";
if ($origine) echo "<input type=\"hidden\" name=\"origine\" value=\"".htmlspecialchars($origine)."\">";
echo mex("Quando si sceglie la tariffa",$pag)."
<select name=\"tipotariffa\" id=\"t3\" onchange=\"aggiorna_val_reg3()\">
<option value=\"\" selected>----</option>";
for ($numtariffa = 1 ; $numtariffa <= $numero_tariffe ; $numtariffa = $numtariffa + 1) {
if ($attiva_tariffe_consentite == "n" or $tariffe_consentite_vett[$numtariffa] == "SI") {
$tariffa = "tariffa".$numtariffa;
$tariffa_vedi = mex("tariffa","prenota.php").$numtariffa;
$nometariffa = risul_query($rigatariffe,0,$tariffa);
if ($nometariffa != "") $nometariffa_vedi = " ($nometariffa)";
else $nometariffa_vedi = "";
echo "
<option value=\"$tariffa\">$tariffa_vedi$nometariffa_vedi</option>";
} # fine if ($attiva_tariffe_consentite == "n" or $tariffe_consentite_vett[$numtariffa] == "SI")
} # fine for $numtariffa
echo "</select>
 ".mex("assegna automaticamente come numero di persone",$pag)."
 <input type=\"text\" id=\"v3\" name=\"num_persone\" size=\"5\">
 ".mex("se non si inserisce nessun altro numero",$pag).".<br>";
echo "<script type=\"text/javascript\">
<!--
var txt_box = document.getElementById(\"v3\");
txt_box.disabled = true;
-->
</script>
<div style=\"text-align: center;\">
<button class=\"irul\" type=\"submit\" name=\"regola_3\" value=\"1\"><div>".mex("Inserisci o modifica la regola",$pag)." 3</div></button>
</div></div></form><hr style=\"width: 95%\">";

if ($tipotariffa_regola3 and preg_replace("/tariffa[0-9]+/","",$tipotariffa_regola3) == "") {
echo "<script type=\"text/javascript\">
<!--
var sel_tr3 = document.getElementById(\"t3\");
sel_tr3.value = '$tipotariffa_regola3';
aggiorna_val_reg3();
-->
</script>
";
} # fine if ($tipotariffa_regola3 and...
} # fine if ((!$mostra_solo_regola or $mostra_solo_regola == 3) and $priv_mod_reg2 == "s")


if ((!$mostra_solo_regola or $mostra_solo_regola == 4) and $id_utente == 1) {
echo "<script type=\"text/javascript\">
<!--
function aggiorna_val_reg4 () {
var sel_tar = document.getElementById(\"t4\");
var num_sel = sel_tar.selectedIndex;
var tariffa = sel_tar.options[num_sel].value;
var sel_ute = document.getElementById(\"v4\");
if (tariffa == '') sel_ute.disabled = true;
else sel_ute.disabled = false;
sel_ute.selectedIndex = 0;
var ind_val = '';
";
$regole4 = esegui_query("select * from $tableregole where tariffa_per_utente != ''");
$num_regole4 = numlin_query($regole4);
for ($num1 = 0 ; $num1 < $num_regole4 ; $num1++) {
$tariffa = risul_query($regole4,$num1,'tariffa_per_utente');
$utente_t = risul_query($regole4,$num1,'iddatainizio');
echo "if (tariffa == \"$tariffa\") ind_val = \"$utente_t\";
";
} # fine for $num1
echo "var num_opt = sel_ute.options.length;
for (n1 = 0 ; n1 < num_opt ; n1++) {
if (sel_ute.options[n1].value == ind_val) sel_ute.selectedIndex = n1;
}
}
-->
</script>

";

$tutti_utenti = esegui_query("select * from $tableutenti order by idutenti");
$num_tutti_utenti = numlin_query($tutti_utenti);
if ($num_tutti_utenti > 1) {
unset($option_select_utenti);
for ($num1 = 0 ; $num1 < $num_tutti_utenti ; $num1++) {
$idutenti = risul_query($tutti_utenti,$num1,'idutenti');
if ($idutenti != 1) {
$nome_utente_option = risul_query($tutti_utenti,$num1,'nome_utente');
$option_select_utenti .= "<option value=\"$idutenti\">$nome_utente_option</option>";
} # fine if ($idutenti != $id_utente_inserimento)
else $nome_utente1 = risul_query($tutti_utenti,$num1,'nome_utente');
} # fine for $num1
echo "<div style=\"text-align: center;\">".mex("Regola di assegnazione",$pag)." <b>4</b>.</div><br>
<form accept-charset=\"utf-8\" method=\"post\" action=\"crearegole.php\"><div>
<input type=\"hidden\" name=\"anno\" value=\"$anno\">
<input type=\"hidden\" name=\"id_sessione\" value=\"$id_sessione\">
<input type=\"hidden\" name=\"inserisci\" value=\"SI\">
".mex("Quando l'utente amministratore",$pag)." ($nome_utente1) ".mex("sceglie la tariffa",$pag)."
<select name=\"tipotariffa\" id=\"t4\" onchange=\"aggiorna_val_reg4()\">
<option value=\"\" selected>----</option>";
for ($numtariffa = 1 ; $numtariffa <= $numero_tariffe ; $numtariffa = $numtariffa + 1) {
$tariffa = "tariffa".$numtariffa;
$tariffa_vedi = mex("tariffa","prenota.php").$numtariffa;
$nometariffa = risul_query($rigatariffe,0,$tariffa);
if ($nometariffa != "") $nometariffa_vedi = " ($nometariffa)";
else $nometariffa_vedi = "";
echo "
<option value=\"$tariffa\">$tariffa_vedi$nometariffa_vedi</option>";
} # fine for $numtariffa
echo "</select>
 ".mex("fai risultare come se l'utente",$pag)."
 <select id=\"v4\" name=\"id_utente_inserimento\">
<option value=\"\" selected>----</option>
$option_select_utenti
</select>
 ".mex("avesse inserito la prenotazione e l'eventuale cliente",$pag).".<br>";
echo "<script type=\"text/javascript\">
<!--
var sel_box = document.getElementById(\"v4\");
sel_box.disabled = true;
-->
</script>
<div style=\"text-align: center;\">
<button class=\"irul\" type=\"submit\" name=\"regola_4\" value=\"1\"><div>".mex("Inserisci o modifica la regola",$pag)." 4</div></button>
</div></div></form><hr style=\"width: 95%\">";
} # fine if ($num_tutti_utenti > 1)
} # fine if ((!$mostra_solo_regola or $mostra_solo_regola == 4) and $id_utente == 1)


if (!$mostra_solo_regola and $priv_ins_costi_agg != "n") {
echo "<table><tr><td style=\"height: 6px;\"></td></tr></table>
<form accept-charset=\"utf-8\" method=\"post\" action=\"creaprezzi.php\"><div>
<input type=\"hidden\" name=\"anno\" value=\"$anno\">
<input type=\"hidden\" name=\"id_sessione\" value=\"$id_sessione\">
<input type=\"hidden\" name=\"ins_rapido_costo\" value=\"SI\">
<input type=\"hidden\" name=\"origine\" value=\"crearegole.php\">
".mex("Inserimento rapido di un nuovo costo aggiuntivo per",'creaprezzi.php')."
 <select name=\"tipocostoagg\">
<option value=\"perm_min\">".mex("permanenza minima",'creaprezzi.php')."</option>
<option value=\"num_bamb\">".mex("numero di bambini",'creaprezzi.php')."</option>
<option value=\"letto_agg\">".mex("letto aggiuntivo",'creaprezzi.php')."</option>
<option value=\"off_spec\">".mex("offerta speciale",'creaprezzi.php')."</option>
</select>
<button class=\"aexc\" type=\"submit\"><div>".mex("inserisci",'creaprezzi.php')."</div></button>.
<table><tr><td style=\"height: 6px;\"></td></tr></table>
</div></form><hr style=\"width: 95%\">";
} # fine if (!$mostra_solo_regola and $priv_ins_costi_agg != "n")


echo "<div style=\"text-align: center;\"><br>";
if (!$origine) {
echo "<form accept-charset=\"utf-8\" method=\"post\" action=\"visualizza_tabelle.php\"><div>
<input type=\"hidden\" name=\"anno\" value=\"$anno\">
<input type=\"hidden\" name=\"id_sessione\" value=\"$id_sessione\">
<input type=\"hidden\" name=\"tipo_tabella\" value=\"regole\">
<button class=\"rule\" type=\"submit\"><div>".mex("Vedi le regole già inserite",$pag)."</div></button>
</div></form><br>
<form accept-charset=\"utf-8\" method=\"post\" action=\"inizio.php\"><div>
<input type=\"hidden\" name=\"anno\" value=\"$anno\">
<input type=\"hidden\" name=\"id_sessione\" value=\"$id_sessione\">
<button class=\"bkmm\" type=\"submit\"><div>".mex("Torna al menù principale",$pag)."</div></button>
<br></div></form>";
} # fine if (!$origine)
else {
echo "<form accept-charset=\"utf-8\" method=\"post\" action=\"".controlla_pag_origine($origine)."\"><div>
<input type=\"hidden\" name=\"anno\" value=\"$anno\">
<input type=\"hidden\" name=\"id_sessione\" value=\"$id_sessione\">
<button class=\"gobk\" type=\"submit\"><div>".mex("Torna indietro",$pag)."</div></button>
<br></div></form>";
} # fine else if (!$origine)
echo "<br></div>";



} # fine else if ($inserisci)



if ($tema[$id_utente] and $tema[$id_utente] != "base" and @is_dir("./themes/".$tema[$id_utente]."/php")) include("./themes/".$tema[$id_utente]."/php/foot.php");
else include("./includes/foot.php");


} # fine if ($anno_utente_attivato == "SI" and ($priv_mod_reg1 != "n" or $priv_mod_reg2 != "n"))
} # fine if ($id_utente)



?>
