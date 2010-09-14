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
/*! \file
 * \brief Search module
 */
require_once('class_dossier.php');
include_once("ac_common.php");
include_once ("constant.php");
require_once('class_acc_ledger.php');
require_once('class_ipopup.php');
html_page_start($_SESSION['g_theme']);



echo js_include('prototype.js');
echo js_include('acc_ledger.js');
echo js_include('card.js');
echo js_include('scripts.js');
echo JS_INFOBULLE;
echo js_include('accounting_item.js');
echo js_include('scriptaculous.js');
echo js_include('effects.js');
echo js_include('controls.js');
echo js_include('dragdrop.js');

$search_card=new IPopup('ipop_card');
$search_card->title=_('Recherche de fiche');
$search_card->value='';

$gDossier=dossier::id();

require_once('class_database.php');
/* Admin. Dossier */

$cn=new Database($gDossier);
include_once ('class_user.php');
$User=new User($cn);
$User->Check();

// display a search box

$ledger=new Acc_Ledger($cn,0);
$search_box=$ledger->search_form('ALL',1);
echo '<div class="content">';
echo IPoste::ipopup('ipop_account');
echo $search_card->input();

echo '<form method="GET">';
echo $search_box;
echo HtmlInput::submit("viewsearch",_("Recherche"));
echo '</form>';

//-----------------------------------------------------
// Display search result
//-----------------------------------------------------
if ( isset ($_GET['viewsearch']))
{

    // Navigation bar
    $step=$_SESSION['g_pagesize'];
    $page=(isset($_GET['offset']))?$_GET['page']:1;
    $offset=(isset($_GET['offset']))?$_GET['offset']:0;
    if (count ($_GET) == 0)
        $array=null;
    else
        $array=$_GET;
    $array['p_action']='ALL';
    list($sql,$where)=$ledger->build_search_sql($array);
    // Count nb of line
    $max_line=$cn->count_sql($sql);

    list($count,$a)=$ledger->list_operation($sql,$offset,0);
    $bar=jrn_navigation_bar($offset,$max_line,$step,$page);

    echo $bar;
    echo $a;
    echo $bar;

}
echo '</div>';
?>
