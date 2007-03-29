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
/* $Revision$ */

// Copyright Author Dany De Bontridder ddebontridder@yahoo.fr

/* !\file 
 */

/* \brief concerne only the template
 *
 */
echo  JS_CONFIRM;

    $cn=DbConnect();
    // IF FMOD_NAME is posted then must add a template
    if ( isset ($_POST["FMOD_NAME"]) ) {
      $mod_name=FormatString($_POST["FMOD_NAME"]);
      $mod_desc=FormatString($_POST["FMOD_DESC"]);
      if ( $mod_name != null) {
	$Res=ExecSql($cn,"insert into modeledef(mod_name,mod_desc)
                        values ('".$mod_name."',".
		     "'".$mod_desc."')");
	
	// get the mod_id
	$l_id=GetSequence($cn,'s_modid');
	if ( $l_id != 0 ) {
	   $Sql=sprintf("CREATE DATABASE %sMOD%d encoding='ISO8859-1' TEMPLATE %sDOSSIER%s",domaine,$l_id,domaine,$_POST["FMOD_DBID"]);
	    ob_start();
            if ( pg_query($cn,$Sql)==false) {
		ob_clean();
		echo "<h2 class=\"error\"> Base de donnée ".domaine."dossier".$_POST['FMOD_ID']."  est accèdée, déconnectez-vous en d'abord</h2>";
		exit;
		}
 	}
      }// if $mod_name != null

      $cn_mod=dbconnect($l_id,'mod');
      // Clean some tables 
      $Res=ExecSql($cn_mod,"truncate table jrn");
      $Res=ExecSql($cn_mod,"truncate table jrnx");
      $Res=ExecSql($cn_mod,"truncate table centralized");
      $Res=ExecSql($cn_mod,"truncate table stock_goods");
      $Res=ExecSql($cn_mod,"truncate table jrn_rapt");
      //	Reset the closed periode
      $Res=ExecSql($cn_mod,"update parm_periode set p_closed='f'");
      // Reset Sequence
      $a_seq=array('s_jrn','s_jrn_op','s_centralized','s_stock_goods');
      foreach ($a_seq as $seq ) {
      	$sql=sprintf("select setval('%s',1,false)",$seq);
      	$Res=ExecSql($cn_mod,$sql);
	}
   	$sql="select jrn_def_id from jrn_def ";
   	$Res=ExecSql($cn_mod,$sql);
    	$Max=pg_NumRows($Res);
    	for ($seq=0;$seq<$Max;$seq++) {
	    $row=pg_fetch_array($Res,$seq);
	    $sql=sprintf ("select setval('s_jrn_%d',1,false)",$row['jrn_def_id']);
	    ExecSql($cn_mod,$sql);
    	}
      
      
    }
    // Show all available templates

    $Res=ExecSql($cn,"select mod_id,mod_name,mod_desc from 
                      modeledef order by mod_name");
    $count=pg_NumRows($Res);
    if ( $count == 0 ) {
      echo "No template available";
    } else {
      echo "<H2>Modèles</H2>";

      echo '<table width="100%" border="1">';
      echo "<TR><TH>Nom</TH>".
	"<TH>Description</TH>".
	"<th></th>".
	"</TR>";

      for ($i=0;$i<$count;$i++) {
	$mod=pg_fetch_array($Res,$i);
	printf('<TR>'.
               '<TD><b> %s</b> </TD>'.
	       '<TD><I> %s </I></TD>'.
	       '<td> '.
	       ' <input type="button" name="Effacer" '.
	       ' Value="Effacer" onClick="confirm_remove(\''.$_REQUEST['PHPSESSID'].'\',\''.$mod['mod_id'].'\',\'mod\');" \>'.
	       '</td>'.

	       '</TR>',
	       $mod['mod_name'],
	       $mod['mod_desc']);

      }// for
      echo "</table>";
    }// if count = 0
      echo "Si vous voulez récupérer toutes les adaptations d'un dossier ".
	" dans un autre dossier, vous pouvez en faire un modèle.".
	" Seules les fiches, la structure des journaux, les périodes,... seront reprises ".
	"et aucune données du dossier sur lequel le dossier est basé.";

    // Show All available folder
    $Res=ExecSql($cn,"select dos_id, dos_name,dos_description from ac_dossier
                      order by dos_name");
    $count=pg_NumRows($Res);
    $available="";
    if ( $count != 0 ) {
      $available='<SELECT NAME="FMOD_DBID">';
      for ($i=0;$i<$count;$i++) {
	$db=pg_fetch_array($Res,$i);
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
    <TD> Basé sur </TD>
    <TD> <? echo $available ?></TD>
</TR>
<TR>
    <td colspan="2"> <INPUT TYPE="SUBMIT" VALUE="Add a template"></TD>
</TR>
</TABLE>
</form>




