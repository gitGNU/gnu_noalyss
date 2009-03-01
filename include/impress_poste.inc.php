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
require_once("class_ispan.php");
require_once("class_iposte.php");
require_once("class_icard.php");
require_once("class_iselect.php");
require_once("class_icheckbox.php");
require_once('class_acc_operation.php');
/*! \file
 * \brief Print account (html or pdf)
 *        file included from user_impress
 *
 * some variable are already defined $cn, $User ...
 * 
 */
//-----------------------------------------------------
// Show the jrn and date
//-----------------------------------------------------
include_once("postgres.php");
//-----------------------------------------------------
// Form
//-----------------------------------------------------
echo '<div class="content">';
echo JS_SEARCH_POSTE;
echo JS_SEARCH_CARD;
echo JS_PROTOTYPE;
echo '<FORM action="?" METHOD="GET">';
echo widget::hidden('p_action','impress');
echo widget::hidden('type','poste');
echo dossier::hidden();
echo '<TABLE><TR>';
$span=new ISpan();

$w=new IPoste();
$w->table=1;
$w->value=(isset($_REQUEST['poste_id']))?$_REQUEST['poste_id']:"";
$w->label="Choississez le poste";
print $w->IOValue("poste_id");
echo $span->IOValue('poste_id_label');
$w_poste=new ICard();
$w_poste->table=1;
$w_poste->label="Ou Choississez la fiche";
$w_poste->extra='all';
$w_poste->value=(isset($_REQUEST['f_id']))?$_REQUEST['f_id']:"";
print $w_poste->IOValue("f_id");
echo $span->IOValue('f_id_label');
print '</TR>';
print '<TR>';
// filter on the current year
$select=new ISelect();
$select->table=1;
$filter_year=" where p_exercice='".$User->get_exercice()."'";
$periode_start=make_array($cn,"select p_id,to_char(p_start,'DD-MM-YYYY') from parm_periode $filter_year order by p_start,p_end");
$select->label="Depuis";
$select->selected=(isset($_REQUEST['from_periode']))?$_REQUEST['from_periode']:"";
print $select->IOValue('from_periode',$periode_start);
$select->label=" jusqu'à ";
$periode_end=make_array($cn,"select p_id,to_char(p_end,'DD-MM-YYYY') from parm_periode  $filter_year order by p_start,p_end");
$select->selected=(isset($_REQUEST['to_periode']))?$_REQUEST['to_periode']:"";
print $select->IOValue('to_periode',$periode_end);
print "</TR>";
print "<TR><TD>";
$all=new ICheckBox();
$all->label="Tous les postes qui en dépendent";
$all->disabled=false;
$all->selected=(isset($_REQUEST['poste_fille']))?true:false;
echo $all->IOValue("poste_fille");
echo '</TD></TR><TR><TD>';
$detail=new ICheckBox();
$detail->label="D&eacute;tail des op&eacute;rations";
$detail->disabled=false;
$detail->selected=(isset($_REQUEST['oper_detail']))?true:false;
echo $detail->IOValue("oper_detail");
echo '</td></tr>';
echo '</TABLE>';
print widget::submit('bt_html','Visualisation');

echo '</FORM>';
echo '<hr>';
echo '</div>';

//-----------------------------------------------------
// If print is asked
// First time in html
// after in pdf or cvs
//-----------------------------------------------------
if ( isset( $_REQUEST['bt_html'] ) ) {
  require_once("class_acc_account_ledger.php");
  $go=0;
// we ask a poste_id
  if ( isset($_GET['poste_id']) && strlen(trim($_GET['poste_id'])) != 0 && isNumber($_GET['poste_id']) )
    {
      if ( isset ($_GET['poste_fille']) )
      {
		$parent=$_GET['poste_id'];
		$a_poste=get_array($cn,"select pcm_val from tmp_pcmn where pcm_val::text like '$parent%' order by pcm_val::text");
	$go=3;
      } 
      // Check if the post is numeric and exists
      elseif (  CountSql($cn,'select * from tmp_pcmn where pcm_val='.FormatString($_GET['poste_id'])) != 0 )
	{
	  $Poste=new Acc_Account_Ledger($cn,$_GET['poste_id']);$go=1;
	}
    }
  if ( strlen(trim($_GET['f_id'])) != 0 )
    {
      require_once("class_fiche.php");
      // thanks the qcode we found the poste account
      $fiche=new fiche($cn);
      $qcode=$fiche->get_by_qcode($_GET['f_id']);
      $p=$fiche->strAttribut(ATTR_DEF_ACCOUNT);
      if ( $p != "- ERROR -") {
	$go=2;  
      }
   }
  
  // A account  is given
  if ( $go == 1) 
    {
      echo '<div class="content">';
      if ( ! isset($_REQUEST['oper_detail']) ) {
	Acc_Account_Ledger::HtmlTableHeader();
	$Poste->HtmlTable();
	echo Acc_Account_Ledger::HtmlTableHeader();
      } else {
	//----------------------------------------------------------------------
	// Detail 
	//----------------------------------------------------------------------
	Acc_Account_Ledger::HtmlTableHeader();
	$Poste->get_row( $_GET['from_periode'], $_GET['to_periode']);
	if ( empty($Poste->row)) exit();
	$Poste->load();
	echo '<table "width=70%">';
	echo '<tr><td  class="mtitle" colspan="5"><h2 class="info">'. $_GET['poste_id'].' '.h($Poste->label).'</h2></td></tr>';

	foreach ($Poste->row as $a) {
	  $detail=$a;
	  
	  echo '<tr><td class="mtitle" colspan="5">'.$detail['j_date'].' '.$detail['jr_internal'].h($detail['description']).'</td></tr>';

	  $op=new Acc_Operation($cn);
	  $op->jr_id=$a['jr_id'];
	  $op->poste=$_GET['poste_id'];
	  echo $op->display_jrnx_detail(1);
	}
	echo '</table>';
	echo Acc_Account_Ledger::HtmlTableHeader();
      }
      echo "</div>";
      exit;
   }
  
  // A QuickCode  is given
  if ( $go == 2) 
    {
      if ( ! isset($_REQUEST['oper_detail']) ) {
	echo '<div class="content">';
	$fiche->HtmlTableHeader();
	$fiche->HtmlTable();
	$fiche->HtmlTableHeader();
	echo "</div>";
      } else {
	// Detail //
	echo '<div class="content">';
	$fiche->HtmlTableHeader();
	$fiche->HtmlTableDetail();
	$fiche->HtmlTableHeader();

      }
      exit;
   }

  // All the children account
  if ( $go == 3 )
    {

      if ( sizeof($a_poste) == 0 ) 
	exit;
      echo '<div class="content">';


      if ( ! isset ($_REQUEST['oper_detail'])) {
	$Poste=new Acc_Account_Ledger($cn,$_GET['poste_id']);
	echo Acc_Account_Ledger::HtmlTableHeader();
		
	foreach ($a_poste as $poste_id ) 
	  {
	    $Poste=new Acc_Account_Ledger ($cn,$poste_id['pcm_val']);
	    $Poste->HtmlTable();
	  }
	echo Acc_Account_Ledger::HtmlTableHeader();
	echo "</div>";
      } else {
	//----------------------------------------------------------------------
	// Detail 
	//----------------------------------------------------------------------
	echo Acc_Account_Ledger::HtmlTableHeader();
	echo '<table "width=70%">';	    
	foreach ($a_poste as $poste_id ) 
	  {
	    $Poste=new Acc_Account_Ledger ($cn,$poste_id['pcm_val']);
	    $Poste->load();
	    $Poste->get_row( $_GET['from_periode'], $_GET['to_periode']);
	    if ( empty($Poste->row)) continue;
	    echo '<tr><td  class="mtitle"  colspan="5"><h2 class="info">'. $poste_id['pcm_val'].' '.h($Poste->label).'</h2></td></tr>';

	    $detail=$Poste->row[0];
	    

	    foreach ($Poste->row as $a) {
	      $detail=$a;
	      echo '<tr><td class="mtitle" colspan="5">'. $detail['j_date'].' '.$detail['jr_internal'].' '.hb($detail['description']).' '.hi($detail['jr_pj_number']).'</td></tr>';

	      $op=new Acc_Operation($cn);
	      $op->poste=$poste_id['pcm_val'];

	      $op->jr_id=$a['jr_id'];
	      echo $op->display_jrnx_detail(1);
	    }
	  }
	echo '</table>';
	echo Acc_Account_Ledger::HtmlTableHeader();
      }
      
      exit;
    }
} 
?>
