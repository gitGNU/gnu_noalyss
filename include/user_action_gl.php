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
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
/* $Revision$ */

/*! \file
 * \brief included file for the great ledger
 */

include_once ("user_common.php");
include_once("class_user.php");
require_once("class_iselect.php");
require_once('class_acc_ledger.php');
$cn=new Database($gDossier);

require_once('class_iposte.php');
require_once('class_ipopup.php');
$search_card=new IPopup('ipop_card');
$search_card->title=_('Recherche de fiche');
$search_card->value='';
echo $search_card->input();

echo IPoste::ipopup('ipop_account');

echo '<div class="content">';

$Ledger=new Acc_Ledger($cn,0);
if ( !isset($_REQUEST['p_jrn']))
{
    $Ledger->id=-1;
}
else
    $Ledger->id=$_REQUEST['p_jrn'];
echo $Ledger->display_search_form();

/*  compute the sql stmt */
list($sql,$where)=$Ledger->build_search_sql($_GET);

$max_line=$cn->count_sql($sql);

$step=$_SESSION['g_pagesize'];
$page=(isset($_GET['offset']))?$_GET['page']:1;
$offset=(isset($_GET['offset']))?$_GET['offset']:0;
$bar=jrn_navigation_bar($offset,$max_line,$step,$page);


echo HtmlInput::hidden("p_action",$_REQUEST['p_action']);
if (isset($_REQUEST['sa'])) echo HtmlInput::hidden("sa",$_REQUEST['sa']);
if (isset($_REQUEST['sb'])) echo HtmlInput::hidden("sb",$_REQUEST['sb']);
if (isset($_REQUEST['sc'])) echo HtmlInput::hidden("sc",$_REQUEST['sc']);
if (isset($_REQUEST['f_id'])) echo HtmlInput::hidden("f_id",$_REQUEST['f_id']);


echo dossier::hidden();
echo $bar;
list($count,$html)= $Ledger->list_operation($sql,$offset);
echo $html;
echo $bar;


?>

</div>
