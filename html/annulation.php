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
 * \brief in a popup window manage the removal of the operations
 */
include_once ("ac_common.php");
include_once("central_inc.php");
include_once("user_common.php");
require_once('class_database.php');
include_once("jrn.php");
require_once("class_itext.php");
require_once("class_ibutton.php");
require_once("class_acc_ledger.php");
require_once("class_acc_operation.php");
require_once ('class_periode.php');
require_once('class_acc_reconciliation.php');


/* Admin. Dossier */
include_once ("class_user.php");
require_once('class_dossier.php');
$gDossier=dossier::id();

$cn=new Database($gDossier);
$User=new User($cn);
$User->Check();
$User->check_dossier(dossier::id());
if ($User->check_action(GEOP,0) == 0 ) {
  alert("Cette action n'est pas autorisée");
  echo '<script>window.close();<script>';
}

html_page_start($User->theme,"onLoad='window.focus();'");
echo JS_LEDGER;
if ( isset( $_REQUEST['p_jrn'] )) {
  $p_jrn=$_GET['p_jrn'];
  }

 // Check privilege
if ( $User->check_jrn($_GET['p_jrn']) != 'W') {
  alert("Vous ne pouvez pas accèder en écriture à ce journal");
  echo '<script>window.close();</script>';
  exit -1;
}


$operation=new Acc_Operation($cn);
list ($l_array,$max_deb,$max_cred)=$operation->get_data($_GET['jrn_op']);
extract ($l_array,EXTR_PREFIX_ALL,'e_');
foreach ($l_array as $key=>$element) { ${"e_".$key}=$element;}
// cancel an operation
if ( isset ($_POST['annul']) ) {
	/* Confirm it first */
	if ( ! isset ( $_POST['confirm'])) {
?>
<p>
<h2 class="info">Confirmation</h2>
<br>
	    <p><span class="error">Attention: l'effacement d'une op&eacute;ration peut rompre la s&eacute;quence de la num&eacute;rotation des factures  surtout celles g&eacute;n&eacute;r&eacute;es automatiquement ainsi que celle des pièces justificatives, soyez tr&egrave;s prudent quand vous effacez. La pi&egrave;ce jointe sera elle aussi effac&eacute;e. Si vous utilisez la comptabilit&eacute; analytique les op&eacute;rations li&eacute;es seront effac&eacute;es.
</span>
</p> 

<p> Voulez-vous vraiment annuler  cette information soit par une remise &agrave; z&eacute;ro des montants 
soit par son &eacute;criture inverse ?
</p>
<span  >
<FORM METHOD="POST" ACTION="annulation.php?p_jrn=<?php echo $_GET['p_jrn'];?>&jrn_op=<?php echo $_GET['jrn_op'];?>">
					   <?php echo dossier::hidden(); ?>
<INPUT TYPE="HIDDEN" NAME="annul">
<INPUT TYPE="HIDDEN" NAME="p_id" value="<?php echo $_POST['p_id']; ?>">
Date pour l'écriture inverse
<?
$idate=new IDate("idate");
echo $idate->input();
?>
<span class="notice">Une écriture inverse sera faite si vous utilisez le mode strict ou si vous êtes dans une période fermée</span>
<br>
<INPUT TYPE="SUBMIT" class="button" NAME="confirm" value="Oui"> 
</FORM>

<FORM METHOD="GET" ACTION="annulation.php">
					   <?php echo dossier::hidden(); ?>
<INPUT TYPE="HIDDEN" NAME="p_jrn" value="<?php echo $_REQUEST['p_jrn']; ?>">
<INPUT TYPE="HIDDEN" NAME="p_id" value="<?php echo $_REQUEST['p_id']; ?>">
<INPUT TYPE="HIDDEN" NAME="jrn_op" value="<?php echo $_REQUEST['jrn_op']; ?>">
<INPUT TYPE="SUBMIT" class="button" NAME="not_confirm" value="non">
</form>
</span>
<?php
return;
} // end confirm'


// Remove is confirmed
  if ( isset ($_POST['p_id'])) {
    $p_id=$_POST['p_id'];

if  ($p_id != -1 ) { // A
     // Test whether date of the operation is in a closed periode
    // get the period_id
   $p=$cn->exec_sql("select jr_tech_per from jrn where jr_grpt_id=$1",array($_REQUEST['jrn_op']));
   $period_id=Database::fetch_result($p,0,0);
    // thanks jrn_op  (jrn.jr_id) we find out the concerned ledger
	
    $sql="select jr_def_id from jrn where jr_grpt_id=".$_REQUEST['jrn_op'];
    $r=$cn->exec_sql($sql);
    $nJrn=Database::fetch_result($r,0,0);
    $per=new Periode($cn);
    $per->set_jrn($nJrn);
    $per->set_periode($period_id);
    $own=new Own($cn);
      // Check the period_id
    if ( $per->is_open() == 0 || $own->MY_STRICT=='Y')
	  {
	    if ( isDate($_POST['idate'])== 0 ) {
	      alert(_('Date invalide'));
	      echo create_Script('window.close()');
	      exit();
	    }
	    // Find the periode of idate
	    try {
	      $periode=new Periode($cn);
	      $periode->find_periode($_POST['idate']);
	    } catch (Exception $e){
	      alert(_('Période ou date incorrecte'));
	      echo create_Script('window.close()');
	      exit();
	    }
	    // if this periode is closed => end
	    if ($periode->is_open()==0) {
	      alert(_('Cette période est fermée'));
	      echo create_Script('window.close()');
	      exit();
	    }
	    try 
	      {
		
		// if the operation is in a closed or centralized period
		// the operation is voided thanks the opposite operation
		$cn->start();
		$grp_new=$cn->get_next_seq('s_grpt');
		$seq=$cn->get_next_seq("s_jrn");
		$oJrn=new Acc_Ledger($cn,$l_array['jr_def_id']);
		$p_internal=$oJrn->compute_internal_code($seq);
		
		$sql= "insert into jrn (
  		jr_id,jr_def_id,jr_montant,jr_comment,               
		jr_date,jr_grpt_id,jr_internal                 
		,jr_tech_per, jr_valid
  		) select $seq,jr_def_id,jr_montant,'Annulation '||jr_comment,
		to_date($1,'DD.MM.YYYY'),$grp_new,'$p_internal',
		".$periode->p_id.", true 
          from
	  jrn
	  where   jr_grpt_id=$2";
		$Res=$cn->exec_sql($sql,array($_POST['idate'],$_POST['p_id']));
   // Check return code
   if ( $Res == false) 
	 throw (new Exception(__FILE__.__LINE__."sql a echoue [ $sql ]"));

 
  // Make also the change into jrnx
   $sql= "insert into jrnx (
  	        j_date,j_montant,j_poste,j_grpt,               
                j_jrn_def,j_debit,j_text,j_internal,j_tech_user,j_tech_per,j_qcode
  		) select now(),j_montant,j_poste,$grp_new,
                  j_jrn_def,not (j_debit),j_text,'$p_internal','".$User->id."',
		  ".$periode->p_id.",j_qcode
	  from
	  jrnx
	  where   j_grpt=".$_POST['p_id'];
   $Res=$cn->exec_sql($sql);
   // Check return code
   if ( $Res == false) 
	 throw (new Exception(__FILE__.__LINE__."sql a echoue [ $sql ]"));

   
    // Mark the operation invalid into the ledger
    // to avoid to nullify twice the same op.
    $sql="update jrn set jr_comment='Annule : '||jr_comment where jr_grpt_id=".$_POST['p_id'];
    $Res=$cn->exec_sql($sql);
    // Check return code
	if ( $Res == false) 
	  throw (new Exception(__FILE__.__LINE__."sql a echoue [ $sql ]"));
	

   // Set the record to A (annulate) in quant_sold and quand_purchase
   

   $Res=$cn->exec_sql("update quant_sold set ".
				" qs_valid='A' where qs_internal='".$l_array['jr_internal']."'"
			       );

   if ( $Res == false) 
	 throw (new Exception(__FILE__.__LINE__."sql a echoue [ $sql ]"));


   $Res=$cn->exec_sql("update quant_purchase set ".
				" qp_valid='A' where qp_internal='".$l_array['jr_internal']."'"
				);
   if ( $Res == false) 
	 throw (new Exception(__FILE__.__LINE__."sql a echoue [ $sql ]"));






    // Add a "concerned operation to bound these op.together
    //
   $rec=new Acc_Reconciliation ($cn);
   $rec->set_jr_id($seq);
   $rec->insert($l_array['jr_id']);

   // Check return code
	if ( $Res == false ) { throw (new Exception(__FILE__.__LINE__."sql a echoue [ $sql ]"));}
    

   // the table stock must updated
   // also in the stock table
   $sql="delete from stock_goods where sg_id = any ( select sg_id
  from stock_goods natural join jrnx  where j_grpt=".$_POST['p_id'].")";
   $Res=$cn->exec_sql($sql);
   // Check return code
   if ( $Res == false) 
	 throw (new Exception(__FILE__.__LINE__."sql a echoue [ $sql ]"));
		  } 
		catch (Exception $e) 
		  {
			$cn->rollback();
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
   $cn->commit();
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
			$cn->start();
			$sql="insert into del_action(del_name,del_time) values ($1,now());";
			$cn->exec_sql($sql,array($_SESSION['g_user']));
			// Set the record to A (annulate) in quant_sold and quand_purchase
			// delete from the rapt table
			$sql="delete from jrn_rapt where jr_id = any (select jr_id from jrn ".
			  " where jr_grpt_id = ".$_POST['p_id'].")";
			$Res=$cn->exec_sql($sql);
			
			if ( $Res == false) 
			  throw (new Exception(__FILE__.__LINE__."sql a echoue [ $sql ]"));

			
			
			$Res=$cn->exec_sql("delete from  quant_sold  ".
						 " where qs_internal='".$l_array['jr_internal']."'"
					);
			
			if ( $Res == false) 
			  throw (new Exception(__FILE__.__LINE__."sql a echoue [ $sql ]"));

	
			$Res=$cn->exec_sql("delete from quant_purchase  ".
						 " where qp_internal='".$l_array['jr_internal']."'"
						 );
	
			if ( $Res == false) 
			  throw (new Exception(__FILE__.__LINE__."sql a echoue [ $sql ]"));
			
			if ( isset($l_array['jr_pj']) && $l_array['jr_pj'] != "") 
			  {
				$Res=$cn->lo_unlink($l_array['jr_pj']);
				if ( $Res == false) 
				  throw (new Exception(__FILE__.__LINE__.
									   "Echec Effacement lob  [ $sql ]"));

			  }

			// delete from the stock table
			$sql="delete from stock_goods where sg_id = any ( select sg_id
 from stock_goods natural join jrnx  where j_grpt=".$_POST['p_id'].")";
			$Res=$cn->exec_sql($sql);
	
			if ( $Res == false) 
			  throw (new Exception(__FILE__.__LINE__."sql a echoue [ $sql ]"));
			// delete from CA
			$sql="delete from operation_analytique where j_id in (select j_id from";
			$sql.="  jrnx where j_grpt=".$_POST['p_id'].")";
			$Res=$cn->exec_sql($sql);
			if ( $Res == false) 
			  throw (new Exception(__FILE__.__LINE__."sql a echoue [ $sql ]"));

			// delete from jrnx & jrn
			$sql="delete from jrnx  where j_grpt=".$_POST['p_id'];
	
			$Res=$cn->exec_sql($sql);
			if ( $Res == false) 
			  throw (new Exception(__FILE__.__LINE__."sql a echoue [ $sql ]"));
	
			// build the sql stmt for jrn
			$sql= "delete from jrn    where   jr_grpt_id=".$_POST['p_id'];
			$Res=$cn->exec_sql($sql);
	
			if ( $Res == false) 
			  throw (new Exception(__FILE__.__LINE__." sql a echoue [ $sql ]"));

		  } 
		catch (Exception $e) 
		  {
			$cn->rollback();
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

	$cn->commit();
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
  } // if Post['p_id']
 }//confirm
	//}// if annul
echo '<div align="center"> Op&eacute;ration '.$l_array['jr_internal'].'</div> 
<div>
<form action="'.$_SERVER['REQUEST_URI'].'" method="post" >';

$a=new IText();
$a->setReadOnly(false);

echo '<div style="border-style:solid;border-width:1pt;">';

$a->size=40;

echo 'Description :'.$a->input("comment",$e_comment);

echo '</DIV>';

if ( isset ($e_ech) ) {
  echo "<DIV> Echeance $e_ech </DIV>";
}
echo '<table width="95%">';
for ( $i = 0; $i < $max_deb;$i++) {
  echo '<tr style="background-color:#BFC2D5;">';
  if ( ${"e_qcode_deb".$i} == "-" ) {
    $poste=new Acc_Account($cn,${"e_class_deb$i"});
    $lib=$poste->get_lib();
  } else {
    $fiche=new Fiche($cn);
    $fiche->get_by_qcode(${"e_qcode_deb".$i},false);
    $lib=$fiche->getName();
  }
  echo td(${"e_class_deb$i"}).td(${"e_qcode_deb".$i}).td($lib).td("<B>".${"e_mont_deb$i"}."</B>").td(_('Débit'));
  echo '</tr>';

}
for ( $i = 0; $i < $max_cred;$i++) {
  if ( ${"e_qcode_cred".$i} == "-" ) {
    $poste=new Acc_Account($cn,${"e_class_cred$i"});
    $lib=$poste->get_lib();
  } else {
    $fiche=new Fiche($cn);
    $fiche->get_by_qcode(${"e_qcode_cred".$i},false);
    $lib=$fiche->getName();
  }

  echo '<tr>';
  echo td(${"e_class_cred$i"}).td(${"e_qcode_cred".$i}).td($lib).td("<B>".${"e_mont_cred$i"}."</B>").td(_('Crédit'));
  echo '</tr>';

}
echo '</table>';
/* concerned operation */
$rec=new Acc_Reconciliation($cn);
$rec->set_jr_id($e_jr_id);
$a=$rec->get();

if ( $a != null ) {

  foreach ($a as $key => $element) {
    echo "operation concern&eacute;e <br>";
$operation=new Acc_Operation($cn);
$operation->jr_id=$element;

    $w=new IButton();
    $w->label=$operation->get_internal();
    $w->javascript="modifyOperation('".$element."',".dossier::id().
      ','.$_REQUEST['p_jrn'].",'S')";
    echo $w->input().'<br>';
  }//for
}// if ( $a != null ) {

echo dossier::hidden().
'
<input type="hidden" name="p_id" value="'.$_GET['jrn_op'].'">
<input type="submit" class="button" name="annul"  value="Mise &agrave; z&eacute;ro ou effacement">
<input type="button" class="button" name="cancel" value="Retour" onClick="window.close();">
</form>';

html_page_stop();
?>
