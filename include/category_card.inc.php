<?php
/*
 *   This file is part of NOALYSS.
 *
 *   NOALYSS is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   NOALYSS is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with NOALYSS; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// Copyright Author Dany De Bontridder danydb@aevalys.eu

/*!\file
 * \brief this file will handle all the actions for a specific customer (
 * contact,operation,invoice and financial)
 * include from client.inc.php and concerned only the customer card and
 * the customer category
 */
if ( ! defined ('ALLOWED') ) die('Appel direct ne sont pas permis');
require_once('class_contact.php');

$str_dossier=Dossier::get();
/* $sub_action = sb = detail */
/* $cn database conx */
$root='?ac='.$_REQUEST['ac']."&sb=detail&f_id=".$_REQUEST["f_id"].'&'.$str_dossier;
$ss_action=( isset ($_REQUEST['sc'] ))? $_REQUEST['sc']: '';
switch ($ss_action)
{
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
case 'let':
    $def=6;
    break;
case 'bal':
  $def=7;
  break;
default:
    $def=1;
    $ss_action='dc';
}
$f=new Fiche($cn,$_REQUEST['f_id']);

echo '<div class="content">';
echo $f->get_gestion_title();
echo ShowItem(array(
                  array($root."&sc=dc",_('Fiche'),_('Détail de la fiche'),1),
                  array($root.'&sc=sv',_('Suivi'),_('Suivi Fournisseur, client, banque, devis, bon de commande, courrier'),2),
                  array($root.'&sc=cn',_('Contact'),_('Liste de contacts'),3),
                  array($root.'&sc=op',_('Opérations'),_('Toutes les opérations'),4),
                  array($root.'&sc=bal',_('Balance'),_('Balance du fournisseur'),7),
                  array($root.'&sc=let',_('Lettrage'),_('Opérations & Lettrages'),6)
                  ),
                  'H',"mtitle","mtitle",$def,' ');
echo '</div>';
echo '<div>';


//---------------------------------------------------------------------------
// Show Detail of a card and category
//---------------------------------------------------------------------------
if ( $ss_action == 'dc' )
{
    require_once('category_detail.inc.php');
}
//---------------------------------------------------------------------------
// Follow up : mail, bons de commande, livraison, rendez-vous...
//---------------------------------------------------------------------------
if ( $ss_action == 'sv' )
{
    require_once('category_followup.inc.php');
}
/*----------------------------------------------------------------------
 * Operation all the operation of this customer
 *
 * ----------------------------------------------------------------------*/
if ( $ss_action == 'op')
{
    require_once('category_operation.inc.php');
}
/*-------------------------------------------------------------------------
 * Balance of the card
 *-------------------------------------------------------------------------*/
if ( $ss_action=='bal')
  {
    require_once('balance_card.inc.php');
  }
/*----------------------------------------------------------------------
 * All the contact
 *
 *----------------------------------------------------------------------*/
if ( $ss_action == 'cn')
{
    echo '<div class="content">';

	echo dossier::hidden();
	$f = new Fiche($cn, $_REQUEST['f_id']);
	$contact=new Contact($cn);
    $contact->company=$f->get_quick_code();
    echo $contact->summary("");

    $sql=' select fd_id from fiche_def where frd_id='.FICHE_TYPE_CONTACT;
    $filter=$cn->make_list($sql);
    if ( empty ($filter))
    {
        echo '<span class="notice">';
        echo _("Vous devez aller dans fiche et créer une catégorie pour les contacts");
        echo '</span>';
        exit();
    }
    /* Add button */
    $f_add_button=new IButton('add_card');
    $f_add_button->label=_('Créer une nouvelle fiche');

    $f_add_button->set_attribute('filter',$filter);
    $f_add_button->javascript=" select_card_type(this);";

    echo $f_add_button->input();
    echo '</div>';
}
/*----------------------------------------------------------------------------
 * Lettering
 *----------------------------------------------------------------------------*/
if ( $def==6 )
{
    require_once('lettering.gestion.inc.php');
}
