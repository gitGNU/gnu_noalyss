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
/* $Revision: 4215 $ */
// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr
/*! \file
 * \brief Search module
 */
require_once('class_dossier.php');
include_once("ac_common.php");
require_once('class_acc_ledger.php');


$gDossier=dossier::id();

require_once('class_database.php');
/* Admin. Dossier */

$cn=new Database($gDossier);
include_once ('class_user.php');
$User=new User($cn);
$User->Check();
$act=$User->check_dossier($gDossier);
// display a search box


/**
 * @todo si ajax alors pas de form GET mais plutot une fonction
 * javascript pour chercher
 */
$base=basename($_SERVER['SCRIPT_NAME']);
$inside=false;
$ledger=new Acc_Ledger($cn,0);
$ledger->type='ALL';
if (isset($_GET['amount_id']))
{
	put_global(array(
				array("key"=>'amount_min','value'=>$_GET['amount_id']),
				array("key"=>'amount_max','value'=>$_GET['amount_id'])
				));
}

$search_box=$ledger->search_form('ALL',1,'search_op');

if ($base == 'recherche.php' || $base == 'do.php')
	{
	echo '<div class="content" >';
	echo	 '<form method="GET">';
	}
	else
	{
		$div='search_op';
		$action="";
		$callback="";
		require 'template/search_top.php';
		echo '<form onsubmit="search_operation(this);return false">';
		echo HtmlInput::get_to_hidden(array('ctlc','ledger'));
		$inside=true;
	}

echo $search_box;
echo HtmlInput::submit("viewsearch",_("Recherche"));
echo HtmlInput::button_close('search_op');
echo '</form>';

if ( isset ($_GET['amount_min'])&& isset($_GET['amount_max'])&& ($_GET['amount_max']!=0 ||$_GET['amount_min']!=0 ))
{
	$_GET['viewsearch']=1;
	put_global(
			array
				(
				array('key'=>'ledger_type','value'=>'ALL'),
				array('key'=>'date_start','value'=>'ALL'),
				array('key'=>'date_end','value'=>'ALL'),
				array('key'=>'desc','value'=>''),
				array('key'=>'qcode','value'=>''),
				array('key'=>'accounting','value'=>'')
				)

			);

}
//-----------------------------------------------------
// Display search result
//-----------------------------------------------------
if ( isset ($_GET['viewsearch']) )
{

    // Navigation bar
    $step=25;
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
    list($count,$content)=$ledger->list_operation_to_reconcile($sql);
    $bar=jrn_navigation_bar($offset,$max_line,$step,$page);

   if (! $inside ) {
	   echo $bar;

   }   else
   {
	    if ($step<$max_line ) echo '<span class="notice">Nombre d\'enregistrements trouvés =' .$max_line.' limite =  '.$step.'</SPAN>';
   }
	echo '<form method="get" onsubmit="set_reconcile(this);return false">';
	echo HtmlInput::submit("upd_rec","Mettre à jour");
    echo $content;
	echo HtmlInput::submit("upd_rec","Mettre à jour");
	echo '</form>';
    if (! $inside )echo $bar;

	echo HtmlInput::get_to_hidden(array('ctlc','amount_id','ledger'));
	echo HtmlInput::get_to_hidden(array('l','date_start','date_end','desc','amount_min','amount_max','qcodesearch_op','accounting','unpaid','gDossier','ledger_type'));
    if (isset($_GET['r_jrn'])) {
      foreach ($_GET['r_jrn'] as $k=>$v)
		echo HtmlInput::hidden('r_jrn['.$k.']',$v);
    }
}
echo '</div>';
?>