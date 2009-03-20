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
include_once("class_widget.php");
/*! \file
 * \brief print the listing of customer for vat
 */

//-----------------------------------------------------
// Show the jrn and date
//-----------------------------------------------------

//-----------------------------------------------------
// Submit Html
//-----------------------------------------------------
if ( isset($_POST['bt_html'] )) {
  require_once("class_customer.php");
  $customer=new Customer($cn);
  $a_Res=$customer->VatListing($_POST['year']);

echo "
<div class=\"content\"
<form method=\"post\" action=\"listing_client.php\"> ".
  dossier::hidden()."
<input type=\"submit\" name=\"bt_disk\" value=\"expérimental !! déclaration magnétique\" disable>
<input type=\"hidden\" name=\"year\" value=\"".$_POST['year']."\">
</form>
<table class=\"result\">
<tr>
<th> Quick Code </th>
<th> Poste Comptable </th>
<th> Nom </th>
<th> Numéro TVA </th>
<th> Montant HTVA </th>
<th> Montant TVA </th>
</tr>
";
$aPoste=array();
 foreach ($a_Res as $key=>$elt) {
   if ( $elt['amount'] == 0 && $elt['tva'] == 0 )
     continue;
   echo "<tr>".
     '<td>'.$key.'</td>'.
     "<td>".$elt['poste_comptable']."</td>".
     "<td>".$elt['name']."</td>".
     "<td>".$elt['vat_number']."</td>".
     "<td align=\"right\">".sprintf("% 8.2f",$elt['amount'])."</td>".
     "<td align=\"right\">".sprintf("% 8.2f",$elt['tva'])."</td>".
     "</tr>";
 }
 echo "</table>";
 echo "</div>";
  return;
 }

//-----------------------------------------------------
// Form
//-----------------------------------------------------
$w=new widget("select");
$w->table=1;

echo '<div class="u_redcontent">';
echo '<FORM ACTION="?p_action=impress&type=list_client" METHOD="POST">';
echo dossier::hidden();
echo '<TABLE>';

print '<TR>';
$year=make_array($cn,"select distinct extract(year from jr_date), extract(year from jr_date) from jrn");
if ( sizeof($year) == 0 ) 
{
  echo "Aucun enregistrement dans les journaux";
  exit();
}
$w->label="Année concernée";
print $w->IOValue('year',$year);
print "</TR>";
echo '</TABLE>';
print widget::submit('bt_html','Impression');

echo '</FORM>';

echo '</div>';

?>
