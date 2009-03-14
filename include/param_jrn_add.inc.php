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
/* $Revision$ */
/*! \file
 * \brief To add a ledger
 */

include_once ("ac_common.php");
include_once("jrn.php");
require_once("class_iposte.php");
require_once("class_itext.php");
require_once("class_iselect.php");
require_once ('class_acc_ledger.php');

html_page_start($_SESSION['g_theme']);
require_once('class_dossier.php');
$gDossier=dossier::id();

include_once ("postgres.php");
/* Admin. Dossier */

include_once ("class_user.php");
$cn=DbConnect($gDossier);
$User=new User($cn);
$User->Check();
$User->check_dossier($gDossier);
include_once ("user_menu.php");




$User->can_request(PARJRN);


echo JS_SEARCH_POSTE;

if ( isset ($_POST["add"]) ) {
  if (  !isset($_POST["p_jrn_name"]) || ! isset($_POST["p_jrn_type"] )) {
    echo '<H2 CLASS="error"> Un paramètre manque</H2>';
  }
  else {
    /*  if name already use we stop */
    if ( CountSql($cn,"select * from jrn_def where jrn_def_name=$1",array($_POST['p_jrn_name'])) >0 ) {
      
      alert ('Un journal de ce nom existe déjà');
    } else {
      
      if (strlen(trim($_POST['p_jrn_deb_max_line'])) == 0 || 
	  (string) (int)$_POST['p_jrn_deb_max_line'] != (string)$_POST['p_jrn_deb_max_line'] )
	$l_deb_max_line=1;
      else
	$l_deb_max_line=$_POST['p_jrn_deb_max_line'];


      $p_jrn_name=$_POST["p_jrn_name"];
      echo_debug('jrn_add.php',__LINE__,"nom journal $p_jrn_name");
      $p_jrn_class_deb=FormatString($_POST["p_jrn_class_deb"]);
      if (strlen(trim($p_jrn_name))==0) return;
      // compute the jrn_def.jrn_def_code
      $p_code=sprintf("%s-%02d",trim($_POST['p_jrn_type']),Acc_Ledger::next_number($cn,$_POST['p_jrn_type']));
      $p_jrn_fiche_deb="";
      $p_jrn_fiche_cred="";

      if ( isset    ($_POST["FICHEDEB"])) {
	$p_jrn_fiche_deb=join(",",$_POST["FICHEDEB"]);
      }
      if ( isset    ($_POST["FICHEDEB"])) {
	$p_jrn_fiche_cred=join(",",$_POST["FICHEDEB"]);
      }
      $l_cred_max_line=$l_deb_max_line;
      StartSql($cn);
	$Sql="insert into jrn_def(jrn_def_name,jrn_def_class_deb,jrn_def_class_cred,jrn_deb_max_line,jrn_cred_max_line,
                  jrn_def_type,jrn_def_fiche_deb,jrn_def_fiche_cred,jrn_def_code,jrn_def_pj_pref) 
                  values ($1,$2,$3,$4,$5,$6,$7,$8,$9,$10)";
	$sql_array=array(   
			 $p_jrn_name,$p_jrn_class_deb,$p_jrn_class_deb,
			 $l_deb_max_line,$l_cred_max_line,$_POST['p_jrn_type'],
			 $p_jrn_fiche_deb,$p_jrn_fiche_cred,
			 $p_code,$_POST['jrn_def_pj_pref']);
	$Res=ExecSqlParam($cn,$Sql,$sql_array);
	$ledger_id=GetSequence($cn,'s_jrn_def');
	$Res=create_sequence($cn,'s_jrn_pj'.$ledger_id);
	AlterSequence($cn,'s_jrn_pj'.$ledger_id,$_POST['jrn_def_pj_seq']);
	Commit($cn);
    }
  }
  echo '<div class="lmenu">';
  MenuJrn();
  echo '</div>';
  exit();
}

echo '<div class="lmenu">';
MenuJrn();
echo '</div>';

$sessid=$_REQUEST['PHPSESSID'];


echo '<DIV CLASS="u_redcontent">';
/* widget for searching an account */
$wSearch=new IPoste();
$wSearch->extra="p_jrn_class_deb";
$wSearch->extra2='jrn';
$wJrn=new IText();
$wJrn->name='p_jrn_class_deb';
$wJrn->size=40;
$wJrn->value='';
$search=$wJrn->input().$wSearch->input();

/* construct all the hidden */
$hidden= HtmlInput::hidden('p_jrn',0);
$hidden.= HtmlInput::hidden('p_action','jrn');
$hidden.= HtmlInput::hidden('sa','add');
$hidden.= dossier::hidden();
$hidden.=HtmlInput::hidden('p_jrn_deb_max_line',10);
$hidden.=HtmlInput::hidden('p_ech_lib','echeance');

/* properties of the ledger */
$name="";
$code="";
$wType=new ISelect();
$wType->value=make_array($cn,'select jrn_type_id,jrn_desc from jrn_type');
$wType->name="p_jrn_type";
$type=$wType->input();
$rcred=$rdeb=array();
$wPjPref=new IText();
$wPjPref->name='jrn_def_pj_pref';
$pj_pref=$wPjPref->input();
$wPjSeq=new IText();
$wPjSeq->value=0;
$wPjSeq->name='jrn_def_pj_seq';
$pj_seq=$wPjSeq->input();

echo '<FORM METHOD="POST">';
echo dossier::hidden();
echo HtmlInput::hidden('p_action','jrn');
echo HtmlInput::hidden('sa','add');
require_once('template/param_jrn.php');

echo '<INPUT TYPE="SUBMIT" name="add" VALUE="Sauve"><INPUT TYPE="RESET" VALUE="Reset">';
echo '</FORM>';
echo "</DIV>";
html_page_stop();
?>
