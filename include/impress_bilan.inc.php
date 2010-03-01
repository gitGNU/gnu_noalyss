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
require_once ('class_acc_bilan.php');
//ini_set("memory_limit","150M");
/*! \file
 * \brief form who call the printing of the bilan in RTF
 *        file included by user_impress
 *
 * some variable are already defined ($cn, $User ...)
 */

//-----------------------------------------------------
// Show the jrn and date
//-----------------------------------------------------
require_once('class_database.php');

//-----------------------------------------------------
// Form
//-----------------------------------------------------
$filter_year=" where p_exercice='".$User->get_exercice()."'";
$bilan=new Acc_Bilan($cn);
echo '<div class="content">';

echo '<FORM  METHOD="GET">';
echo HtmlInput::hidden('p_action','impress');
echo HtmlInput::hidden('type','bilan');
echo dossier::hidden();
echo $bilan->display_form ($filter_year);
echo HtmlInput::submit('verif',_('Verification comptabilite'));
echo '</FORM>';



if ( isset($_GET['verif'])) {
  echo '<h2> Etape 2 :Impression </h2>';
  $bilan->get_request_get();
  $bilan->verify();
  require_once ('verif_bilan.inc.php');
  echo '<FORM METHOD="GET" ACTION="bilan.php">';
  echo dossier::hidden();
  echo HtmlInput::hidden('b_id',$bilan->id);

  echo HtmlInput::hidden('from_periode',$bilan->from);
  echo HtmlInput::hidden('to_periode',$bilan->to);
  echo HtmlInput::submit('Impression','Impression');
  echo '</form>';

 }
echo _('<span class="notice"> Attention : si le bilan n\'est pas équilibré.<br> Vérifiez <ul>
<li>L\'affectation du résultat est fait</li>
<li>Vos comptes actifs ont  un solde débiteur (sauf les comptes dit inversés)</li> 
<li> les comptes passifs ont un solde créditeur (sauf les comptes dit inversés) </li>
</ul> 
Utilisez la balance des comptes pour vérifier. </span>');

echo '</div>';
?>
