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

/*!\file 
* \brief Management of the folder
 *
 */
require_once ('class_widget.php');
$sa=(isset($_REQUEST['sa']))?$_REQUEST['sa']:'list';
//---------------------------------------------------------------------------
// Update
if ( isset ($_POST['upd']) && isset ($_POST['d'])) {
  $dos=new dossier($_POST['d']);
  $dos->set_parameter('name',$_POST['name']);
  $dos->set_parameter('desc',$_POST['desc']);
  $dos->save();
 }
echo '<div class="u_redcontent">';
// check and add an new folder
if ( isset ($_POST["DATABASE"]) ) {
  $cn=DbConnect();
  $dos=trim($_POST["DATABASE"]);
  $dos=FormatString($dos);
  if (strlen($dos)==0) {
    echo ("Le nom du dossier est vide");
    exit -1;
  }
  $encoding=getDbValue($cn,"select encoding from pg_database  where ".
		       " datname='".domaine.'mod'.FormatString($_POST["FMOD_ID"])."'");
  if ( $encoding != 6 ) {
    echo "<script> alert('Désolé vous devez migrer ce modèle en unicode')</script>";
    echo '<span class="error">le modele '.domaine.'mod'.$_POST["FMOD_ID"]." doit être migré en unicode.";
    echo 'Pour le passer en unicode, faites-en un backup puis restaurez le fichier reçu</span>';
    echo widget::button_href('Retour','admin_repo.php?action=dossier_mgt');
    exit();
  }

  $desc=FormatString($_POST["DESCRIPTION"]);
  try  {
    StartSql($cn);
    $Res=ExecSql($cn,"insert into ac_dossier(dos_name,dos_description)
                    values ('".$dos."','$desc')");
    $l_id=GetSequence($cn,'dossier_id');
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
	    $Sql=sprintf("CREATE DATABASE %sDOSSIER%d encoding='UTF8' TEMPLATE %sMOD%d",
			 domaine,
			 $l_id,
			 domaine,
			 FormatString($_POST["FMOD_ID"]));
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
    $cn=DbConnect();
//---------------------------------------------------------------------------
// List of folder
if ( $sa == 'list' ) {
     echo widget::button_href('Rafra&icirc;chir','admin_repo.php?action=dossier_mgt');
     echo widget::button_href('Ajouter','admin_repo.php?action=dossier_mgt&sa=add');

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
	  $Dossier['dos_id']." <B>".h($Dossier['dos_name'])."</B> </TD>".
	  "<TD><I>  ".h($Dossier['dos_description'])."</I>
</TD>
<TD>";
	echo widget::button_href('Effacer','?action=dossier_mgt&sa=del&d='.$Dossier['dos_id']);
	echo "</TD>";
	echo '<td>'.widget::button_href('Modifier','?action=dossier_mgt&sa=mod&d='
					.$Dossier['dos_id']).
	  '</td>';
	echo '<td>'.widget::button_href('Backup','backup.php?action=backup&sa=b&t=d&d='
					.$Dossier['dos_id']).
	  '</td>';

	echo '<tr>';
	$compteur++; 
	
      }

      echo "</TR>";
      
    }
    echo '</table>';
    
   echo jrn_navigation_bar($offset,$count,$size,$page); 

   }

//---------------------------------------------------------------------------
// Add a new folder
 if ( $sa == 'add' ) {
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
       $template.='<OPTION VALUE="'.$mod['mod_id'].'"> '.h($mod['mod_name']." - ".substr($mod['mod_desc'],0,30));
     }// for
     $template.="</SELECT>";
   }// if count = 0
   $m_date=date('Y');
   
?>

</TABLE>

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
<td>
<?php  echo widget::button_href('Retour','admin_repo.php?action=dossier_mgt'); ?>
</td>
    </TR>
    </TABLE>
 </FORM>
<?php
												       }
//---------------------------------------------------------------------------
// action= mod
if ( $sa == 'mod' ) {
  require_once ('class_dossier.php');
  $dos=new dossier($_REQUEST['d']);
  $dos->load();
  $wText=new widget('text');
  echo '<form action="admin_repo.php" method="post">';
  echo widget::hidden('action','dossier_mgt');
  echo widget::hidden('d',$dos->get_parameter("id"));
  echo 'Nom : ';
  echo  $wText->IOValue('name',$dos->get_parameter('name'));
  echo '<br>';
  $wDesc=new widget('textarea');
  $wDesc->heigh=5;
  echo 'Description : <br>';
  echo  $wDesc->IOValue('desc',$dos->get_parameter('desc'));
  echo '<br>';
  echo widget::submit('upd','Modifie');
  echo widget::button_href('Retour','?action=dossier_mgt');
  echo '</form>';
 }
//---------------------------------------------------------------------------
// action = del
//---------------------------------------------------------------------------
if ( $sa == 'del' ) {
  $d=new Dossier($_REQUEST ['d'] );
  $d->load();
  echo '<form method="post">';
  echo widget::hidden('d',$_REQUEST['d']);
  echo widget::hidden('sa','remove');
  echo '<h2 class="error">Etes vous sure et certain de vouloir effacer '.$d->dos_name.' ???</h2>';
  $confirm=new widget('checkbox');
  $confirm->name="p_confirm";
  echo 'Cochez la case si vous êtes sûr de vouloir effacer ce dossier';
  echo $confirm->IOValue();
  echo widget::submit('remove','Effacer');
  echo widget::button_href('Retour','?action=dossier_mgt');
  echo '</form>';
 }
//---------------------------------------------------------------------------
// action = del
//---------------------------------------------------------------------------
if ( $sa == 'remove' ) {
  if ( ! isset ($_REQUEST['p_confirm'])) {echo('Désolé, vous n\'avez pas coché la case');  echo widget::button_href('Retour','?action=dossier_mgt');exit();}

  $cn=DbConnect();
   $msg="dossier";
   $name=getDbValue($cn,"select dos_name from ac_dossier where dos_id=$1",array($_REQUEST['d']));
   if ( strlen(trim($name)) == 0 )
     {
       echo "<h2 class=\"error\"> $msg inexistant</h2>";
       exit();
     }
   $sql="drop database ".domaine."dossier".FormatString($_REQUEST['d']);
   ob_start();
   if ( pg_query($cn,$sql)==false) {
     ob_end_clean();
     
     echo "<h2 class=\"error\"> 
         Base de donnée ".domaine."dossier".$_REQUEST['d']."  est accèdée, déconnectez-vous d'abord</h2>";
     exit;
   }
   ob_flush();
   $sql="delete from priv_user where priv_id in (select jnt_id from jnt_use_dos where dos_id=$1)";
   ExecSqlParam($cn,$sql,array($_REQUEST['d']));
   $sql="delete from  jnt_use_dos where dos_id=$1";
   ExecSqlParam($cn,$sql,array($_REQUEST['d']));
   $sql="delete from ac_dossier where dos_id=$1";
   ExecSqlParam($cn,$sql,array($_REQUEST['d']));
   print '<h2 class="info">';
   print "Voilà le dossier ".h($name)." est effacé</h2>";
   echo widget::button_href('Retour','?action=dossier_mgt');
 }
?>
</div>
