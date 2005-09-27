<?
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
include_once ("ac_common.php");
include_once ("poste.php");
include_once("preference.php");
include_once("central_inc.php");
include_once("user_common.php");
include_once("form_input.php");
include_once("check_priv.php");
include_once ("postgres.php");
include_once("jrn.php");
/* Admin. Dossier */
$rep=DbConnect();
include_once ("class_user.php");
$User=new cl_user($rep);
$User->Check();

html_page_start($User->theme,"onLoad='window.focus();'");
if ( ! isset ( $_SESSION['g_dossier'] ) ) {
  echo "You must choose a Dossier ";
  exit -2;
}

if ( isset( $_GET['p_jrn'] )) {
  $p_jrn=$_GET['p_jrn'];
  }

 // Check privilege
 // CheckJrn verify that the user is not an admin
 // an admin has all right
  if ( CheckJrn($_SESSION['g_dossier'],$_SESSION['g_user'],$_GET['p_jrn']) != 2 )    {
       NoAccess();
       exit -1;
  }


$cn=DbConnect($_SESSION['g_dossier']);

list ($l_array,$max_deb,$max_cred)=GetData($cn,$_GET['jrn_op']);
foreach ($l_array as $key=>$element) {
  ${"e_$key"}=$element;
  echo_debug(__FILE__,__LINE__,"e_$key =$element");
}

// cancel an operation
if ( isset ($_POST['annul']) ) {
  if ( isset ($_POST['p_id'])) {
    // Get the current periode
    $period=GetUserPeriode($cn,$User->id);
    $p_id=$_POST['p_id'];
   // Get the date
   $e_op_date=$_POST['op_date'];


   // Test if date is valid
   if ( isDate ($e_op_date) == null ) {
     $msg='Invalid Date';
     echo "<script> alert('$msg');</script>";
     // set an incorrect pid to get out from here
     $p_id=-1;
   }
if  ($p_id != -1 ) { // A
   // userPref contient la periode par default
    $userPref=GetUserPeriode($cn,$User->id);
    list ($l_date_start,$l_date_end)=GetPeriode($cn,$userPref);

    // Periode fermée 
    if ( PeriodeClosed ($cn,$userPref)=='t' )
      {
		$msg="This periode is closed please change your preference";
		echo_error($msg); 
		echo "<SCRIPT>alert('$msg');</SCRIPT>";
		// set an incorrect pid to get out from here
		$p_id=-1;
      }
 if ( $p_id != -1 ) { //B
    // Test whether date of the operation is in a closed periode
    // get the period_id
    $period_id=getPeriodeFromDate($cn,$e_op_date);
      // Check the period_id
    if ( PeriodeClosed($cn,$period_id) == 't' ){
      // if the operation is in a closed or centralized period
      // the operation is voided thanks the opposite operation
   StartSql($cn);
   $p_internal=SetInternalCode($cn,$p_id,$l_array['jr_id']);
   $grp_new=NextSequence($cn,'s_grpt');
   $sql= "insert into jrn (
  		jr_def_id,jr_montant,jr_comment,               
		jr_date,jr_grpt_id,jr_internal                 
		,jr_tech_per, jr_valid
  		) select jr_def_id,jr_montant,'Annulation '||jr_comment,
		now(),$grp_new,'$p_internal',
		$period, true 
          from
	  jrn
	  where   jr_grpt_id=".$_POST['p_id'];
   $Res=ExecSql($cn,$sql);
   // Check return code
   if ( $Res == false ) { Rollback($cn);exit(-1);}
   // Mark the operation invalid into the ledger
   // to avoid to nullify twice the same op.
   $sql="update jrn set jr_valid=false where jr_grpt_id=".$_POST['p_id'];
   $Res=ExecSql($cn,$sql);
   // Check return code
   if ( $Res == false ) { Rollback($cn);exit(-1);}
 
  // Make also the change into jrnx
   $sql= "insert into jrnx (
  	        j_date,j_montant,j_poste,j_grpt,               
                j_jrn_def,j_debit,j_text,j_internal,j_tech_user
  		) select now(),j_montant,j_poste,$grp_new,
                  j_jrn_def,j_debit,j_text,'$p_internal','".$User->id."'
	  from
	  jrnx
	  where   j_grpt=".$_POST['p_id'];
   $Res=ExecSql($cn,$sql);
   // Check return code
   if ( $Res == false ) { Rollback($cn);exit(-1);}

   // the table stock must updated
   // also in the stock table
   $sql="delete from stock_goods where sg_id = any ( select sg_id
  from stock_goods natural join jrnx  where j_grpt=".$_POST['p_id'].")";
   $Res=ExecSql($cn,$sql);
   // Check return code
   if ( $Res == false ) { Rollback($cn);exit(-1);}

   Commit($cn);
   // close the window
   echo '<h2 class="info"> Opération Annulée</h2>';
    ?>
 <script>
    window.close();
self.opener.RefreshMe();
</script>
    <?
	    
    } else {
	// operation is not in a closed period
      // Check only if a line is valid or not
      if ( isValid($cn,$p_id) ==  1 ) {
	// Start Sql
	StartSql($cn);

	// delete from the stock table
	$sql="delete from stock_goods where sg_id = any ( select sg_id
 from stock_goods natural join jrnx  where j_grpt=".$_POST['p_id'].")";
	$Res=ExecSql($cn,$sql);
	
   if ( $Res == false ) { Rollback($cn);exit(-1);}
	// delete from jrnx & jrn
	$sql="update jrnx set j_montant = 0 where j_grpt=".$_POST['p_id'];
	
	$Res=ExecSql($cn,$sql);
	
   if ( $Res == false ) { Rollback($cn);exit(-1);}
	
	// build the sql stmt for jrn
	$sql= "update  jrn  set jr_montant=0,jr_valid='f',jr_comment='Erreur:'||jr_comment  where   jr_grpt_id=".$_POST['p_id'];
	$Res=ExecSql($cn,$sql);
	
   if ( $Res == false ) { Rollback($cn);exit(-1);}
	Commit($cn);
	echo '<h2 class="info"> Opération Annulée</h2>';
	  ?>
	  <script>
	     window.close();
	self.opener.RefreshMe();
	</script>
	    <?
	    
	    }// if isValid
    } // else if period is closed
    }//B p_id == -1
  }//A p_id == -1
  } // if Post['p_id']
}// if annul
echo '<div align="center"> Opération '.$l_array['jr_internal'].'</div> 
<div>
<form action="'.$_SERVER['REQUEST_URI'].'" method="post" >';

$a=InputType("Date","text", "op_date",$e_op_date,false);
//echo 'Date : '.$e_op_date;
echo $a;
echo '<div style="border-style:solid;border-width:1pt;">';
$a=InputType("Description:","text_big","comment",$e_comment,false);
echo $a;
echo '</DIV>';

if ( isset ($e_ech) ) {
  echo "<DIV> Echeance $e_ech </DIV>";
}
for ( $i = 0; $i < $max_deb;$i++) {
  $lib=GetPosteLibelle($_SESSION['g_dossier'],${"e_class_deb$i"}); 
  echo '<div style="background-color:#BFC2D5;">';
  echo ${"e_class_deb$i"}." $lib    "."<B>".${"e_mont_deb$i"}."</B>";
  echo "</div>";
}
for ( $i = 0; $i < $max_cred;$i++) {
  $lib=GetPosteLibelle($_SESSION['g_dossier'],${"e_class_cred$i"});
  echo '<div style="background-color:#E8F4FF;">';
  echo ${"e_class_cred$i"}."  $lib   "."<B>".${"e_mont_cred$i"}."</B>";
  echo '</div>';
}
//echo "operation concernée $e_rapt<br><br>
//";
$a=GetConcerned($cn,$e_jr_id);

if ( $a != null ) {
  foreach ($a as $key => $element) {
    echo "operation concernée <br>";

    echo "<A HREF=\"jrn_op_detail.php?jrn_op=".GetGrpt($cn,$element)."\"> ".GetInternal($cn,$element)."</A><br>";
  }//for
}// if ( $a != null ) {

echo '

<input type="hidden" name="p_id" value="'.$_GET['jrn_op'].'">
<input type="submit" name="annul"  value="Mise à zéro">
<input type="button" name="cancel" value="Retour" onClick="window.close();">
</form>';

html_page_stop();
?>
