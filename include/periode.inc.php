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
 * \brief add, modify, close or delete a period
 */
if ( ! defined ('ALLOWED') ) die('Appel direct ne sont pas permis');
$gDossier=dossier::id();
require_once("class_iselect.php");
require_once ('class_periode.php');
echo '<div class="content">';
$cn=new Database($gDossier);
//-----------------------------------------------------
// Periode
//-----------------------------------------------------
$action="";
if ( isset($_REQUEST['action']))
    $action=$_REQUEST['action'];
$choose=(isset ($_GET['choose']))?$_GET['choose']:"no";

if ($choose=='Valider') $choose='yes';

if ( isset ($_POST["add_per"] ))
{
    extract($_POST);
    $obj=new Periode($cn);
    if ( $obj->insert($p_date_start,$p_date_end,$p_exercice) == 1 )
    {
        alert(_('Valeurs invalides'));
    }
    $choose="yes";

}
if (isset($_POST['add_exercice']))
  {
    $obj=new Periode($cn);
    $exercice=$cn->get_value('select max(p_exercice::float)+1 from parm_periode');
    if ( $obj->insert_exercice($exercice,$_POST['nb_exercice']) == 1 )
    {
        alert(_('Valeurs invalides'));
    }

    $choose="yes";
  }
if ( $action=="closed")
{
    $p_per=$_GET['p_per'];
    $per=new Periode($cn);
    $jrn_def_id=(isset($_GET['jrn_def_id']))?$_GET['jrn_def_id']:0;
    $per->set_jrn($jrn_def_id);
    $per->set_periode($p_per);
    $per->close();
    $choose="yes";
}

if ( $action== "delete_per" )
{
    $p_per=$_GET["p_per"];
// Check if the periode is not used
    if ( $cn->count_sql("select * from jrnx where j_tech_per=$p_per") != 0 )
    {
        alert(' Désolé mais cette période est utilisée');
    }
    else
    {
        $Res=$cn->exec_sql("delete from parm_periode where p_id=$p_per");
    }
    $choose="yes";
}
if ( $action == 'reopen')
  {
    $jrn_def_id=(isset($_GET['jrn_def_id']))?$_GET['jrn_def_id']:0;
    $per=new Periode($cn);
    $jrn_def_id=(isset($_GET['jrn_def_id']))?$_GET['jrn_def_id']:0;
    $per->set_jrn($jrn_def_id);
    $per->set_periode($_GET['p_per']);
    $per->reopen();

    $choose="yes";
  }
if ( $choose=="yes" )
{
    echo HtmlInput::button_anchor('Autre Journal ?','?choose=no&ac='.$_REQUEST['ac'].'&gDossier='.dossier::id());
    $per=new Periode($cn);
    $jrn=(isset($_GET['jrn_def_id']))?$_GET['jrn_def_id']:0;
    $per->set_jrn($jrn);

    $per->display_form_periode();
    $nb_exercice=new ISelect("nb_exercice");
    $nb_exercice->value=array(
			      array('value'=>12,'label'=>"12 périodes"),
			      array('value'=>13,'label'=>"13 périodes")
			      );

    require_once('template/periode_add_exercice.php');
}
else
{
    echo '<form method="GET" >';
    echo dossier::hidden();
    $sel_jrn=$cn->make_array("select jrn_def_id, jrn_def_name from ".
                             " jrn_def order by jrn_def_name");
    $sel_jrn[]=array('value'=>0,'label'=>'Global : periode pour tous les journaux');
    $wSel=new ISelect();
    $wSel->value=$sel_jrn;
    $wSel->name='jrn_def_id';
    echo "Choississez global ou uniquement le journal à fermer".$wSel->input();
    echo   HtmlInput::submit('choose','Valider');
    echo HtmlInput::hidden('ac',$_REQUEST['ac']);
    echo "</form>";
    echo '<p class="info"> Pour ajouter, effacer ou modifier une p&eacute;riode, il faut choisir global</p>';
}
echo '</div>';
?>
