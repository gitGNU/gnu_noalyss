<?php
/*
 *   This file is part of PhpCompta.
 *
 *   PhpCompta is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   PhpCompta is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with PhpCompta; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
/* $Revision$ */

// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr

/*!\file
 * \brief respond ajax request, the get contains
 *  the value :
 * - l for ledger 
 * - gDossier
 * - PHPSESSID
 * Must return at least tva, htva and tvac

 */

require_once ('constant.php');
require_once ('postgres.php');
require_once ('debug.php');
require_once('class_dossier.php');
require_once('class_acc_ledger.php');
require_once ('class_user.php');

// Check if the needed field does exist
extract ($_GET);
foreach (array('l','gDossier') as $a) {
  if ( ! isset (${$a}) )   { echo "error $a is not set "; exit();} 
}
if ( ereg('^[0-9]+$',$l) == false ) {exit();}
$cn=DbConnect(dossier::id());
$User=new User($cn);
$User->Check();

$Ledger=new Acc_Ledger($cn,$l);
$prop=$Ledger->get_propertie();
$pj_seq=$Ledger->guess_pj();
$string='{"pj":"'.$pj_seq.'"}';

header("Content-type: text/json; charset: utf8",true);
echo $string;


?>

