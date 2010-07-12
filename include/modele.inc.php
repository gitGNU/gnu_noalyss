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
 *
 * 
 * \brief concerne only the template
 *
 */
require_once("class_itext.php");
require_once("class_icheckbox.php");
$sa=(isset ( $_REQUEST['sa']))?$_REQUEST['sa']:'list';
if ( isset ($_POST['upd']) &&
     isset($_POST['m'])){
  if (  isset($_POST['name']) && isset($_POST['desc'])) {
    extract($_POST);
    $cn=new Database();
    if ( strlen(trim($name)) !=0 
	 && $cn->get_value('select count(*) from modeledef where '.
		       'mod_name=$1 and mod_id !=$2',
		       array(trim($name),$m)) == 0
	 )
      {

	$cn->exec_sql("update modeledef set mod_name=$1, ".
		     " mod_desc=$2 where mod_id=$3 ",
		     array(trim($name),trim($desc),$m));
	
	
      }
  }
  $sa="list";
 }
echo  JS_CONFIRM;

$cn=new Database();



// IF FMOD_NAME is posted then must add a template
if ( isset ($_POST["FMOD_NAME"]) ) {
  $encoding=$cn->get_value("select encoding from pg_database  where ".
		       " datname='".domaine.'dossier'.FormatString($_POST["FMOD_DBID"])."'");

  if ( $encoding != 6 ) {
    alert('Désolé vous devez migrer ce modèle en unicode');
    echo '<span class="error">la base de donnée '.
      domaine.'mod'.$_POST["FMOD_DBID"]." doit être migrée en unicode</span>";
    echo '<span class="error"> Pour le passer en unicode, faites-en un backup puis restaurez le fichier reçu</span>';

    echo HtmlInput::button_anchor('Retour','admin_repo.php?action=dossier_mgt');
    exit();
  }

  $mod_name=FormatString($_POST["FMOD_NAME"]);
  $mod_desc=FormatString($_POST["FMOD_DESC"]);
  if ( $mod_name != null) {
    $Res=$cn->exec_sql("insert into modeledef(mod_name,mod_desc)
                        values ('".$mod_name."',".
		 "'".$mod_desc."')");
    
    // get the mod_id
    $l_id=$cn->get_current_seq('s_modid');
    if ( $l_id != 0 ) {
      $Sql=sprintf("CREATE DATABASE %sMOD%d encoding='UTF8' TEMPLATE %sDOSSIER%s",domaine,$l_id,domaine,$_POST["FMOD_DBID"]);
      ob_start();
      if ( $cn->exec_sql($Sql)==false) {
	ob_end_clean();
	echo "<h2 class=\"error\"> Base de donn&eacute;e ".domaine."dossier".$_POST['FMOD_DBID']."  est accèd&eacute;e, d&eacute;connectez-vous en d'abord</h2>";
	$Res=$cn->exec_sql("delete from modeledef where mod_id=".$l_id);

	exit;
      }
    }
  }// if $mod_name != null
  
  $cn_mod=new Database($l_id,'mod');
  
  // Clean some tables 

  $Res=$cn_mod->exec_sql("select distinct jr_pj from jrn where jr_pj is not null ");
  if ( Database::num_row($Res) != 0 )
    {
      $a_lob=Database::fetch_all($Res);
      for($i=0;$i<count($a_lob);$i++) 
	$cn_mod->lo_unlink($a_lob[$i]['jr_pj']);
    }
  
  $Res=$cn_mod->exec_sql("truncate table centralized");
  $Res=$cn_mod->exec_sql("truncate table jrn cascade");
  $Res=$cn_mod->exec_sql("delete from del_jrn");
  $Res=$cn_mod->exec_sql("delete from del_jrnx");
  $Res=$cn_mod->exec_sql("truncate table  jrnx cascade ");
  $Res=$cn_mod->exec_sql("delete from del_action");

  $Res=$cn_mod->exec_sql('delete from operation_analytique');

  // TODO 

  $Res=$cn_mod->exec_sql("truncate table import_tmp");
  //	Reset the closed periode
  $Res=$cn_mod->exec_sql("update parm_periode set p_closed='f'");
  $Res=$cn_mod->exec_sql('delete from jrn_periode');
  $Res=$cn_mod->exec_sql(' insert into jrn_periode(p_id,jrn_def_id,status) '.
	    ' select p_id,jrn_def_id,\'OP\' '.
	    ' from '.
	    ' parm_periode cross join jrn_def');

  // Reset Sequence
  $a_seq=array('s_jrn','s_jrn_op','s_centralized','s_stock_goods','s_internal');
  foreach ($a_seq as $seq ) {
    $sql=sprintf("select setval('%s',1,false)",$seq);
    $Res=$cn_mod->exec_sql($sql);
  }
  $sql="select jrn_def_id from jrn_def ";
  $Res=$cn_mod->exec_sql($sql);
  $Max=Database::num_row($Res);
  for ($seq=0;$seq<$Max;$seq++) {
    $row=Database::fetch_array($Res,$seq);
    /* if seq doesn't exist create it */
    if ( $cn_mod->exist_sequence('s_jrn_'.$row['jrn_def_id']) == false ) {
      $cn_mod->create_sequence('s_jrn_'.$row['jrn_def_id']);
    }

    
    $sql=sprintf ("select setval('s_jrn_%d',1,false)",$row['jrn_def_id']);
    $cn_mod->exec_sql($sql);

    $sql=sprintf ("select setval('s_jrn_pj%d',1,false)",$row['jrn_def_id']);
    $cn_mod->exec_sql($sql);
    $sql=sprintf ("select setval('jnt_letter_jl_id_seq',1,false)");
    $cn_mod->exec_sql($sql);

  }
  //---
  // Cleaning Action
  //-- 
  if ( isset($_POST['DOC'] ))
    {
      $Res=$cn_mod->exec_sql("delete from action_gestion");
      $Res=$cn_mod->exec_sql("delete from document");

      // Remove lob file
      $Res=$cn_mod->exec_sql("select distinct loid from pg_largeobject except select md_lob from document_modele");
      if ( Database::num_row($Res) != 0 )
	{
	  $a_lob=Database::fetch_all($Res);
	  //var_dump($a_lob);
	  foreach ($a_lob as $lob) {
	    $cn_mod->lo_unlink($lob['loid']);
	  }
	}
    }
  if ( isset($_POST['CARD'])) 
    {
      $Res=$cn_mod->exec_sql("delete from  attr_value");
      $Res=$cn_mod->exec_sql("delete from  jnt_fic_att_value");
      $Res=$cn_mod->exec_sql("delete from   fiche");
      $Res=$cn_mod->exec_sql("delete from action_gestion");
      $Res=$cn_mod->exec_sql("delete from document");
      $Res=$cn_mod->exec_sql("delete from document_modele");
      $Res=$cn_mod->exec_sql("delete from op_predef");

      // Remove lob file
      $Res=$cn_mod->exec_sql("select distinct loid from pg_largeobject");
      if ( Database::num_row($Res) != 0 )
			  {
			    $a_lob=Database::fetch_all($Res);
			    foreach ($a_lob as $lob) 
			     $cn_mod->lo_unlink($lob['loid']);
			  }
      
      
    }
  if ( isset($_POST['CANAL'])) {
    $Res=$cn_mod->exec_sql('delete from poste_analytique');
    $Res=$cn_mod->exec_sql('delete from plan_analytique');
  }  
  
 }
// Show all available templates

$Res=$cn->exec_sql("select mod_id,mod_name,mod_desc from 
                      modeledef order by mod_name");
$count=Database::num_row($Res);
echo '<div class="content">';
echo "<H2>Modèles</H2>";
if ( $sa=='list') {
  if ( $count == 0 ) {
    echo "No template available";
  } else {
    

    echo HtmlInput::button_anchor('Rafra&icirc;chir','admin_repo.php?action=modele_mgt');
    echo HtmlInput::button_anchor('Ajouter','admin_repo.php?action=modele_mgt&sa=add');

    echo '<table width="100%" >';
    echo "<TR><TH>Nom</TH>".
      "<TH>Description</TH>".
      "<th></th>".
      "</TR>";
  
    for ($i=0;$i<$count;$i++) {
      $mod=Database::fetch_array($Res,$i);
      printf('<TR>'.
	     '<TD>%d <b> %s</b> </TD>'.
	     '<TD><I> %s </I></TD>'.
	     '<td> '.
	     HtmlInput::button_anchor('Effacer','?action=modele_mgt&sa=del&m='.$mod['mod_id']).'</td>'.
	     '</td>'.
	     '<td>'.HtmlInput::button_anchor('Modifie','?action=modele_mgt&sa=mod&m='.$mod['mod_id']).'</td>'.
	     '</td>'.
	     '<td>'.HtmlInput::button_anchor('Backup','backup.php?action=backup&sa=b&t=m&d='
				      .$mod['mod_id']).'</td>'.
	     '</TR>',
	     $mod['mod_id'],
	     $mod['mod_name'],
	     $mod['mod_desc']);
    
  }// for
  echo "</table>";
 }// if count = 0
echo "Si vous voulez r&eacute;cup&eacute;rer toutes les adaptations d'un dossier ".
" dans un autre dossier, vous pouvez en faire un modèle.".
" Seules les fiches, la structure des journaux, les p&eacute;riodes,... seront reprises ".
"et aucune donn&eacute;e du dossier sur lequel le dossier est bas&eacute;.";
 }
//---------------------------------------------------------------------------
// Add a template
//---------------------------------------------------------------------------
if ( $sa == 'add') {
// Show All available folder
$Res=$cn->exec_sql("select dos_id, dos_name,dos_description from ac_dossier
                      order by dos_name");
$count=Database::num_row($Res);
$available="";
if ( $count != 0 ) {
  $available='<SELECT NAME="FMOD_DBID">';
  for ($i=0;$i<$count;$i++) {
    $db=Database::fetch_array($Res,$i);
    $available.='<OPTION VALUE="'.$db['dos_id'].'">'.$db['dos_name'].':'.$db['dos_description'];
  }//for i
  $available.='</SELECT>';
 }//if count !=0
?>
<form action="admin_repo.php?action=modele_mgt" METHOD="post">
<TABLE>
<tr>
    <td>Nom </TD>
    <TD><INPUT TYPE="TEXT" VALUE="" NAME="FMOD_NAME"></TD>
</TR>
<TR>
    <TD>Description</TD>
    <TD><TEXTAREA ROWS="2" COLS="60" NAME="FMOD_DESC"></Textarea></TD>
</TR>
<TR>
    <TD> Bas&eacute; sur </TD>
    <TD> <?php   echo $available ?></TD>
</TR>
<TR><TD>Nettoyage des Documents et courriers (ce qui  n'effacera pas les modèles de documents)</TD><TD> <input type="checkbox" name="DOC"></TD></TR>
<TR><TD>Nettoyage de toutes les fiches (ce qui effacera client, op&eacute;rations pr&eacute;d&eacute;finies fournisseurs modèles de documents et documents)</TD><TD> <input type="checkbox" name="CARD"></TD></TR>

<TR><TD>Nettoyage de la comptabilit&eacute; analytique : effacement des plans et des postes, les op&eacute;rations sont de toute fa&ccedil;on effac&eacute;es </TD><TD> <input type="checkbox" name="CANAL"></TD></TR>
</TABLE>
    
'

<INPUT TYPE="SUBMIT" class="button" VALUE="Ajout d'un modele">
<?php
echo HtmlInput::button_anchor('Retour','?action=modele_mgt');
?>

</form>
<?php
}
//---------------------------------------------------------------------------
// Modify
 if ( $sa == 'mod' && isset($_GET['m']) ){
   $cn=new Database();

   echo '<form method="post">';
   $name=$cn->get_value(
		    "select mod_name from modeledef where ".
		    " mod_id=$1",
		    array($_GET['m']));

   $desc=$cn->get_value(
		    "select mod_desc from modeledef where ".
		    " mod_id=$1",
		    array($_GET['m']));
   $wText=new IText();
   echo 'Nom : '.$wText->input('name',$name);
   $wDesc=new ITextArea();
   $wDesc->heigh=5;
   echo '<br>Description :<br>';
   echo $wDesc->input('desc',$desc);
   echo HtmlInput::hidden('m',$_GET['m']);
   echo HtmlInput::hidden('action','modele_mgt');
   echo '<br>';
   echo HtmlInput::button_anchor('Retour','?action=modele_mgt');
   echo HtmlInput::submit('upd','Modifie');
   echo '</form>';
 }

//---------------------------------------------------------------------------
// action = del
//---------------------------------------------------------------------------
if ( $sa == 'del' ) {
  $cn=new Database();
  $name=$cn->get_value('select mod_name from modeledef where mod_id=$1',array($_REQUEST['m']));
  echo '<form method="post">';
  echo HtmlInput::hidden('d',$_REQUEST['m']);
  echo HtmlInput::hidden('sa','remove');
  echo '<h2 class="error">Etes vous sure et certain de vouloir effacer '.$name.' ???</h2>';
  $confirm=new ICheckBox();
  $confirm->name="p_confirm";
  echo 'Cochez la case si vous êtes sûr de vouloir effacer ce modèle';
  echo $confirm->input();
  echo HtmlInput::submit('remove','Effacer');
  echo HtmlInput::button_anchor('Retour','?action=modele_mgt');
  echo '</form>';
 }
//---------------------------------------------------------------------------
// action = del
//---------------------------------------------------------------------------
if ( $sa == 'remove' ) {
  if ( ! isset ($_REQUEST['p_confirm'])) {
    echo('Désolé, vous n\'avez pas coché la case');  
    echo HtmlInput::button_anchor('Retour','?action=modele_mgt');
    exit();
  }

  $cn=new Database();
   $msg="dossier";
   $name=$cn->get_value("select mod_name from modeledef where mod_id=$1",array($_REQUEST['m']));
   if ( strlen(trim($name)) == 0 )
     {
       echo "<h2 class=\"error\"> $msg inexistant</h2>";
       exit();
     }
   $sql="drop database ".domaine."mod".FormatString($_REQUEST['m']);
   ob_start();
   if ( $cn->exec_sql($sql)==false) {
     ob_end_clean();
     
     echo "<h2 class=\"error\"> 
         Base de donnée ".domaine."mod".$_REQUEST['m']."  est accèdée, déconnectez-vous d'abord</h2>";
     exit;
   }
   ob_flush();
   $sql="delete from modeledef where mod_id=$1";
   $cn->exec_sql($sql,array($_REQUEST['m']));
   print '<h2 class="info">';
   print "Voilà le modèle $name est effacé</H2>";
   echo HtmlInput::button_anchor('Retour','?action=modele_mgt');
 }
 echo '</div>';
?>

