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
/* $Revision$ */
include_once ("ac_common.php");
include_once("jrn.php");
include_once("preference.php");
include_once("poste.php");
include_once("error.php");
include_once("user_common.php");

html_page_start($g_UserProperty['use_theme'],"onLoad=\"CheckTotal();\"");
if ( ! isset ( $g_dossier ) ) {
  echo "You must choose a Dossier ";
  exit -2;
}
include_once ("postgres.php");
include_once ("check_priv.php");
/* Admin. Dossier */
$rep=DbConnect();
include_once ("class_user.php");
$User=new cl_user($rep);
$User->Check();
if ( isset( $p_jrn )) {
  $_SESSION["g_jrn"];
  $g_jrn=$p_jrn;
}
    $cn=DbConnect($g_dossier);

echo '<SCRIPT LANGUAGE="javascript" SRC="compute.js"></SCRIPT>';
echo JS_CONCERNED_OP;
echo JS_VIEW_JRN_DETAIL;

/* Get MaxLine */
include_once("poste.php");
include_once ("top_menu_compta.php");
ShowMenuCompta($g_dossier,$g_UserProperty);
ShowMenuComptaRight($g_dossier,$g_UserProperty);
if ( $g_UserProperty['use_admin'] == 0 ) {
  $r=CheckAction($g_dossier,$g_user,ENCJRN);
  if ($r == 0 ){
    /* Cannot Access */
    NoAccess();
  }
  if ( isset ($g_jrn)) {
  	$right=CheckJrn($g_dossier,$g_user,$g_jrn);
	  if ($right == 0 ){
	    /* Cannot Access */
	    NoAccess();
	    exit -1;
	  }
    } // if isset g_jrn

}

ShowMenuJrnUser($g_dossier,$g_UserProperty);

if ( isset ( $_GET["action"] )) {
  if ( $_GET["action"] == "view" ) {

    // view all the journal's operation
    echo '<DIV class="redcontent">';
    ShowMenuRecherche($g_dossier,$g_jrn);
    ViewJrn($g_dossier,$g_user,$g_jrn,"enc_jrn.php");
    echo '</DIV>';
  }
  if ( $_GET["action"] == "record" ) {
    if ( CheckJrn($g_dossier,$g_user,$g_jrn) < 2 ) {
      NoAccess();
      exit -1;

    }
    echo_debug(__FILE__,__LINE__,"record");
    $max_deb=$_GET["max_deb"];
    $max_cred=$_GET["max_cred"];
    RecordJrn($g_dossier,$g_user,$g_jrn,$max_deb,$max_cred);
  }
  
  if ($_GET["action"]=="update" ) {
    if ( CheckJrn($g_dossier,$g_user,$g_jrn) < 2 ) {
      NoAccess();
      exit -1;
    
    }
    // p_id is jrn.jr_id
    $p_id=$_GET["line"];
    echo_debug(__FILE__,__LINE__," action = update p_id = $p_id");
    $r ='<FORM METHOD="POST" ACTION="enc_jrn.php">';
    $r.=UpdateJrn($cn,$p_id);
    $r.='<input type="SUBMIT" name="update_record" value="Enregistre">';
    $r.='</FORM>';
    echo '<div class="redcontent">';
    echo $r;
    echo '</div>';
  }    
  
}
?>

<?
  if ( isset ($_POST['add_line_deb'])	) {
    echo '<DIV class="redcontent">';
    foreach ( $HTTP_POST_VARS as $key=>$element) {
      ${"p_$key"}=$element;
    }
    $p_MaxDeb+=2;
    echo "</DIV>";
    CorrectRecord($g_dossier,$g_user,$g_jrn,$p_MaxDeb,$p_MaxCred,$HTTP_POST_VARS);
    echo_debug(__FILE__,__LINE__,"CorrectRecord($g_dossier,$g_user,$g_jrn,$p_MaxDeb,$p_MaxCred,$HTTP_POST_VARS);");
  }

  if ( isset ($_POST['add_line_cred'])	) {
    echo '<DIV class="redcontent">';
    foreach ( $HTTP_POST_VARS as $key=>$element) {
      ${"p_$key"}=$element;
    }
    $p_MaxCred+=2;
    echo "</DIV>";
    CorrectRecord($g_dossier,$g_user,$g_jrn,$p_MaxDeb,$p_MaxCred,$HTTP_POST_VARS);
    echo_debug(__FILE__,__LINE__,"CorrectRecord($g_dossier,$g_user,$g_jrn,$p_MaxDeb,$p_MaxCred+10,$HTTP_POST_VARS);");
  }
 if ( isset ($_POST['viewsearch']) ) {
   reset($HTTP_POST_VARS);
 
   echo '<DIV class="redcontent">';
   ShowMenuRecherche($g_dossier,$g_jrn,$HTTP_POST_VARS);

   ViewJrn($g_dossier,$g_user,$g_jrn,"enc_jrn.php",$HTTP_POST_VARS);
   echo '</DIV>';
 
 }

if ( isset($_POST['add_record']) ) {
    echo '<DIV class="redcontent">';
    foreach ( $HTTP_POST_VARS as $name=>$element ) {
      echo_debug(__FILE__,__LINE__,"element $name -> $element ");
      // Sauve les données dans des variables
      ${"p_$name"}=$element;
    }
    $userPref=GetUserPeriode($cn,$g_user);
    list ($l_date_start,$l_date_end)=GetPeriode($cn,$userPref);

    $p_op_date=$p_op_date.substr($l_date_start,2,8);
    echo_debug(__FILE__,__LINE__,"p_op_date is $p_op_date");
    $aHttp=$HTTP_POST_VARS;
    $aHttp['op_date']=$p_op_date;
    // Verifie data
    //    $result=VerifData($cn,$HTTP_POST_VARS,$g_user);
    $result=VerifData($cn,$aHttp,$g_user);
    if ($result != NOERROR) {
      // Parse result
      AnalyzeError($result);

      echo "</DIV>";
      CorrectRecord($g_dossier,$g_user,$g_jrn,$p_MaxDeb,$p_MaxCred,$HTTP_POST_VARS);
      return;
    }

    $Res=StartSql($cn);
    $seq=GetNextId($cn,'j_grpt')+1;
    $s_op=GetNextId($cn,'j_id')+1;
	
	
    $tot_cred=0;
    $tot_deb=0;

    //debit
    for ( $i = 0; $i < $p_MaxDeb; $i++) {
      $montant=${"p_mont_deb$i"};
      $l_class=${"p_class_deb$i"};
      if ( strlen(trim($montant)) != 0 && $montant != 0) {
	${"p_text_deb$i"}=FormatString(GetPosteLibelle($g_dossier,$l_class));
	//	$p_text=(FormatString(${"p_text_deb$i"})==null)?:FormatString(${"p_text_deb$i"});
	$Sql="insert into jrnx(j_id,j_date,j_montant,j_poste,j_grpt,
                j_jrn_def,j_debit,j_tech_user,j_tech_per) 
                values ( $s_op,to_date('$p_op_date','DD.MM.YYYY'), "
                .$montant.",
                $l_class,$seq,
                $g_jrn,true,'$g_user',$userPref)";
	echo_debug(__FILE__,__LINE__,"sql $Sql");
	$s_op++;
	$tot_deb+=$montant;
	$Res=ExecSql($cn,$Sql);
  	if ( $Res == false ) { Rollback($cn); EndSql($cn); return;}
        // For the user profile we need the correct sequence id
	AlterSequence($cn,'s_jrn_op',$s_op);
      }
    }
    for ( $i = 0; $i < $p_MaxCred; $i++) {
      $montant=${"p_mont_cred$i"};
      $l_class=${"p_class_cred$i"};
      if ( strlen(trim($montant)) != 0 && $montant != 0) {
	//$p_text=(FormatString(${"p_text_cred$i"})==null)?FormatString(GetPosteLibelle($g_dossier,$l_class)):FormatString(${"p_text_cred$i"});
	${"p_text_cred$i"}=FormatString(GetPosteLibelle($g_dossier,$l_class));
	$Sql="insert into jrnx(j_id,j_date,j_montant,j_poste,j_grpt,
                j_jrn_def,j_debit,j_tech_user,j_tech_per) 
                values ( $s_op,to_date('$p_op_date','DD.MM.YYYY'), $montant,
                $l_class,$seq,
                $g_jrn,false,'$g_user',$userPref)";
	echo_debug(__FILE__,__LINE__,"sql $Sql");
	$s_op++;
	$tot_cred+=$montant;
	$Res=ExecSql($cn,$Sql);
	if ( $Res == false ) { Rollback($cn); EndSql($cn); break;}
        // For the user profile we need the correct sequence id
	AlterSequence($cn,'s_jrn_op',$s_op);

      }
    }

    


      $jrn_id=GetNextJrnId($cn,'jr_id')+1;
      if ( ! isset ($p_ech) ) $p_ech="";
      $l_date=isDate($p_ech);
      if ( $l_date == null) {
	$p_ech='null';
      } else {
	$p_ech="to_char('".$l_date."','DD.MM.YYYY')";
	    }
      $comment=FormatString($p_comment);
      
      $Sql=sprintf("insert into jrn(jr_id,jr_def_id,jr_comment,jr_date,jr_grpt_id,
                            jr_montant,jr_tech_per) values(%s,%s,'%s',%s,%d,%f,%d)",
		   $jrn_id,
		   $g_jrn,
		   $comment,
		   $p_ech,
		   $seq,$tot_deb,$userPref);
      echo_debug(__FILE__,__LINE__,"Sql $Sql");
      $Res=ExecSql($cn,$Sql);
      if ($Res) 
	SetInternalCode($cn,$seq,$g_jrn);
      if ( isset ($p_rapt)) 	  InsertRapt($cn,$jrn_id,$p_rapt);
    
    
    if ( $Res) {
      Commit($cn); 
      EndSql($cn);
      
        // For the user profile we need the correct sequence id
	AlterSequence($cn,'s_jrn',$jrn_id);
      //	AlterSequence($cn,"s_grpt",$seq+1);
      //AlterSequence($cn,"s_jrn_op",$s_op);
      // Add the p_text to the array

      ViewRecord($g_dossier,$g_jrn,$seq,$p_MaxDeb,$p_MaxCred,$aHttp);
      //      echo_debug(__FILE__,__LINE__,"ViewRecord($g_dossier,$g_jrn,$seq,$p_MaxDeb,$p_MaxCred,$HTTP_POST_VARS);");
            echo_debug(__FILE__,__LINE__,"ViewRecord($g_dossier,$g_jrn,$seq,$p_MaxDeb,$p_MaxCred,$aHttp);");
    } else
      {
	Rollback($cn); 
	EndSql($cn);
      }
} // _POST['add_record']

if ( isset($_POST['update_record']) ) {
  // NO UPDATE except rapt & comment
  UpdateComment($cn,$_POST['jr_id'],$_POST['comment']);
  InsertRapt($cn,$_POST['jr_id'],$_POST['rapt']);
  echo '<div class="redcontent">';
    ViewJrn($g_dossier,$g_user,$g_jrn,"enc_jrn.php");
  echo '</div>';

} // if update_record

?>
</DIV>
<?
html_page_stop();
?>
