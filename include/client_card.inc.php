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
 * \brief this file will handle all the actions for a specific customer (
 * contact,operation,invoice and financial)
 * include from client.inc.php and concerned only the customer card and
 * the customer category
 */

  /* $sub_action = sb = detail */
  /* $cn database conx */
 $return=new IAction();
 $return->name='retour';
 $return->label='Retour';
 $return->value='?p_action=client&'.$str_dossier;

$root="?p_action=client&sb=detail&f_id=".$_REQUEST["f_id"].'&'.$str_dossier;
$ss_action=( isset ($_REQUEST['sc'] ))? $_REQUEST['sc']: '';
switch ($ss_action) {
case 'dc':
  $def=1;
  break;
case 'sv':			/* all the actions (mail,meeting...) */
  $def=2;
  break;
case 'cn':
  $def=3;
  break;
case 'op':
  $def=4;
  break;
case 'fa':
  $def=5;
  break;
case 'fi':
  $def=6;
  break;
default:
  $def=1;
  $ss_action='dc';
}
echo '<div class="u_subtmenu">';
echo ShowItem(array(
		    array($root."&sc=dc",'Fiche','Détail de la fiche',1),
		    array($root.'&sc=sv','Suivi','Suivi client, devis, bon de commande, courrier',2),
		    array($root.'&sc=cn','Contact','Liste de contacts de ce client',3),
		    array($root.'&sc=op','Opérations','Toutes les opérations',4),
		    array($root.'&sc=fa','Facture','Liste des factures et encodage nouvelles',5),
		    array($root.'&sc=fi','Financier','Liste de tous les mouvements financiers de ce client',6)
		    ),
	      'H',"mtitle","mtitle",$def,' width="100%"');
echo '</div>';

//---------------------------------------------------------------------------
// Show Detail of a card and category
//---------------------------------------------------------------------------
if ( $ss_action == 'dc' )
{
  require_once('detail_client.inc.php');
}
//---------------------------------------------------------------------------
// Follow up : mail, bons de commande, livraison, rendez-vous...
//---------------------------------------------------------------------------
if ( $ss_action == 'sv' ) {
  require_once('suivi_client.inc.php');
}
