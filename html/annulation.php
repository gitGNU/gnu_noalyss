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
 * \brief in a popup window manage the deletion of the operations
 */
include_once ("ac_common.php");
include_once ("poste.php");
include_once("preference.php");
include_once("central_inc.php");
include_once("user_common.php");
include_once("check_priv.php");
include_once ("postgres.php");
include_once("jrn.php");
require_once("class_widget.php");
/* Admin. Dossier */
include_once ("class_user.php");

if ( ! isset ( $_SESSION['g_dossier'] ) ) {
  echo "You must choose a Dossier ";
  exit -2;
}
$cn=DbConnect($_SESSION['g_dossier']);
$User=new cl_user($cn);
$User->Check();

html_page_start($User->theme,"onLoad='window.focus();'");

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



list ($l_array,$max_deb,$max_cred)=GetData($cn,$_GET['jrn_op']);
foreach ($l_array as $key=>$element) {
  ${"e_$key"}=$element;
  echo_debug('annulation.php',__LINE__,"e_$key =$element");
}

// cancel an operation
if ( isset ($_POST['annul']) ) {
	/* Confirm it first */
	if ( ! isset ( $_POST['confirm'])) {
?>
<p>
<h2 class="info">Confirmation</h2>
<br>
<p>
Voulez-vous vraiment annuler  cette information soit par une remise à z&eacute;ro des montants 
soit par son &eacute;criture inverse ?
</p>
<span>
<FORM METHOD="POST" ACTION="annulation.php?p_jrn=<?php echo $_GET['p_jrn'];?>&jrn_op=<?php echo $_GET['jrn_op'];?>">
<INPUT TYPE="HIDDEN" NAME="annul">
<INPUT TYPE="HIDDEN" NAME="p_id" value="<?php echo $_POST['p_id']; ?>">
<INPUT TYPE="HIDDEN" NAME="op_date" value="<?php echo $_POST['op_date']; ?>">
<INPUT TYPE="SUBMIT" NAME="confirm" value="Oui"> 
</FORM>

<FORM METHOD="GET" ACTION="annulation.php">
<INPUT TYPE="HIDDEN" NAME="p_jrn" value="<?php echo $_REQUEST['p_jrn']; ?>">
<INPUT TYPE="HIDDEN" NAME="p_id" value="<?php echo $_REQUEST['p_id']; ?>">
<INPUT TYPE="HIDDEN" NAME="jrn_op" value="<?php echo $_REQUEST['jrn_op']; ?>">
<INPUT TYPE="SUBMIT" NAME="not_confirm" value="non">
</form>
</span>
<?php
return;
} // end confirm


// Remove is confirmed
  if ( isset ($_POST['p_id'])) {
    $p_id=$_POST['p_id'];
   // Get the date
   $e_op_date=$_POST['op_date'];


   // Test if date is valid
   if ( isDate ($e_op_date) == null ) {
     $msg=' Date non valide';
     echo "<script> alert('$msg');</script>";
     // set an incorrect pid to get out from here
     $p_id=-1;
   }
if  ($p_id != -1 ) { // A
   // userPref contient la periode par default
   $userPref=$User->GetPeriode($cn);
    list ($l_date_start,$l_date_end)=GetPeriode($cn,$userPref);

    // Periode fermée 
    if ( PeriodeClosed ($cn,$userPref)=='t' )
      {
	$msg="Votre periode par defaut est fermee, changez vos préférences";
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
    if ( PeriodeClosed($cn,$period_id) == 't' )
	  {
		try 
		  {

			// if the operation is in a closed or centralized period
			// the operation is voided thanks the opposite operation
			StartSql($cn);
			$grp_new=NextSequence($cn,'s_grpt');
			$seq=NextSequence($cn,"s_jrn");
			$p_internal=SetInternalCode($cn,$seq,$l_array['jr_def_id']);

			$sql= "insert into jrn (
  		jr_id,jr_def_id,jr_montant,jr_comment,               
		jr_date,jr_grpt_id,jr_internal                 
		,jr_tech_per, jr_valid
  		) select $seq,jr_def_id,jr_montant,'Annulation '||jr_comment,
		now(),$grp_new,'$p_internal',
		$userPref, true 
          from
	  jrn
	  where   jr_grpt_id=".$_POST['p_id'];
			$Res=ExecSql($cn,$sql,false);
			// Check return code
			if ( $Res == false) 
			  throw (new Exception(__FILE__.__LINE__."sql a echoue [ $sql ]"));
			

 
			// Make also the change into jrnx
			$sql= "insert into jrnx (
  	        j_date,j_montant,j_poste,j_grpt,               
                j_jrn_def,j_debit,j_text,j_internal,j_tech_user,j_tech_per,j_qcode
  		) select now(),j_montant,j_poste,$grp_new,
                  j_jrn_def,not (j_debit),j_text,'$p_internal','".$User->id."',
		  $userPref, j_qcode
	  from
	  jrnx
	  where   j_grpt=".$_POST['p_id'];
			$Res=ExecSql($cn,$sql,false);
			// Check return code
			if ( $Res == false) 
			  throw (new Exception(__FILE__.__LINE__."sql a echoue [ $sql ]"));
			
   
			// Mark the operation invalid into the ledger
			// to avoid to nullify twice the same op.
			$sql="update jrn set jr_comment='Annule : '||jr_comment where jr_grpt_id=".$_POST['p_id'];
			$Res=ExecSql($cn,$sql,false);
			// Check return code
			if ( $Res == false) 
			  throw (new Exception(__FILE__.__LINE__."sql a echoue [ $sql ]"));


			
			// Add a "concerned operation to bound these op.together
			//
			$Res=InsertRapt($cn,$seq,$l_array['jr_id']);
			// Check return code
			if ( $Res == false) 
			  throw (new Exception(__FILE__.__LINE__."sql a echoue [ $sql ]"));

			

			// the table stock must updated
			// also in the stock table
			$sql="delete from stock_goods where sg_id = any ( select sg_id
  from stock_goods natural join jrnx  where j_grpt=".$_POST['p_id'].")";
			$Res=ExecSql($cn,$sql);
			// Check return code
			if ( $Res == false) 
			  throw (new Exception(__FILE__.__LINE__."sql a echoue [ $sql ]"));
		  } 
		catch (Exception $e) 
		  {
			Rollback($cn);
			$msg="Désolé mais il n a pas été possible d'annuler ".
			  "cette opération";
			
			echo "<SCRIPT>alert('$msg');</SCRIPT>";

			echo '<span class="error">'.
			  'Erreur : '.
			  __FILE__.':'.__LINE__.' '.
			  $e->getMessage();
			echo '<p>';
			echo 'Postez ce message sur '.
			  '<A HREF="http://www.phpcompta.org/pmwiki.php/Forum/Forum">'.
			  'le forum de www.phpcompta.org</A> '.
			  '</p>';
			$p_id=-1;
			exit(-1);
		  }
   Commit($cn);
   // close the window
   echo '<h2 class="info"> Opération Annulée</h2>';
    ?>
 <script>
    window.close();
self.opener.RefreshMe();
</script>
    <?php
	    
    } else {
	// operation is not in a closed period
      // Check only if a line is valid or not
      if ( isValid($cn,$p_id) ==  1 ) {
		try 
		  {
			// Start Sql
			StartSql($cn);
			// delete from the rapt table
			$sql="delete from jrn_rapt where jr_id = any (select jr_id from jrn ".
			  " where jr_grpt_id = ".$_POST['p_id'].")";
			$Res=ExecSql($cn,$sql,false);

			if ( $Res == false) 
			  throw (new Exception(__FILE__.__LINE__."sql a echoue [ $sql ]"));

			// delete from the quant_sold
			$sql="delete from quant_sold where qs_internal = any (select jr_internal from jrn ".
			  " where jr_grpt_id = ".$_POST['p_id'].")";
			$Res=ExecSql($cn,$sql,false);

			if ( $Res == false) 
			  throw (new Exception(__FILE__.__LINE__."sql a echoue [ $sql ]"));



			// delete from the stock table
			$sql="delete from stock_goods where sg_id = any ( select sg_id".
			  " from stock_goods natural join jrnx  where j_grpt=".$_POST['p_id'].")";
			$Res=ExecSql($cn,$sql,false);

			if ( $Res == false) 
			  throw (new Exception(__FILE__.__LINE__." sql a echoue [ $sql ]"));

			// delete from jrnx & jrn
			$sql="delete from  jrnx  where j_grpt=".$_POST['p_id'];
			$Res=ExecSql($cn,$sql,false);

			if ( $Res == false) 
			  throw (new Exception(__FILE__.__LINE__." sql a echoue [ $sql ]"));
			
			// build the sql stmt for jrn
			$sql= "delete from jrn  where   jr_grpt_id=".$_POST['p_id'];
			$Res=ExecSql($cn,$sql,false);
			if ( $Res == false) 
			  throw (new Exception(__FILE__.__LINE__." sql a echoue [ $sql ]"));

		  } 
		catch (Exception $e) 
		  {
			Rollback($cn);
			$msg="Désolé mais il n a pas été possible d'annuler ".
			  "cette opération";
			
			echo "<SCRIPT>alert('$msg');</SCRIPT>";

			echo '<span class="error">'.
			  'Erreur : '.
			  __FILE__.':'.__LINE__.' '.
			  $e->getMessage();
			echo '<p>';
			echo 'Postez ce message sur '.
			  '<A HREF="http://www.phpcompta.org/pmwiki.php/Forum/Forum">'.
			  'le forum de www.phpcompta.org</A> '.
			  '</p>';
			$p_id=-1;
			exit(-1);
		  }
		Commit($cn);
		echo '<h2 class="info"> Opération Annulée</h2>';
		?>
		  <script>
			 window.close();
		self.opener.RefreshMe();
	  </script>
	    <?php
	      
	    }// if isValid
    } // else if period is closed
    }//B p_id == -1
  }//A p_id == -1
  } // if Post['p_id']
}// if annul
echo '<div align="center"> Opération '.$l_array['jr_internal'].'</div> 
<div>
<form action="'.$_SERVER['REQUEST_URI'].'" method="post" >';

$a=new widget("text");
// $a=InputType("Date","text", "op_date",$e_op_date,false);
//echo 'Date : '.$e_op_date;
$a->SetReadOnly(false);
echo $a->IOValue("op_date",$e_op_date,"Date");

echo '<div style="border-style:solid;border-width:1pt;">';
//$a=InputType("Description:","text_big","comment",$e_comment,false);
$a->size=80;
echo $a->IOValue("comment",$e_comment,"Description");
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
