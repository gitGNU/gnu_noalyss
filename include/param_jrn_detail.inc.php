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
 * \brief Show and let modify ledger parameter
 */
require_once("class_itext.php");
require_once('class_dossier.php');
require_once('class_acc_ledger.php');
/* javascript for the search poste */
require_once('class_ipopup.php');
echo js_include('accounting_item.js');
echo js_include('prototype.js');
echo js_include('scriptaculous.js');
echo js_include('effects.js');
echo js_include('controls.js');
echo js_include('dragdrop.js');

/* ipopup for search poste */
echo IPoste::ipopup('ipop_account');

$gDossier=dossier::id();

include_once ("ac_common.php");
html_page_start($_SESSION['g_theme']);
require_once('class_database.php');
/* Admin. Dossier */
$cn=new Database($gDossier);
include_once ("class_user.php");
$User=new User($cn);
$User->Check();
$User->check_dossier($gDossier);


$User->can_request(PARJRN);

if ( isset( $_REQUEST['p_jrn'] )) {
  $p_jrn=$_REQUEST['p_jrn'];
 } else {
  echo '<h2 class="error">'._('Journal inexistant').'</h2>';
  exit();
 }
//--------------------------------------------------
// remove ledger
//--------------------------------------------------
if ( isset($_POST["efface"])) {
	if ( $cn->count_sql("select * from jrn where jr_def_id=".$_POST['p_jrn']." limit 3") == 0 )
	  {
	    $cn->exec_sql("delete from jrn_def where jrn_def_id=$1",array($_POST['p_jrn']));
	  } else { 
	  alert(_("Impossible d\'effacer ce journal.\n Il est utilisé\n"));
	}
  }


//--------------------------------------------------
// Update ledger
If ( isset ($_POST["update"] )) {
  if (  !isset($_POST["p_jrn_name"])  ) {
    echo '<H2 CLASS="error">'._('Un paramètre manque').'</H2>';
  }
  else {
    if ( isset ($_POST['p_ech']) && $_POST['p_ech'] == 'no' ) {
      $p_ech='false';
      $p_ech_lib='null';
    } else {
      $p_ech='true';
      $p_ech_lib="'".$_POST['p_ech_lib']."'";
    }
   
    if ( strlen(trim($_POST['p_jrn_deb_max_line'])) == 0 || 
	(string) (int)$_POST['p_jrn_deb_max_line'] != (string)$_POST['p_jrn_deb_max_line'] ||
	 $_POST['p_jrn_deb_max_line'] <= 0
	 )
      $l_deb_max_line=1;
    else
      $l_deb_max_line=$_POST['p_jrn_deb_max_line'];
    
    $l_cred_max_line=$l_deb_max_line;

    $p_jrn_name=$_POST['p_jrn_name'];
     if (strlen(trim($p_jrn_name))==0) return;
     $p_jrn_name=FormatString($p_jrn_name);
       $p_jrn_fiche_deb="";
       $p_jrn_fiche_cred="";

     if ( isset    ($_POST["FICHEDEB"])) {
       $p_jrn_fiche_deb=join(",",$_POST["FICHEDEB"]);
     }
      if ( isset    ($_POST["FICHECRED"])) {
       $p_jrn_fiche_cred=join(",",$_POST["FICHECRED"]);
      }
    $Sql="update jrn_def set jrn_def_name=$1,jrn_def_class_deb=$2,jrn_def_class_cred=$3,
                 jrn_deb_max_line=$4,jrn_cred_max_line=$5,jrn_def_ech=$6,jrn_def_ech_lib=$7,jrn_def_fiche_deb=$8,
                  jrn_def_fiche_cred=$9, jrn_def_pj_pref=upper($10)
                 where jrn_def_id=$11";
      $sql_array=array(
		       $p_jrn_name,$_POST['p_jrn_class_deb'],$_POST['p_jrn_class_deb'],
		       $l_deb_max_line,$l_cred_max_line,
		       $p_ech,$p_ech_lib,
		       $p_jrn_fiche_deb,$p_jrn_fiche_cred,
		       $_POST['jrn_def_pj_pref'],
		       $_GET['p_jrn']
		       );
    echo_debug($Sql);
    $Res=$cn->exec_sql($Sql,$sql_array);
    if ( isNumber($_POST['jrn_def_pj_seq']) == 1 && $_POST['jrn_def_pj_seq']!=0)
      $Res=$cn->alter_seq("s_jrn_pj".$_GET['p_jrn'],$_POST['jrn_def_pj_seq']);
  }
}
echo '<div class="lmenu">';
MenuJrn();
echo '</div>';
?>
<script language="javascript">
  function m_confirm() {
  return confirm ("Etes-vous sur de vouloir effacer ce journal ?");
}
</script>

<?php
$Res=$cn->exec_sql("select jrn_def_name,jrn_def_class_deb,jrn_def_class_cred,".
	     "jrn_deb_max_line,jrn_cred_max_line,jrn_def_code".
                 ",jrn_def_type,jrn_def_ech, jrn_def_ech_lib,jrn_def_fiche_deb,jrn_def_fiche_cred".
                 " from jrn_def where".
                 " jrn_def_id=".$_REQUEST['p_jrn']);
if ( Database::num_row($Res) == 0 ) exit();


$l_line=Database::fetch_array($Res,0);

/* Load all the properties of the ledger */
$Ledger=new Acc_Ledger($cn,$_REQUEST['p_jrn']);
$prop=$Ledger->get_propertie();
$type=$prop['jrn_def_type'];
$name=$prop['jrn_def_name'];
$code=$prop['jrn_def_code'];
/* widget for searching an account */
$wSearch=new IPoste();
$wSearch->set_attribute('ipopup','ipop_account');
$wSearch->set_attribute('account','p_jrn_class_deb');
$wSearch->set_attribute('no_overwrite','1');
$wSearch->set_attribute('noquery','1');
$wSearch->table=3;
$wSearch->name="p_jrn_class_deb";
$wSearch->size=20;
$wSearch->value=$prop['jrn_def_class_deb'];
$search=$wSearch->input();


$wPjPref=new IText();
$wPjPref->name='jrn_def_pj_pref';
$wPjPref->value=$prop['jrn_def_pj_pref'];
$pj_pref=$wPjPref->input();
$wPjSeq=new INum();
$wPjSeq->value=0;
$wPjSeq->name='jrn_def_pj_seq';
$pj_seq=$wPjSeq->input();
$last_seq=$Ledger->get_last_pj();
$name=$l_line['jrn_def_name'];

/* construct all the hidden */
$hidden= HtmlInput::hidden('p_jrn',$_GET['p_jrn']);
$hidden.= HtmlInput::hidden('p_action','jrn');
$hidden.= HtmlInput::hidden('sa','detail');
$hidden.= dossier::hidden();
$hidden.=HtmlInput::hidden('p_jrn_deb_max_line',10);
$hidden.=HtmlInput::hidden('p_ech_lib','echeance');

/* Load the card */
$card=$Ledger->get_fiche_def();
$rdeb=explode(',',$card['deb']);
$rcred=explode(',',$card['cred']);


echo '<div class="u_redcontent">';
echo '<form method="POST">';
echo $hidden;
require_once('template/param_jrn.php');
echo '<INPUT TYPE="SUBMIT" class="button" VALUE="Sauve" name="update">
<INPUT TYPE="RESET" class="button" VALUE="Reset">
<INPUT TYPE="submit" class="button"  name="efface" value="Efface" onClick="return m_confirm();">';

echo '</FORM>';
echo "</DIV>";
html_page_stop();
?>
