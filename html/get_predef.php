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
 * Must return at least tva, htva and tvac
 */

require_once ('constant.php');
require_once ('class_database.php');
require_once ('debug.php');
require_once('class_dossier.php');
require_once('class_pre_operation.php');
// Check if the needed field does exist
extract ($_GET);
foreach (array('l','t','d','gDossier') as $a) {
  if ( ! isset (${$a}) )   { echo "error $a is not set "; exit();} 
}
$cn=new Database(dossier::id());
$op=new Pre_operation_detail($cn);
$op->set('ledger',$l);
$op->set('ledger_type',$t);
$op->set('direct',$d);
$array=$op->get_operation();
$string='{"count":"'.count($array).'"';
$idx=0;
if (! empty($array))
  foreach ($array as $a) {
    $string.=',"value'.$idx.'":"'.$a['value'].'",';
    $string.='"label'.$idx.'":"'.$a['label'].'"';
    $idx++;
  }
$string.="}";

header("Content-type: text/json; charset: utf8",true);
echo $string;


?>

