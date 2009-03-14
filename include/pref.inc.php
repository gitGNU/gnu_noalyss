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
 * \brief this file is always included and then executed
 *        it permits to change the user preferences
 */

require_once('class_user.php');
require_once("class_iselect.php");
require_once("class_iperiod.php");
require_once('class_acc_report.php');
require_once('class_periode.php');

echo '<DIV class="content">';
//----------------------------------------------------------------------
// Change password

if ( isset($_POST['pass_1'])
     && strlen(trim($_POST['pass_1'])) != 0 ) {
  if ($_POST['pass_1'] != $_POST['pass_2'] ) {
  ?>
<script>
   alert("Les mots de passe ne correspondent pas. Mot de passe inchang&eacute;");
</script>
<?php  
    }
    else {
      $Rep=DbConnect();
      $l_pass=md5($_POST['pass_1']);
      $Res=ExecSql($Rep,"update ac_users set use_pass='$l_pass' where use_login='".$_SESSION['g_user']."'");
      $pass=$_POST['pass_1'];
      $_SESSION['g_pass']=$_POST['pass_1'];
      $g_pass=$_POST['pass_1'];
      echo "<i>Mot de passe est modifi&eacute;</i>";
    }
  }

$url=$_SERVER['REQUEST_URI'];
if ( ! isset ($_REQUEST['gDossier']) ) 
  {
    echo '<A class="mtitle" href="user_login.php"><input type="button" value="Retour"></a>';
  } else {
  echo '<h2 class="info">Changez vos pr&eacute;f&eacute;rences</h2>';
  }

?>

<div class="content">

<FORM ACTION="<?php   echo $url;?>" METHOD="POST">
<fieldset><legend> Options G&eacute;n&eacute;rales</legend>
<table>
<tr><td>
Mot de passe :
<td><input type="password" name="pass_1">
<input type="password" name="pass_2">
</td>
</tr>
<?php  
$Rep=DbConnect();
// charge tous les styles
$res=ExecSql($Rep,"select the_name from theme
                      order by the_name");
for ($i=0;$i < pg_NumRows($res);$i++){
  $st=pg_fetch_array($res,$i);
  $style[]=$st['the_name'];
}
// Formatte le display
$disp_style="<SELECT NAME=\"style_user\" >";
foreach ($style as $st){
  if ( $st == $_SESSION['g_theme'] ) {
    $disp_style.='<OPTION VALUE="'.$st.'" SELECTED>'.$st;
  } else {
    $disp_style.='<OPTION VALUE="'.$st.'">'.$st;
  }
}
$disp_style.="</SELECT>";
?>
<p>
<tr>
<td>
Th&egrave;me 
</td>
<td>
<?php   print $disp_style;?> 
</td>
</tr>

<?php  
/*!\todo Not used anymore to be removed ?
$topmenu=new ISelect();
$topmenu->name='topmenu';
$topmenu->selected=$_SESSION['g_topmenu'];
$array=array(
	     array('value'=>'TEXT','label'=>'Texte'),
	     array('value'=>'SELECT','label'=>'Menu deroulant')
	     );
$topmenu->value=$array;
?>
<tr><td>
Style de menu
</td>
<td>
<?php echo $topmenu->input();?>
</td>
</tr>

<?php
*/
$inside_dossier=false;
// Si utilise un dossier alors propose de changer
// la periode par defaut
if (  isset ($_REQUEST['gDossier']))
  {
    $inside_dossier=true;
    $msg=""; 
    $cn=DbConnect($_REQUEST['gDossier']);
    $User->cn=$cn;    

    if ( isset ($_POST['periode']))
      {    
	$periode=$_POST["periode"];
	$User->set_periode($periode);
      }

    $l_user_per=$User->get_periode();
    if ( $l_user_per=="") 
      $l_user_per=getDbValue($cn,"select min(p_id) from parm_periode where p_closed='f'");

    // if periode is closed then warns the users
	$period=new Periode($cn,$l_user_per);
	$period->jrn_def_id=0;
    if ( $period->is_closed($cn,$l_user_per)==1)
      {
	$msg= '<h2 class="notice">Attention cette p&eacute;riode est ferm&eacute;e, vous ne pourrez rien modifier dans le module comptable</h2>';
      }
    

	$period=new IPeriode("period");
	$period->user=$User;
	$period->cn=$cn;
	$period->value=$l_user_per;
	$period->type=ALL;
	$l_form_per=$period->input();
	
    
    
    ?>
<tr><td> P&eacute;riode</td>
<td>
<?php      printf(' %s ',$l_form_per); ?>
</td>
<td>  <?php   echo $msg; ?></td>
<?php  

}
?>

<tr>
<td>Taille des pages</td>
<td>
<SELECT NAME="p_size">
<option value="15">15
<option value="25">25
<option value="50">50
<option value="100">100
<option value="150">150
<option value="200">200
<option value="-1">Illimit&eacute;
<?php  
	$label=($_SESSION['g_pagesize'] == -1)?'Illimit&eacute;':$_SESSION['g_pagesize'];
	echo '<option value="'.$_SESSION['g_pagesize'].'" selected>'.$label;
?>
</SELECT>

</td>
</tr>
</table>
</fieldset>
<?php
if ( $inside_dossier ) {
  /* Pref for welcome page */
  echo '<fieldset>';
  echo '<legend> Options pour la page d\'accueil</legend>';
  echo 'Mini-Rapport : ';
  $rapport=new Acc_Report($cn);
  $aRapport=$rapport->make_array();
  $aRapport[]=array("value"=>0,"label"=>'Aucun mini rapport');
  $wRapport=new ISelect();
  $wRapport->name="minirap";
  $wRapport->selected=$User->get_mini_report();
  $wRapport->value=$aRapport;
  echo $wRapport->input();
  echo '<span class="notice">Le mini rapport est un rapport qui s\'affiche  sur votre page d\'accueil</span>';
  echo '</fieldset>';
}



echo HtmlInput::submit("val","Valider");
echo '</form>';
if ( ! $inside_dossier ) 
{
    echo '<A class="mtitle" href="user_login.php"><input type="button" value="Retour"></a>';
}


     
echo "</DIV>";
?>
