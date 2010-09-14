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
require_once('class_contact.php');
require_once('class_ipopup.php');

echo ICard::ipopup('ipopcard');

/* $sub_action = sb = detail */
/* $cn database conx */
$return=new IAction();
$return->name='retour';
$return->label='Retour';
$return->value='?p_action=adm&'.$str_dossier;
$root="?p_action=adm&sb=detail&f_id=".$_REQUEST["f_id"].'&'.$str_dossier;
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
default:
    $def=1;
    $ss_action='dc';
}
$f=new Fiche($cn,$_REQUEST['f_id']);
echo '<div class="u_subtmenu">';
echo ShowItem(array(
                  array($root."&sc=dc",_('Fiche'),_('Détail de la fiche'),1),
                  array($root.'&sc=sv',_('Suivi'),_('Suivi adm, devis, bon de commande, courrier'),2),
                  array($root.'&sc=cn',_('Contact'),_('Liste de contacts de cette administration'),3),
                  array($root.'&sc=op',_('Opérations'),_('Toutes les opérations'),4),
                  array('?p_action=adm&'.dossier::get(),_('Retour liste'),_('Retour à la liste des administration'),5)
                  ),
                  'H',"mtitle","mtitle",$def,' ');
echo '</div>';
echo '<div class="gest_name">';
echo '<h2 class="gest_name">'.$f->get_quick_code()." ".$f->strAttribut(ATTR_DEF_NAME).'</h2>';
echo '</div>';
echo '</div>';

//---------------------------------------------------------------------------
// Show Detail of a card and category
//---------------------------------------------------------------------------
if ( $ss_action == 'dc' )
{
    require_once('detail_adm.inc.php');
}
//---------------------------------------------------------------------------
// Follow up : mail, bons de commande, livraison, rendez-vous...
//---------------------------------------------------------------------------
if ( $ss_action == 'sv' )
{
    require_once('suivi_adm.inc.php');
}
/*----------------------------------------------------------------------
 * Operation all the operation of this customer
 *
 * ----------------------------------------------------------------------*/
if ( $ss_action == 'op')
{
    require_once('operation_adm.inc.php');
}
/*----------------------------------------------------------------------
 * All the contact
 *
 *----------------------------------------------------------------------*/
if ( $ss_action == 'cn')
{
    echo '<div class="content">';
    echo dossier::hidden();
    $f=new Fiche($cn,$_REQUEST['f_id']);
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
    $f_add_button->set_attribute('ipopup','ipopcard');

    $f_add_button->set_attribute('filter',$filter);
    $f_add_button->javascript=" select_card_type(this);";

    echo $f_add_button->input();
    echo '</div>';
}
