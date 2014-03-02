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
 *
 *
 * \brief Misc Operation for analytic accountancy
 *
 */
if ( ! defined ('ALLOWED') ) die('Appel direct ne sont pas permis');
require_once("class_ihidden.php");
require_once("class_iselect.php");
require_once("class_anc_account.php");
require_once ("class_anc_operation.php");
require_once ("class_anc_plan.php");
require_once ("class_anc_group_operation.php");
global $g_user;

$str_dossier=Dossier::get();
$pa=new Anc_Plan($cn);
$m=$pa->get_list();
if ( ! $m )
{

    echo '<div style="float:left;width:60%;margin-left:20%"><h2 class="error">'._('Aucun plan analytique défini').'</h2></div>';
    exit();
}



//----------------------------------------------------------------------
// show the left menu
//----------------------------------------------------------------------
echo '
<div class="content" >
<div class="lmenu">
<table>
<tr>
<td  class="mtitle" >
<A class="mtitle" HREF="?ac='.$_REQUEST['ac'].'&new&'.$str_dossier.'"> '._('Nouveau').' </A>
</td>
<td  class="mtitle" >
<A class="mtitle" HREF="?ac='.$_REQUEST['ac'].'&see&'.$str_dossier.'">'._('Liste opérations').' </A
</td>
</tr>
</table>
</div>
</div>
';


//----------------------------------------------------------------------
// the pa_id is set
//
//----------------------------------------------------------------------
if ( isset($_GET['see']))
{

    // Show the list for the period
    // and exit
    //-----------------------------
    $a=new Anc_Operation($cn);

    echo '
    <div class="redcontent"  style="margin-left:12%">
    <form method= "get">
    ';

    echo dossier::hidden();
    $hid=new IHidden();

    $hid->name="ac";
    $hid->value=$_REQUEST['ac'];
    echo $hid->input();

    $hid->name="see";
    $hid->value="";
    echo $hid->input();

    $w=new ISelect();
    $w->name="p_periode";
// filter on the current year
    $filter_year=" where p_exercice='".$g_user->get_exercice()."'";

    $periode_start=$cn->make_array("select p_id,to_char(p_start,'DD-MM-YYYY') from parm_periode $filter_year order by  p_start,p_end",1);
    $g_user=new User($cn);
    $current=(isset($_GET['p_periode']))?$_GET['p_periode']:$g_user->get_periode();
    $w->value=$periode_start;
    $w->selected=$current;
    echo $w->input();
    echo 'P&eacute;riode  '.HtmlInput::submit('gl_submit','Valider').'</form>';

    echo '<div class="redcontent"  style="margin-left:12%">';
    echo $a->html_table($current);
    echo '</div>';
    exit();
}
if ( isset($_POST['save']))
{
    // record the operation and exit
    // and exit
    //-----------------------------
    echo '<div class="redcontent" style="margin-left:12%">'.
    _('Opération sauvée');
    $a=new Anc_Group_Operation($cn);

    $a->get_from_array($_POST);

    $a->save();
    echo $a->show();
    echo '</div>';
    exit();
}

if ( isset($_GET['new']))
{
    //show the form for entering a new Anc_Operation
    //------------------------------------------
    $a=new Anc_Group_Operation($cn);

    $wSubmit=new IHidden("p_action","ca_od");
    $wSubmit->table=0;
    echo '<div class="redcontent"  style="margin-left:12%">';
    echo '<form method="post">';
    echo dossier::hidden();
    echo $wSubmit->input();
    echo $a->form();
    echo HtmlInput::submit("save","Sauver");
    echo '</form>';
    echo '<div class="info">';
    echo _('Débit').' = <span id="totalDeb"></span>';
    echo _('Crédit').' = <span id="totalCred"></span>';
    echo _('Difference').' = <span id="totalDiff"></span>
    </div>
    ';

    echo '</div>';
    exit();
}

?>
<div class="redcontent">
