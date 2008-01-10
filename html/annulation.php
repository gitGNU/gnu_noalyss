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
require_once("class_acc_ledger.php");
require_once("class_acc_operation.php");
require_once ('class_periode.php');

/* Admin. Dossier */
include_once ("class_user.php");
require_once('class_dossier.php');
$gDossier=dossier::id();

$cn=DbConnect($gDossier);
$User=new cl_user($cn);
$User->Check();

html_page_start($User->theme,"onLoad='window.focus();'");
echo JS_VIEW_JRN_MODIFY;
if ( isset( $_REQUEST['p_jrn'] )) {
  $p_jrn=$_GET['p_jrn'];
  }

 // Check privilege
 // CheckJrn verify that the user is not an admin
 // an admin has all right
  if ( CheckJrn($gDossier,$_SESSION['g_user'],$_GET['p_jrn']) != 2 )    {
       NoAccess();
       exit -1;
  }



list ($l_array,$max_deb,$max_cred)=get_data($cn,$_GET['jrn_op']);
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
	    <p><span class="error">Attention: l'effacement d'une op&eacute;ration peut rompre la s&eacute;quence de la num&eacute;rotation des factures surtout celles g&eacute;n&eacute;r&eacute;es automatiquement, soyez tr&egrave;s prudent quand vous effacez. La pi&egrave;ce jointe sera elle aussi effac&eacute;e. Si vous utilisez la comptabilit&eacute; analytique les op&eacute;rations li&eacute;es seront effac&eacute;es.
</span>
</p> 

<p> Voulez-vous vraiment annuler  cette information soit par une remise &agrave; z&eacute;ro des montants 
soit par son &eacute;criture inverse ?
</p>
<span>
<FORM METHOD="POST" ACTION="annulation.php?p_jrn=<?php echo $_GET['p_jrn'];?>&jrn_op=<?php echo $_GET['jrn_op'];?>">
					   <?php echo dossier::hidden(); ?>
<INPUT TYPE="HIDDEN" NAME="annul">
<INPUT TYPE="HIDDEN" NAME="p_id" value="<?php echo $_POST['p_id']; ?>">
<INPUT TYPE="HIDDEN" NAME="op_date" value="<?php echo $_POST['op_date']; ?>">
<INPUT TYPE="SUBMIT" NAME="confirm" value="Oui"> 
</FORM>

<FORM METHOD="GET" ACTION="annulation.php">
					   <?php echo dossier::hidden(); ?>
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
   $userPref=$User->get_periode($cn);
    list ($l_date_start,$l_date_end)=get_periode($cn,$userPref);
    $per=new Periode($cn);
    $per->set_jrn($p_jrn);
    $per->set_periode($userPref);
    
    // Periode ferme
    if ( $per->is_open()==0)
      {
	$msg="Votre periode par defaut est fermee, changez vos preferences";
	echo_error($msg); 
	echo "<SCRIPT>alert('$msg');</SCRIPT>";
	$p_id=-1;
      }
 
 if ( $p_id != -1 ) { //B
    // Test whether date of the operation is in a closed periode
    // get the period_id
    $period_id=getPeriodeFromDate($cn,$e_op_date);
    // thanks jrn_op  (jrn.jr_id) we find out the concerned ledger
    $sql="select jr_def_id from jrn where jr_id=".$_REQUEST['jrn_op'];
    $r=ExecSql($cn,$sql);
    $nJrn=pg_fetch_result($r,0,0);
    $per=new Periode($cn);
    $per->set_jrn(nJrn);
    $per->set_periode($period_id);

      // Check the period_id
    if ( $per->is_open() == 0 )
	  {
	    try 
	      {
		
		// if the operation is in a closed or centralized period
		// the operation is voided thanks the opposite operation
		StartSql($cn);
		$grp_new=NextSequence($cn,'s_grpt');
		$seq=NextSequence($cn,"s_jrn");
		$oJrn=new Acc_Ledger($cn,$l_array['jr_def_id']);
		$p_internal=$oJrn->compute_internal_code($seq);
		
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
		  $userPref,j_qcode
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
	

   // Set the record to A (annulate) in quant_sold and quand_purchase
   

   $Res=ExecSql($cn,"update quant_sold set ".
				" qs_valid='A' where qs_internal='".$l_array['jr_internal']."'",
				false);

   if ( $Res == false) 
	 throw (new Exception(__FILE__.__LINE__."sql a echoue [ $sql ]"));


   $Res=ExecSql($cn,"update quant_purchase set ".
				" qp_valid='A' where qp_internal='".$l_array['jr_internal']."'",
				false);
   if ( $Res == false) 
	 throw (new Exception(__FILE__.__LINE__."sql a echoue [ $sql ]"));






    // Add a "concerned operation to bound these op.together
    //
    $Res=InsertRapt($cn,$seq,$l_array['jr_id']);
   // Check return code
	if ( $Res == false ) { throw (new Exception(__FILE__.__LINE__."sql a echoue [ $sql ]"));}
    

   // the table stock must updated
   // also in the stock table
   $sql="delete from stock_goods where sg_id = any ( select sg_id
  from stock_goods natural join jrnx  where j_grpt=".$_POST['p_id'].")";
   $Res=ExecSql($cn,$sql,false);
   // Check return code
   if ( $Res == false) 
	 throw (new Exception(__FILE__.__LINE__."sql a echoue [ $sql ]"));
		  } 
		catch (Exception $e) 
		  {
			Rollback($cn);
			$msg="D&eacute;sol&eacute; mais il n a pas &eacute;t&eacute; possible d'annuler ".
			  "cette op&eacute;ration";

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
   echo '<h2 class="info"> Op&eacute;ration Annul&eacute;e</h2>';
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
			// Set the record to A (annulate) in quant_sold and quand_purchase
			// delete from the rapt table
			$sql="delete from jrn_rapt where jr_id = any (select jr_id from jrn ".
			  " where jr_grpt_id = ".$_POST['p_id'].")";
			$Res=ExecSql($cn,$sql,false);
			
			if ( $Res == false) 
			  throw (new Exception(__FILE__.__LINE__."sql a echoue [ $sql ]"));

			
			
			$Res=ExecSql($cn,"delete from  quant_sold  ".
						 " where qs_internal='".$l_array['jr_internal']."'",
						 false);
			
			if ( $Res == false) 
			  throw (new Exception(__FILE__.__LINE__."sql a echoue [ $sql ]"));

	
			$Res=ExecSql($cn,"delete from quant_purchase  ".
						 " where qp_internal='".$l_array['jr_internal']."'",
						 false);
	
			if ( $Res == false) 
			  throw (new Exception(__FILE__.__LINE__."sql a echoue [ $sql ]"));
			
			if ( $l_array['jr_pj'] != "") 
			  {
				$Res=pg_lo_unlink($cn,$l_array['jr_pj']);
				if ( $Res == false) 
				  throw (new Exception(__FILE__.__LINE__.
									   "Echec Effacement lob  [ $sql ]"));

			  }

			// delete from the stock table
			$sql="delete from stock_goods where sg_id = any ( select sg_id
 from stock_goods natural join jrnx  where j_grpt=".$_POST['p_id'].")";
			$Res=ExecSql($cn,$sql,false);
	
			if ( $Res == false) 
			  throw (new Exception(__FILE__.__LINE__."sql a echoue [ $sql ]"));
			// delete from CA
			$sql="delete from operation_analytique where j_id in (select j_id from";
			$sql.="  jrnx where j_grpt=".$_POST['p_id'].")";
			$Res=ExecSql($cn,$sql,false);
			if ( $Res == false) 
			  throw (new Exception(__FILE__.__LINE__."sql a echoue [ $sql ]"));

			// delete from jrnx & jrn
			$sql="delete from jrnx  where j_grpt=".$_POST['p_id'];
	
			$Res=ExecSql($cn,$sql,false);
			if ( $Res == false) 
			  throw (new Exception(__FILE__.__LINE__."sql a echoue [ $sql ]"));
	
			// build the sql stmt for jrn
			$sql= "delete from jrn    where   jr_grpt_id=".$_POST['p_id'];
			$Res=ExecSql($cn,$sql,false);
	
			if ( $Res == false) 
			  throw (new Exception(__FILE__.__LINE__." sql a echoue [ $sql ]"));

		  } 
		catch (Exception $e) 
		  {
			Rollback($cn);
			$msg="Desole mais il n a pas ete possible d'annuler ".
			  "cette operation";
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
	echo '<h2 class="info"> Op&eacute;ration Annul&eacute;e</h2>';
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
echo '<div align="center"> Op&eacute;ration '.$l_array['jr_internal'].'</div> 
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
  $lib=GetPosteLibelle($gDossier,${"e_class_deb$i"}); 
  echo '<div style="background-color:#BFC2D5;">';
  echo ${"e_class_deb$i"}." $lib    "."<B>".${"e_mont_deb$i"}."</B>";
  echo "</div>";
}
for ( $i = 0; $i < $max_cred;$i++) {
  $lib=GetPosteLibelle($gDossier,${"e_class_cred$i"});
  echo '<div style="background-color:#E8F4FF;">';
  echo ${"e_class_cred$i"}."  $lib   "."<B>".${"e_mont_cred$i"}."</B>";
  echo '</div>';
}
//echo "operation concern&eacute;e $e_rapt<br><br>
//";
$a=GetConcerned($cn,$e_jr_id);

if ( $a != null ) {

  foreach ($a as $key => $element) {
    echo "operation concern&eacute;e <br>";
$operation=new Acc_Operation($cn);
$operation->jr_id=$element;

    $w=new widget("button");
    $w->label=$operation->get_internal();
    $w->javascript="modifyOperation('".$element."','".$_REQUEST['PHPSESSID']."',".dossier::id().
      ','.$_REQUEST['p_jrn'].",'S')";
    echo $w->IOValue().'<br>';
  }//for
}// if ( $a != null ) {

echo dossier::hidden().
'
<input type="hidden" name="p_id" value="'.$_GET['jrn_op'].'">
<input type="submit" name="annul"  value="Mise &agrave; z&eacute;ro ou effacement">
<input type="button" name="cancel" value="Retour" onClick="window.close();">
</form>';

html_page_stop();
?>
