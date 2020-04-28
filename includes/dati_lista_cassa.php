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




if (!@is_array($altre_valute)) {
$canc_altre_valute = 1;
if ($tablepersonalizza and function_exists('altre_valute')) $altre_valute = altre_valute();
else $altre_valute = array();
} # fine if (!@is_array($altre_valute))
else $canc_altre_valute = 0;
$tabelle_lock = array();
$altre_tab_lock = array($tablecosti,$tablecasse);
$tabelle_lock = lock_tabelle($tabelle_lock,$altre_tab_lock);
$num_r = 0;
$cassa_esist = esegui_query("select nome_cassa from $tablecasse where idcasse = '".aggslashdb($lista_cassa)."' ");
if (numlin_query($cassa_esist)) {
if ($lista_cassa == 1) $cond_cassa = "(nome_cassa = '' or nome_cassa is NULL) ";
else $cond_cassa = "nome_cassa = '".aggslashdb(risul_query($cassa_esist,0,'nome_cassa'))."' ";
$num_pagamenti = 0;
for ($num1 = 1 ; $num1 <= $num_ripeti ; $num1++) {
$pagamenti = esegui_query("select val_costo,tipo_costo,valuta,costo_valuta,persona_costo,metodo_pagamento,datainserimento from $tablecosti where $cond_cassa and (tipo_costo = 'e' or tipo_costo = 's') order by tipo_costo,datainserimento");
$num_pagamenti2 = numlin_query($pagamenti);
$num_r++;
for ($num2 = 0 ; $num2 < $num_pagamenti2 ; $num2++) {
${"data_paga".$num_pagamenti."_".$num_r} = substr(risul_query($pagamenti,$num2,'datainserimento'),0,10);
${"utente_paga".$num_pagamenti."_".$num_r} = risul_query($pagamenti,$num2,'persona_costo');
if (strcmp(risul_query($pagamenti,$num2,'metodo_pagamento'),"")) ${"metodo_paga".$num_pagamenti."_".$num_r} = risul_query($pagamenti,$num2,'metodo_pagamento');
${"saldo_paga".$num_pagamenti."_".$num_r} = risul_query($pagamenti,$num2,'val_costo');
if (risul_query($pagamenti,$num2,'tipo_costo') == "s") ${"saldo_paga".$num_pagamenti."_".$num_r} = (${"saldo_paga".$num_pagamenti."_".$num_r} * -1);
$valuta_paga = risul_query($pagamenti,$num2,'valuta');
if ($valuta_paga) {
$valuta_paga = explode(">",$valuta_paga);
${"valuta_paga".$num_pagamenti."_".$num_r} = $valuta_paga[0];
${"tasso_cambio_paga".$num_pagamenti."_".$num_r} = $valuta_paga[1];
${"valore_valuta_paga".$num_pagamenti."_".$num_r} = converti_valuta(${"saldo_paga".$num_pagamenti."_".$num_r},$valuta_paga[1],$valuta_paga[2]);
} # fine if ($valuta_paga)
$num_pagamenti++;
} # fine for $num2
${"num_pagamenti_".$num_r} = $num_pagamenti;
} # fine for $num1
} # fine if (numlin_query($cassa_esist))

unset($valuta_paga);
unset($num_pagamenti);
if ($canc_altre_valute) unset($altre_valute);
unset($canc_altre_valute);

$num_ripeti = $num_r;
unset($num_r);
if ($tabelle_lock) unlock_tabelle($tabelle_lock);



?>