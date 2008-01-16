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

/* !\file 
 */

/* \brief Management of the folder
 *
 */
require_once ('class_widget.php');

echo '<div class="u_redcontent">';
// check and add an new folder
if ( isset ($_POST["DATABASE"]) ) {
  $cn=DbConnect();
  $dos=trim($_POST["DATABASE"]);
  $dos=FormatString($dos);
      if (strlen($dos)==0) {
	echo ("Dataname name is empty");
	exit -1;
      }
      $desc=FormatString($_POST["DESCRIPTION"]);
      try  {
	StartSql($cn);
      $Res=ExecSql($cn,"insert into ac_dossier(dos_name,dos_description)
                    values ('".$dos."','$desc')");
      $l_id=GetDbId($dos);
      Commit($cn);
      } catch (Exception $e) {
	$msg="Desole la creation de ce dossier a echoue, la cause la plus probable est".
	  ' deux fois le même nom de dossier';
	echo '<script>alert("'.$msg.'");</script>';
	echo_debug(__FILE__.':'.__LINE__.'- echec ','Echec creation ',$e);
	$l_id=0;	
	Rollback($cn);

      }
      // If the id is not null, name successfully inserted
      // Database created

      if ( $l_id != 0) {
	//--
	// setting the year
	//--
	$year=FormatString($_POST['YEAR']);
	if ( strlen($year) != 4 || isNumber($year) == 0 || $year > 2100 || $year < 2000 || $year != round($year,0))
	  {
	    echo "$year est une année invalide";
	    $Res=ExecSql($cn,"delete from ac_dossier where dos_id=$l_id");
	  }
	else 
	  {
	    $Sql=sprintf("CREATE DATABASE %sDOSSIER%d encoding='ISO8859-1' TEMPLATE %sMOD%d",
			 domaine,
			 $l_id,
			 domaine,
			 $_POST["FMOD_ID"]);
	    echo_debug("[".$Sql."]");
	    ob_start();
	    if ( pg_query($cn,$Sql)==false) {
	      echo   "[".$Sql."]";

	      ob_end_clean();
	      ExecSql($cn,"delete from ac_dossier where dos_id=$l_id");
	      echo "<h2 class=\"error\"> Base de donnée ".domaine."mod".$_POST['FMOD_ID']."  est accèdée, déconnectez-vous d'abord</h2>";
	      exit;
		}
	    ob_flush();
	    $Res=ExecSql($cn,"insert into jnt_use_dos (use_id,dos_id) values (1,$l_id)");
	    // Connect to the new database
	    $cn=DbConnect($l_id);
	    //--year --
	    $Res=ExecSql($cn,"delete from parm_periode");
	    if ( ($year % 4 == 0 && $year % 100 != 0) || $year % 400 == 0 )  
	      $fev=29;
	    else
	      $fev=28;

	    $Res=ExecSql($cn,"delete from user_local_pref where parameter_type='PERIODE'");
	    $nb_day=array(31,$fev,31,30,31,30,31,31,30,31,30,30);
	    $m=1;
	    foreach ($nb_day as $day) 
	      {
			$p_start=sprintf("01-%d-%s",$m,$year);
			$p_end=sprintf("%d-%d-%s",$day,$m,$year);
			$sql=sprintf("insert into parm_periode (p_start,p_end,p_exercice)
                              values (to_date('%s','DD-MM-YYYY'),to_date('%s','DD-MM-YYYY'),'%s')",
			     $p_start,$p_end,$year);
			$Res=ExecSql($cn,$sql);
			$m++;
	      }
	    $sql=sprintf("insert into parm_periode (p_start,p_end,p_exercice)
                              values (to_date('31-12-%s','DD-MM-YYYY'),to_date('31-12-%s','DD-MM-YYYY'),'%s')",
					 $year,$year,$year);
	    $Res=ExecSql($cn,$sql);
	    $sql="	insert into jrn_periode(p_id,jrn_def_id,status) ".
	      "select p_id,jrn_def_id, 'OP'".
	      " from parm_periode cross join jrn_def";
	    $Res=ExecSql($cn,$sql);
	

	  }
      } // if $l_id != 0
    } // $_POST[DATABASE]
?>
   <h2> Dossier Management</h2>

<?php  
  echo widget::button_href('Rafra&icirc;chir','admin_repo.php?action=dossier_mgt');
      
    $cn=DbConnect();
    $offset=(isset($_REQUEST['offset']))?$_REQUEST['offset']:0;
    $page=(isset($_REQUEST['page']))?$_REQUEST['page']:1;
    $count=getDbValue($cn,"select count(*) from ac_dossier");
    $size=10; 

    echo jrn_navigation_bar($offset,$count,$size,$page); 
    $Res=ShowDossier('all',$offset,$size);
    $compteur=1;
    $template="";
    echo JS_CONFIRM;
    echo '<TABLE BORDER=1>';

    // show all dossiers
    if ( $Res != null ) {
      foreach ( $Res as $Dossier) {
	
	if ( $compteur%2 == 0 ) 
	  $cl='class="odd"';
	else
	  $cl='class="even"';

	echo "<TR $cl><TD VALIGN=\"TOP\"> ".
	  $Dossier['dos_id']." <B>".$Dossier['dos_name']."</B> </TD>".
	  "<TD><I>  ".$Dossier['dos_description']."</I>
</TD>
<TD>
<input type=\"button\" name=\"Effacer\"".
'Value="Effacer" onClick="confirm_remove(\''.$_REQUEST['PHPSESSID'].'\',\''.$Dossier['dos_id'].'\',\'db\');" \>'.
	       '</td>'.
"</TD></TR>";

	$compteur++; 
	
      }

      echo "</TR>";
      
    }
    
    // Load the available Templates
    $Res=ExecSql($cn,"select mod_id,mod_name,mod_desc from 
                      modeledef order by mod_name");
    $count=pg_NumRows($Res);
    
if ( $count == 0 ) {
  echo "No template available";
} else {
  $template='<SELECT NAME=FMOD_ID>';
  for ($i=0;$i<$count;$i++) {
    $mod=pg_fetch_array($Res,$i);
    $template.='<OPTION VALUE="'.$mod['mod_id'].'"> '.$mod['mod_name']." - ".substr($mod['mod_desc'],0,30);
  }// for
      $template.="</SELECT>";
}// if count = 0
 $m_date=date('Y');
// Add a new folder
?>
</TABLE>
    <?php   echo jrn_navigation_bar($offset,$count,$size,$page); ?>
 <FORM ACTION="admin_repo.php?action=dossier_mgt" METHOD="POST">
    <TABLE>
    <TR>
    <TD> Nom du dossier</td><td>  <INPUT TYPE="TEXT" NAME="DATABASE"> </TD>
    </TR><TR>
    <TD> Description</td><td>  <TEXTAREA COLS="60" ROWS="2" NAME="DESCRIPTION" ></TEXTAREA> </TD>
    </TR>
    <TR> <TD> Modèle</td><td>  <?php   echo $template; ?> </TD></TR>
<TR><TD>Année </TD><TD><input type="text" size=4 name="YEAR" value=<?php  echo '"'.$m_date.'"'; ?>></TD></TR>
    <TR>
    <TD> <INPUT TYPE=SUBMIT VALUE="Creation Dossier"></TD>
    </TR>
    </TABLE>
 </FORM>
    
</div>
