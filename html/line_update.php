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
// $Revision$
/*! \file
 * \brief Modify the "Plan Comptable"
 */
include_once ("ac_common.php");
require_once('class_dossier.php');
$gDossier=dossier::id();
require_once('class_database.php');
require_once ('class_acc_account.php');
/* Admin. Dossier */
$rep=new Database();
include_once ("class_user.php");
$User=new User($rep);
$User->Check();
html_page_start($User->theme,"onLoad='window.focus();'");
$cn=new Database($gDossier);
$User->can_request(PARPCMN);


include ("user_menu.php");
/* Modif d'une ligne */
if ( isset ($_POST["update"] ) ) {

  echo JS_UPDATE_PCMN;

    $p_val=trim($_POST["p_val"]);
    $p_lib=FormatString($_POST["p_lib"]);
    $p_parent=trim($_POST["p_parent"]);
    $old_line=trim($_POST["p_old"]);
    $p_type=htmlentities($_POST['p_type']);
    $acc=new Acc_Account($cn);
    $acc->set_parameter('libelle',$p_lib);
    $acc->set_parameter('value',$p_val);
    $acc->set_parameter('parent',$p_parent);
    $acc->set_parameter('type',$p_type);
    // Check if the data are correct 
    try {
      $acc->check() ; 
    }catch (Exception $e) {
      $message="Valeurs invalides, pas de changement \n ".
	$e->getMessage();
	      echo '<script> alert(\''.$message.'\'); 
           </script>';
	      echo "<script> 
		window.close();
		 self.opener.RefreshMe();

		</script>";
	      exit();

	}
    echo_debug('line_update.php',__LINE__,"Update old : $old_line News = $p_val $p_lib");
    if ( strlen ($p_val) != 0 && strlen ($p_lib) != 0 && strlen($old_line)!=0 ) {
      if (strlen ($p_val) == 1 ) {
	$p_parent=0;
      } else {
	if ( strlen($p_parent)==0 ) {
	  $p_parent=substr($p_val,0,strlen($p_val)-1);
	  echo_debug('line_update.php',__LINE__,"Modif valeur = $p_val parent = $p_parent");
	}
      }
      /* Parent existe */
      $Ret=$cn->exec_sql("select pcm_val from tmp_pcmn where pcm_val=$1",array($p_parent));
      if ( ($p_parent != 0 && pg_NumRows($Ret) == 0) || $p_parent==$old_line ) {
	echo '<SCRIPT> alert(" Ne peut pas modifier; aucun poste parent"); </SCRIPT>';
      } else {
	$acc->update($old_line);	
      }
    } else {
      echo '<script> alert(\'Update Valeurs invalides\'); </script>';
    }

  echo "<script> 
        window.close();
         self.opener.RefreshMe();

        </script>";

}

?>
<FORM ACTION="line_update.php" METHOD="POST">
<?
$acc=new Acc_Account($cn);
$acc->set_parameter('value',$_GET['l']);
$acc->load();

echo $acc->form(true);
?>
<TABLE>
<TR>
<TD><INPUT TYPE="Submit" VALUE="Sauve">
<INPUT TYPE="HIDDEN" name="update">
  <?php printf ('<INPUT TYPE="HIDDEN" name="p_old" value="%s">',h($acc->get_parameter('value'))); ?>
</TD><TD><input type="button"  Value="Retour sans sauver" onClick='window.close();'></TD></TR>
</TABLE>
</FORM>


<?php
html_page_stop();
?>
