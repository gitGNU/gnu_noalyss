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

include_once('postgres.php');
include_once('class_fiche.php');
include_once("class_widget.php");

$cn=DbConnect($_SESSION['g_dossier']);

//-----------------------------------------------------
if  ( isset ($_REQUEST['fd_id'])) {
  // if amount requested
  $with_amount= (isset($_REQUEST['with_amount']))?true:false;
  if ($with_amount) 
    include_once("class_poste.php");

  echo '<div class="u_content">';
  $submit=new widget();
  $hid=new widget("hidden");
  $fiche_id=new widget("hidden");
  $w=new widget("select");
  $fiche_def=new fiche_def($cn);

  echo '<form method="POST" ACTION="fiche_csv.php">'.
    $submit->Submit('bt_csv',"Export CSV").
    $hid->IOValue("type","fiche").
    $hid->IOValue("p_action","impress").
    $fiche_id->IOValue("fd_id",$_REQUEST['fd_id']);
  if ($with_amount) {
    echo $hid->IOValue("with_amount");
    echo $hid->IOValue("from_periode",$_REQUEST['from_periode']);
    echo $hid->IOValue("to_periode",$_REQUEST['to_periode']);
  }
  echo "</form>";
  echo '<form method="Post" action="?p_action=impress&type=fiche">'.$submit->Submit("bt_submit","Autres fiches")."</form>";
  
  $fiche_def->id=$_REQUEST['fd_id'];

  // Si les fiches ont un poste comptable
  // propose de calculer aussi le solde
  //--
  if ( $fiche_def->HasAttribute(ATTR_DEF_ACCOUNT) == true ) {
    echo '<form method="POST" ACTION="?p_action=impress&type=fiche">';
    // filter on the current year
    $filter_year=" where p_exercice='".$User->getExercice()."'";

    $periode_start=make_array($cn,"select p_id,to_char(p_start,'DD-MM-YYYY') from parm_periode $filter_year order by p_start,p_end");
    
    $w->selected=(isset($_POST['from_periode']))?$_POST['from_periode']:"";
    print "Depuis ".$w->IOValue('from_periode',$periode_start);
    $periode_end=make_array($cn,"select p_id,to_char(p_end,'DD-MM-YYYY') from parm_periode $filter_year order by p_start,p_end");
    $w->selected=(isset($_POST['to_periode']))?$_POST['to_periode']:"";
    print " Jusque ".$w->IOValue('to_periode',$periode_end);
    

    print $submit->Submit('bt_solde',"Avec solde").
    $hid->IOValue("type","fiche").
    $fiche_id->IOValue("fd_id",$_REQUEST['fd_id']).
      $hid->IOValue("with_amount");
  
  echo "</form>";
  }
  
  $fiche=new fiche($cn);

  $old=-1;
  echo "<TABLE class=\"result\">";
  echo "<TR>";
  $fiche_def->GetAttribut();
  foreach ($fiche_def->attribut as $attribut) {
    echo "<TH>".$attribut->ad_text."</TH>";
    // si solde demand� affiche la col
    //--
    if ($attribut->ad_id==ATTR_DEF_ACCOUNT 
	&& $with_amount==true) {
      echo "<TH  >D�bit</TH>";
      echo "<TH  >Cr�dit</TH>";
      echo "<TH  >Solde</TH>";
    }
  }

  echo "<TR></TR>";
  $e=$fiche_def->GetByType($fiche_def->id);
  $l=var_export($e,true);
  echo_debug('impress_fiche.php',__LINE__,$l);

  if ( count($e) != 0 ) {
    foreach ($e as $detail) {
      echo "<TR>";
      foreach ( $detail->attribut as $dattribut ) {
	echo "<TD>".$dattribut->av_text."</TD>";
	// if amount requested
	//---
	if ( $dattribut->ad_id == ATTR_DEF_ACCOUNT && 
	     $with_amount) {

	  $account=new poste ($cn,$dattribut->av_text);
	  $sql_periode=sql_filter_per($cn,$_REQUEST['from_periode'],$_REQUEST['to_periode'],'p_id','j_tech_per');
	  $solde=  $account->GetSoldeDetail($sql_periode);

	  printf ("<td align=\"right\">% 10.2f</td>",$solde['debit']);
	  printf ("<td align=\"right\">% 10.2f</td>",$solde['credit']);
	  printf ("<td align=\"right\">% 10.2f</td>",$solde['solde']);
			      
	}
      }
    }
    echo "</TR>";
  }
 
  echo "</TABLE>";
  echo "</div>";
 }
 else {
   // only the menu
   $fiche_def=new fiche_def($cn);


   $fiche_def->GetAll();

   $i=0;
   foreach ($fiche_def->all as $l_fiche) {
     $a[$i]=array("?p_action=impress&type=fiche&fd_id=".$l_fiche->id,$l_fiche->label);
     $i++;
   }
   echo ShowItem($a,'V');
 }

?>