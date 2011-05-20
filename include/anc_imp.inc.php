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
 *
 *
 * \brief this file is included for printing the analytic
 * accountancy.
 *
 */
require_once("class_ihidden.php");
require_once('class_anc_operation.php');
require_once('class_anc_plan.php');
require_once('ac_common.php');

//-- the menu
$menu=array(array("?p_action=ca_imp&sub=listing&$str_dossier",_("Historique"),_("Historique des opérations"),"listing"),
            array("?p_action=ca_imp&sub=ancgl&$str_dossier",_("grand livre"),_("Grand livre d' plan analytique"),"ancgl"),
            array("?p_action=ca_imp&sub=bs&$str_dossier",_("Balance simple"),_("Balance simple d'un plan analytique"),"bs"),
            array("?p_action=ca_imp&sub=bc2&$str_dossier",_("Balance croisée"),_("Balance croisée de 2 plans analytiques"),"bc2"),
	    array("?p_action=ca_imp&sub=tab&$str_dossier",_("Tableau"),_("Tableau lié à la comptabilité"),'tab'),
	    array("?p_action=ca_imp&sub=lico&$str_dossier",_("Balance comptabilité"),_("Lien entre comptabilité et Comptabilité analytique"),'lico'),
	    array("?p_action=ca_imp&sub=groupe&$str_dossier",_("Groupe"),_("Balance par groupe"),'gr'),

           );
$sub=(isset($_GET['sub']))?$_GET['sub']:'no';

echo '<div class="content"  style="width:88%;margin-left:12%">';
echo ShowItem($menu,"H","mtitle","mtitle",$sub);
echo '</div>';

echo '<div class="content" style="width:80%;margin-left:10%">';

$hidden=new IHidden();
$str_hidden=$hidden->input("p_action","ca_imp");
$str_hidden.=$hidden->input("sub",$sub);

// select following the sub action
//------------------------------------------------------------------------------
// listing
if ( $sub=='listing')
{
    require_once ('class_anc_listing.php');
    $list=new Anc_Listing($cn);
    $list->get_request();

    echo $list->display_form($str_hidden);
    //---- result
    if ( isset($_GET['result']) )
    {
        echo '<div class="content">';

        //--------------------------------
        // export Buttons
        //---------------------------------
        echo $list->show_button($str_hidden);
        echo $list->display_html();
        echo '</div>';
    }
    echo '</div>';
}

//------------------------------------------------------------------------------
// Simple balance
if ($sub == 'bs')
{
    require_once ('class_anc_balance_simple.php');
    $bs=new Anc_Balance_Simple($cn);
    $bs->get_request();
    echo '<form method="get">';
    echo $bs->display_form($str_hidden);
    echo '</form>';
    if ( isset($_GET['result']))
    {
        echo $bs->show_button($str_hidden);
        echo $bs->display_html();
    }
}

//------------------------------------------------------------------------------
// crossed balance
if ( $sub == 'bc2')
{
    require_once ('class_anc_balance_double.php');
    $bc=new Anc_Balance_Double($cn);
    $bc->get_request ();
    echo '<form method="get">';
    echo $bc->display_form($str_hidden);
    echo '</form>';
    if ( isset($_GET['result']))
    {
        echo $bc->show_button($str_hidden);
        echo $bc->display_html();
    }
}
//----------------------------------------------------------------------
// Table linked between accountancy and analytic
//---------------------------------------------------------------------------
if ( $sub == 'tab')
  {
    require_once('class_anc_table.php');
    $tab=new Anc_Table($cn);
    $tab->get_request();
    echo '<form method="get">';
    echo $tab->display_form($str_hidden);
    echo '<p>'.HtmlInput::submit('Recherche','Recherche').'</p>';

    echo '</form>';
    if ( isset($_GET['result']))
    {
        echo $tab->show_button($str_hidden);
	$tab->display_html();
    }
  }

//----------------------------------------------------------------------
//  linked between accountancy and analytic
//---------------------------------------------------------------------------
if ( $sub == 'lico')
  {
    require_once('class_anc_acc_list.php');
    $tab=new Anc_Acc_List($cn);
    $tab->get_request();
    echo '<form method="get">';
    echo $tab->display_form($str_hidden);
    echo '<p>'.HtmlInput::submit('Recherche','Recherche').'</p>';

    echo '</form>';
    if ( isset($_GET['result']))
    {
        echo $tab->show_button($str_hidden);
	$tab->display_html();
    }
  }

//---------------------------------------------------------------------------
if ( $sub == 'ancgl')
  {
    require_once('class_anc_grandlivre.php');
    $gl=new Anc_GrandLivre($cn);
    $gl->get_request();
    echo '<form method="get">';
    echo $gl->display_form($str_hidden);
    echo '<p>'.HtmlInput::submit('Recherche','Recherche').'</p>';
    echo '</form>';
    if ( isset($_GET['result']))
    {
	echo $gl->display_html();
    }
  }
