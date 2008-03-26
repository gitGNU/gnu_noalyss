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
 * \brief concerns the management of the "Plan Comptable"
 */
require_once ('class_acc_account.php');
include_once ("ac_common.php");
require_once("constant.php");
require_once('class_dossier.php');
$gDossier=dossier::id();

include_once ("postgres.php");
/* Admin. Dossier */
$rep=DbConnect();
include_once ("class_user.php");
$User=new User($rep);
$User->Check();


include_once ("user_menu.php");
include_once ("check_priv.php");


$cn=DbConnect($gDossier);


echo JS_UPDATE_PCMN;
/* Store the p_start parameter */
if ( ! isset ( $_SESSION['g_start']) ) {
  $_SESSION['g_start']="";

}
if ( isset ($_GET['p_start'])) { 
  $g_start=$_GET['p_start'];
  $_SESSION["g_start"]=$g_start; 

}

echo '<div class="u_subtmenu">';

echo '</div>';
$User->can_request($cn,MPCMN);


echo '<div class="lmenu">';
ShowMenuPcmn($_SESSION['g_start']);
echo '</div>';
echo '<DIV CLASS="u_redcontent">';
/* Analyse ce qui est demandé */
/* Effacement d'une ligne */
if (isset ($_GET['action'])) {
//-----------------------------------------------------
// Action == remove a line
  if ( $_GET['action']=="del" ) {
    if ( isset ($_GET['l']) ) {
      /* Ligne a enfant*/
      $R=ExecSqlParam($cn,"select pcm_val from tmp_pcmn where pcm_val_parent=$1",array($_GET['l']));
      if ( pg_NumRows($R) != 0 ) {
	echo "<SCRIPT> alert(\"Ne peut pas effacer le poste: d'autres postes en dépendent\");</SCRIPT>";
      } else {
	/* Vérifier que le poste n'est pas utilisé qq part dans les journaux */
	$Res=ExecSqlParam($cn,"select * from jrnx where j_poste=$1",array($_GET['l']));
	if ( pg_NumRows($Res) != 0 ) {
	  echo "<SCRIPT> alert(\"Ne peut pas effacer le poste: il est utilisé dans les journaux\");</SCRIPT>";
	}
	else {
	  $Del=ExecSql($cn,"delete from tmp_pcmn where pcm_val=".$_GET['l']);
	} // if pg_NumRows
      } // if pg_NumRows
    } // isset ($l)
  } //$action == del
} // isset action
//-----------------------------------------------------
/* Ajout d'une ligne */
if ( isset ( $_POST["Ajout"] ) ) {
	extract ($_POST);
	$p_val=trim($p_val);
	$p_parent=trim($p_parent);

  if ( isset ( $p_val) && isset ( $p_lib ) && isNumber($p_val) && isNumber($p_parent) ) {
    $p_val=trim($p_val);
    $p_lib=FormatString(trim($p_lib));
    $p_parent=$_POST["p_parent"];
    if ( strlen ($p_val) != 0 && strlen ($p_lib) != 0 ) {
      if (strlen ($p_val) == 1 ) {
	$p_parent=0;
      } else {
	if ( strlen(trim($p_parent))==0 && 
	     (string) $p_parent != (string)(int) $p_parent) {
	  $p_parent=substr($p_val,0,strlen($p_val)-1);
	}
	echo_debug('pcmn_update.php',__LINE__,"Ajout valeur = $p_val parent = $p_parent");
      }
      /* Parent existe */
      $Ret=ExecSqlParam($cn,"select pcm_val from tmp_pcmn where pcm_val=$1",array($p_parent));
      if ( pg_NumRows($Ret) == 0 ) {
	echo '<SCRIPT> alert(" Ne peut pas modifier; aucune poste parent"); </SCRIPT>';
      } else {
	// Check if the account already exists

	$Count=CountSql($cn,"select * from tmp_pcmn where pcm_val='".$p_val."'");
	if ( $Count != 0 ) 
	  {
	    // Alert message account already exists
	    echo '<SCRIPT> alert(" Ce poste existe déjà "); </SCRIPT>';

	  } else 
	    {
	      $Ret=ExecSql($cn,"insert into tmp_pcmn (pcm_val,pcm_lib,pcm_val_parent,pcm_type) values ('$p_val','$p_lib',$p_parent,'$p_type'));
	    }
      }
    } else {
      echo '<H2 class="error"> Valeurs invalides </H3>';
    }
  }
}

$Ret=ExecSql($cn,"select pcm_val,pcm_lib,pcm_val_parent,pcm_type from tmp_pcmn where substr(pcm_val::text,1,1)='".$_SESSION['g_start']."' order by pcm_val::text");
$MaxRow=pg_NumRows($Ret);

?>

<FORM METHOD="POST">
<?php
echo widget::hidden('p_action','pcmn');
//echo widget::hidden('sa','detail');
echo dossier::hidden();
?>
<TABLE ALIGN="center" BORDER=0 CELLPADDING=0 CELLSPACING=0> 
<TR>
<TH> Classe </TH>
<TH> Libellé </TH>
<TH> Parent </TH>
<TH> Type </TH>
</TR>
<?php
  $line=new Acc_Account($cn);
  echo $line->form(false); 
?>
<TD>
<INPUT TYPE="SUBMIT" Value="Ajout" Name="Ajout">
</TD>
</TR>
<?php
  $str_dossier=dossier::get();
for ($i=0; $i <$MaxRow; $i++) {
  $A=pg_fetch_array($Ret,$i);

  if ( $i%2 == 0 ) {
    $td ='<TD class="odd">';
  } else {
    $td='<TD class="even">';
  }
  echo "<TR> ";
  echo "$td";
  echo $A['pcm_val'];
  echo '</td>';
  echo "$td";
  printf ("<A HREF=\"javascript:PcmnUpdate(%d,'%s','%s','%s','%s',%d)\">",
	  $A['pcm_val'],
	  FormatString($A['pcm_lib']),
	  $A['pcm_val_parent'],
	  $A['pcm_type'],
	  $_REQUEST['PHPSESSID'],
	  dossier::id());
  echo $A['pcm_lib'];

  echo $td;
  echo $A['pcm_val_parent'];
  echo '</TD>';
  echo "</td>$td";
  echo $A['pcm_type'];
  echo "</TD>";


  echo $td;
  printf ('<A href="?p_action=pcmn&l=%d&action=del&%s">Delete</A>',$A['pcm_val'],$str_dossier);
  echo "</TD>";

  
  echo "</TR>";
}
echo "</TABLE>";
echo "</FORM>";
echo "</DIV>";
html_page_stop();
?>
