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
 * \brief Misc Operation for analytic accountancy
 *
 */
require_once("class_ihidden.php");
require_once("class_iselect.php");
require_once("class_anc_account.php");
require_once ("class_anc_operation.php");
require_once ("class_anc_plan.php");
require_once ("class_anc_group_operation.php");


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
<div class="content" style="width:88%;margin-left:12%">
<div class="lmenu">
<table>
<tr>
<td  class="mtitle" >
<A class="mtitle" HREF="?p_action=ca_od&new&'.$str_dossier.'"> '._('Nouveau').' </A>
</td>
<td  class="mtitle" >
<A class="mtitle" HREF="?p_action=ca_od&see&'.$str_dossier.'">'._('Liste opérations').' </A
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
    <div class="u_redcontent"  style="margin-left:12%">
    <form method= "get">
    ';

    echo dossier::hidden();
    $hid=new IHidden();

    $hid->name="p_action";
    $hid->value="ca_od";
    echo $hid->input();

    $hid->name="see";
    $hid->value="";
    echo $hid->input();

    $w=new ISelect();
    $w->name="p_periode";
// filter on the current year
    $filter_year=" where p_exercice='".$User->get_exercice()."'";

    $periode_start=$cn->make_array("select p_id,to_char(p_start,'DD-MM-YYYY') from parm_periode $filter_year order by  p_start,p_end",1);
    $User=new User($cn);
    $current=(isset($_GET['p_periode']))?$_GET['p_periode']:$User->get_periode();
    $w->value=$periode_start;
    $w->selected=$current;
    echo $w->input();
    echo 'P&eacute;riode  '.HtmlInput::submit('gl_submit','Valider').'</form>';

    echo '<div class="u_redcontent"  style="margin-left:12%">';
    echo $a->html_table($current);
    echo '</div>';
    exit();
}
if ( isset($_POST['save']))
{
    // record the operation and exit
    // and exit
    //-----------------------------
    echo '<div class="u_redcontent" style="margin-left:12%">'.
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
    echo JS_CAOD_COMPUTE;
    $wSubmit=new IHidden("p_action","ca_od");
    $wSubmit->table=0;
    echo '<div class="u_redcontent"  style="margin-left:12%">';
    echo '<form method="post">';
    echo dossier::hidden();
    echo $wSubmit->input();
    echo $a->form();
    echo HtmlInput::submit("save","Sauver");
    echo '</form>';
    echo '<div class="info">
    D&eacute;bit = <span id="totalDeb"></span>
    Cr&eacute;dit = <span id="totalCred"></span>
    Difference = <span id="totalDiff"></span>
    </div>
    ';

    echo '</div>';
    exit();
}

?>
<div class="u_redcontent">
