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

include_once("ac_common.php");
include_once("postgres.php");
include_once("debug.php");
include_once("user_menu.php");


html_page_start($g_UserProperty['use_theme']);
echo_debug(__FILE__,__LINE__,"entering admin_repo");

$rep=DbConnect();
include_once ("class_user.php");
$User=new cl_user($rep);
$User->Check();
if ($g_UserProperty['use_admin'] != 1) {
  html_page_stop();
  return;
}

echo '<H2 class="info"> Administration Globale</H2>';
echo "<div>".MenuAdmin()."</div>";




?>
<DIV >
<?
if ( isset ($_GET["action"]) ) {
  if ( $_GET["action"]=="user_mgt" ) {
    // Add user
    if ( isset ($_POST["LOGIN"]) ) {
      $cn=DbConnect();
      $pass5=md5($PASS);
      $Res=ExecSql($cn,"insert into ac_users(use_first_name,use_name,use_login,use_active,use_pass
                        ,use_usertype)
                    values ('".$_POST["FNAME"]."','".$_POST["LNAME"]."','".$_POST["LOGIN"]."',1,'$pass5',
                    'user')");
    } //SET login

    // Show all the existing user on 7 columns
    $cn=GetAllUser();
    echo_debug(__FILE__,__LINE__,"Array = $cn");
    $compteur=0;
?>
<h2>Gestion Utilisateurs</h2>
<TABLE><TR>
<?
    if ( $cn != null ) {
      foreach ( $cn as $rUser) {
	$compteur++;
	if ( $compteur==0 ) echo "<TR>";
	if ( $compteur%3 == 0)     echo "</TR><TR>";
	if ( $rUser['use_active'] == 0 ) {
	  $Active="not actif";
	} else {
	  $Active="";
	}
	printf('<TD><A HREF=priv_user.php?UID=%s> %s %s ( %s )</A> %s </TD>',
	       $rUser['use_id'],
	       $rUser['use_first_name'],
	       $rUser['use_name'],
	       $rUser['use_login'],
	       $Active);
      }// foreach
    } // $cn != null
?>
</TABLE>
<TABLE> <TR> 
<form action="admin_repo.php?action=user_mgt" method="POST">
<TD><H3>Ajout d'utilisateur<H3></TD></TR>
<?
    echo '<TR><TD> First Name </TD><TD><INPUT TYPE="TEXT" NAME="FNAME"></TD>';
    echo '<TD> Last Name </TD><TD><INPUT TYPE="TEXT" NAME="LNAME"></TD></TR>';
    echo '<TR><TD> login </TD><TD><INPUT TYPE="TEXT" NAME="LOGIN"></TD>';
    echo '<TD> password </TD><TD> <INPUT TYPE="TEXT" NAME="PASS"></TD></TR>';
    echo '<TD> <INPUT TYPE="SUBMIT" Value="Create user" NAME="ADD"></TD>';
    echo '</TABLE>';

?>
</FORM>

<?
    // check and add an user (see form below)

  } // action=user_mgt
  if ( $_GET["action"]=="dossier_mgt") {
    // check and add an new folder
    if ( isset ($_POST["DATABASE"]) ) {
      $cn=DbConnect();
      $dos=trim($_POST["DATABASE"]);
      if (strlen($dos)==0) {
	echo ("Dataname name is empty");
	exit -1;
      }
      $desc=FormatString($_POST["DESCRIPTION"]);
      $Res=ExecSql($cn,"insert into ac_dossier(dos_name,dos_description)
                    values ('".$dos."','$desc')");
      // If the id is not null, name successfully inserted
      // Database created
      $l_id=GetDbId($dos);
      if ( $l_id != 0) {
	$Sql=sprintf("CREATE DATABASE DOSSIER%d encoding='ISO8859-1' TEMPLATE MOD%d",$l_id,$_POST["FMOD_ID"]);
	echo_debug($Sql);
	ExecSql($cn,$Sql);
	$Res=ExecSql($cn,"insert into jnt_use_dos (use_id,dos_id) values (1,$l_id)");
      } // if $l_id != 0
    } // $_POST[DATABASE]
?>
   <h2> Dossier Management</h2>

<TABLE BORDER=1>
<?
$cn=DbConnect();
$Res=ShowDossier('all');
$compteur=1;
$template="";

 // show all dossiers
if ( $Res != null ) {
  foreach ( $Res as $Dossier) {
    
    if ( $compteur%2 == 0 ) 
    	$cl='class="odd"';
	else
	$cl='class="even"';

    echo "<TR $cl><TD VALIGN=\"TOP\"> <B>$Dossier[dos_name]</B> </TD><TD><I>  $Dossier[dos_description]</I></TD></TR>";
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

// Add a new folder
?>
</TABLE>
 <FORM ACTION="admin_repo.php?action=dossier_mgt" METHOD="POST">
    <TABLE>
    <TR>
    <TD> Name</td><td>  <INPUT TYPE="TEXT" NAME="DATABASE"> </TD>
    </TR><TR>
    <TD> Description</td><td>  <TEXTAREA COLS="60" ROWS="2" NAME="DESCRIPTION" ></TEXTAREA> </TD>
    </TR>
    <TR> <TD> Modèle</td><td>  <? echo $template; ?> </TD></TR>

    <TR>
    <TD> <INPUT TYPE=SUBMIT VALUE="Create Folder"></TD>
    </TR>
    </TABLE>
 </FORM>

<?

  } // action = dossier_mgt
  if ( $_GET["action"] == "modele_mgt" ) {
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
	   $Sql=sprintf("CREATE DATABASE MOD%d encoding='ISO8859-1' TEMPLATE DOSSIER%s",$l_id,$_POST["FMOD_DBID"]);
	   ExecSql($cn,$Sql);
 	}
      }// if $mod_name != null

      $cn_mod=dbconnect($l_id,'mod');
      // Clean some tables 
      $Res=ExecSql($cn_mod,"truncate table jrn");
      $Res=ExecSql($cn_mod,"truncate table jrnx");
      $Res=ExecSql($cn_mod,"truncate table centralized");
      $Res=ExecSql($cn_mod,"truncate table stock_goods");
//	Reset the closed periode
      $Res=ExecSql($cn_mod,"update parm_periode set p_closed='t'");
      // Reset Sequence
      $a_seq=array('s_jrn','s_jrn_op','s_centralized','s_stock_goods');
      foreach ($seq as $a_seq ) {
      	$sql=sprintf("select nextval('%s',1,false)",$seq);
      	$Res=ExecSql($cn_mod,$sql);
	}
   	$sql="select jrn_def_id from jrn_def ";
   	$Res=ExecSql($cn_mod,$sql);
    	$Max=pg_NumRows($Res);
    	for ($seq=0;$seq<$Max;$seq++) {
	    $row=pg_fetch_array($Res,$seq);
	    $sql=sprintf ("select setval('s_jrn_%d,1,false)",$row['jrn_def_id']);
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
	"</TR>";

      for ($i=0;$i<$count;$i++) {
	$mod=pg_fetch_array($Res,$i);
	printf('<TR>'.
               '<TD><b> %s</b> </TD>'.
	       '<TD><I> %s </I></TD>'.
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
    <TD><TEXTAREA" ROWS="2" COLS="60" NAME="FMOD_DESC"></Textarea></TD>
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
<?

  }// action = modele_mgt
} // action is set
?>
</DIV>
<?

html_page_stop();
?>
