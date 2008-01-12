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
/*! \file
 * \brief form who call the printing of the bilan in RTF
 *        file included by user_impress
 *
 * some variable are already defined ($cn, $User ...)
 */

//-----------------------------------------------------
// Show the jrn and date
//-----------------------------------------------------
include_once("postgres.php");
/*$ret=make_array($cn,"select fr_id,fr_label
                 from formdef
                 order by fr_label");
*/
//-----------------------------------------------------
// Form
//-----------------------------------------------------
$filter_year=" where p_exercice='".$User->get_exercice()."'";
$bilan=new Acc_Bilan($cn);
echo '<div class="u_content">';

echo '<FORM  METHOD="GET">';
echo widget::hidden('p_action','impress');
echo widget::hidden('type','bilan');
echo dossier::hidden();
echo $bilan->display_form ($filter_year);
echo widget::submit_button('verif','Verification comptabilite');
echo '</FORM>';



if ( isset($_GET['verif'])) {
  echo '<h2> Etape 2 :Impression </h2>';
  $bilan->get_request_get();
  $bilan->verify();
  require_once ('verif_bilan.inc.php');
  echo '<FORM METHOD="GET" ACTION="bilan.php">';
  echo dossier::hidden();
  echo widget::hidden('b_id',$bilan->id);

  echo widget::hidden('from_periode',$bilan->from);
  echo widget::hidden('to_periode',$bilan->to);
  echo widget::submit_button('Impression','Impression');
  echo '</form>';

 }
echo '<span class="notice"> Attention : si le bilan n\'est pas &eacute;quilibr&eacute;.<br> V&eacute;rifiez <ul>
<li>L\'affectation du r&eacute;sultat est fait</li>
<li>Vos comptes actifs ont  un solde d&eacute;biteur (sauf les comptes dit invers&eacute;s)</li> 
<li> les comptes passifs ont un solde cr&eacute;diteur (sauf les comptes dit invers&eacute;s) </li>
</ul> 
Utilisez la balance des comptes pour v&eacute;rifier. </span>';

echo '</div>';
?>
