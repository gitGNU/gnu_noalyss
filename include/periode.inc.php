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
$gDossier=dossier::id();
require_once("class_iselect.php");
require_once("preference.php");
require_once ('class_periode.php');
echo '<div class="content">';
$cn=DbConnect($gDossier);
//-----------------------------------------------------
// Periode
//-----------------------------------------------------
$action="";
if ( isset($_REQUEST['action'])) 
     $action=$_REQUEST['action'];
$choose=(isset ($_GET['choose']))?$_GET['choose']:"no";

if ($choose=='Valider') $choose='yes';
if ( $action=="change_per") {
  $User->can_request(PARPER);
  foreach($_GET as $key=>$element) 
    ${"$key"}=$element;
  echo "<TABLE>";
  echo '<TR> <FORM ACTION="?p_action=periode" METHOD="POST">';
  echo dossier::hidden();
  echo ' <INPUT TYPE="HIDDEN" NAME="p_per" VALUE="'.$p_per.'">';
  echo '<TD> <INPUT TYPE="text" NAME="p_date_start" VALUE="'.$p_date_start.'"></TD>';
  echo '<TD> <INPUT TYPE="text" NAME="p_date_end" VALUE="'.$p_date_end.'"></TD>';
  echo '<TD> <INPUT TYPE="text" NAME="p_exercice" VALUE="'.$p_exercice.'"></TD>';
  echo '<TD> <INPUT TYPE="SUBMIT" NAME="conf_chg_per" Value="Change"</TD>';
  echo '</FORM></TR>';
  echo "</TABLE>";
  //  $choose="yes";

}
if ( isset ($_POST["conf_chg_per"] ) ) {
  $User->can_request(PARPER);
  extract($_POST);

  if (isDate($p_date_start) == null ||
      isDate($p_date_end) == null ||
      strlen (trim($p_exercice)) == 0 ||
     (string) $p_exercice != (string)(int) $p_exercice)
    { 
      echo "<H2 class=\"error\"> Valeurs invalides</H2>";
      return;
    }
  $Res=ExecSql($cn," update parm_periode ".
	       "set p_start=to_date('". $p_date_start."','DD.MM.YYYY'),".
	       " p_end=to_date('". $p_date_end."','DD.MM.YYYY'),".
	       " p_exercice='".$p_exercice."'".
	       " where p_id=".$p_per);


  $choose="yes";

}
if ( isset ($_POST["add_per"] )) {
  $User->can_request(PARPER,1);
  extract($_POST);
  $obj=new Periode($cn);
  if ( $obj->insert($p_date_start,$p_date_end,$p_exercice) == 1 ){
      echo "<H2 class=\"error\"> Valeurs invalides</H2>";
      return;
  }
  $choose="yes";

}

echo_debug('periode.inc',__LINE__,"Action $p_action");
if ( $action=="closed") {
  $User->can_request(PARCLO);
  $p_per=$_GET['p_per'];
  $per=new Periode($cn);
  $jrn_def_id=(isset($_GET['jrn_def_id']))?$_GET['jrn_def_id']:0;
  $per->set_jrn($jrn_def_id);
  $per->set_periode($p_per);
  $per->close();
  $choose="yes";
}

if ( $action== "delete_per" ) {
  $User->can_request(PARPER);
  $p_per=$_GET["p_per"];
// Check if the periode is not used
  if ( CountSql($cn,"select * from jrnx where j_tech_per=$p_per") != 0 ) {
  echo '<h2 class="error"> Désolé mais cette période est utilisée</h2>';
  } else
  {
  $Res=ExecSql($cn,"delete from parm_periode where p_id=$p_per");
  }
  $choose="yes";
}
if ( $choose=="yes" ) {
  echo widget::button_href('Autre Journal ?','?choose=no&p_action=periode&gDossier='.dossier::id());
  $per=new Periode($cn);
  $jrn=(isset($_GET['jrn_def_id']))?$_GET['jrn_def_id']:0;
  $per->set_jrn($jrn);
  
  $per->display_form_periode();
 }  else {
  echo '<form method="GET" action="?">';
  echo dossier::hidden();
  $sel_jrn=make_array($cn,"select jrn_def_id, jrn_def_name from ".
		      " jrn_def order by jrn_def_name");
  $sel_jrn[]=array('value'=>0,'label'=>'Global : periode pour tous les journaux');
  $wSel=new ISelect();
  $wSel->value=$sel_jrn;
  $wSel->name='jrn_def_id';
  echo "Choississez global ou uniquement le journal à fermer".$wSel->IOValue();
  echo   widget::submit('choose','Valider');
  echo widget::hidden('p_action','periode');
  echo "</form>";
  echo '<p class="info"> Pour ajouter, effacer ou modifier une p&eacute;riode, il faut choisir global</p>';
 }
echo '</div>';
?>
